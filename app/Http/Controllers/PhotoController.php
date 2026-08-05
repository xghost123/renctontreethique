<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    /**
     * Upload a photo
     * POST /api/photos/upload
     */
    public function upload(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
                'biodata_id' => 'required|integer|exists:biodata,id',
            ]);

            $user = Auth::user();
            $biodata = Biodata::findOrFail($validated['biodata_id']);

            // Check user owns this biodata
            if ($biodata->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized - This is not your profile',
                ], 403);
            }

            // Check quota (max 5 photos per profile)
            $photoCount = Photo::where('biodata_id', $biodata->id)->count();
            if ($photoCount >= 5) {
                return response()->json([
                    'message' => 'Maximum 5 photos allowed per profile',
                    'current_count' => $photoCount,
                ], 422);
            }

            // Process and store the image
            $file = $request->file('photo');
            $filename = Str::uuid() . '.jpg';
            $path = 'photos/' . $user->id;

            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            // Optimize image using Intervention Image
            $image = Image::make($file)
                ->resize(1200, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85); // quality 85%

            // Store the optimized image
            Storage::disk('public')->put(
                $path . '/' . $filename,
                $image->stream()->detach()
            );

            // Create photo record
            $photo = Photo::create([
                'user_id' => $user->id,
                'biodata_id' => $biodata->id,
                'path' => $path . '/' . $filename,
                'original_filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'approved' => false, // Requires admin approval
                'display_order' => $photoCount + 1,
            ]);

            return response()->json([
                'message' => 'Photo uploaded successfully. Awaiting admin approval.',
                'photo' => [
                    'id' => $photo->id,
                    'path' => asset('storage/' . $photo->path),
                    'approved' => $photo->approved,
                    'size' => $photo->size,
                    'created_at' => $photo->created_at,
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error uploading photo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a photo
     * DELETE /api/photos/{id}
     */
    public function delete($id)
    {
        try {
            $photo = Photo::findOrFail($id);
            $user = Auth::user();

            // Check user owns this photo
            if ($photo->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized - This is not your photo',
                ], 403);
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }

            // Delete database record
            $photo->delete();

            return response()->json([
                'message' => 'Photo deleted successfully',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Photo not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Photo delete error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting photo',
            ], 500);
        }
    }

    /**
     * Get photos for a biodata
     * GET /api/biodata/{id}/photos
     */
    public function getByBiodata($biodataId)
    {
        try {
            $biodata = Biodata::findOrFail($biodataId);
            $user = Auth::user();

            // Get photos
            $query = Photo::forBiodata($biodataId);

            // If not the profile owner, only show approved photos
            if (!$user || $biodata->user_id !== $user->id) {
                $query = $query->approved();
            }

            $photos = $query->get()->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'path' => asset('storage/' . $photo->path),
                    'approved' => $photo->approved,
                    'created_at' => $photo->created_at,
                    'size' => $photo->size,
                ];
            });

            return response()->json([
                'biodata_id' => $biodataId,
                'photos' => $photos,
                'count' => count($photos),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Biodata not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Get photos error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching photos',
            ], 500);
        }
    }

    /**
     * Get pending photos for admin approval
     * GET /api/admin/photos/pending
     */
    public function getPendingPhotos()
    {
        try {
            $photos = Photo::pending()
                ->with('user', 'biodata')
                ->orderByDesc('created_at')
                ->paginate(20);

            $photos = $photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'user_id' => $photo->user_id,
                    'user_name' => $photo->user->name,
                    'biodata_id' => $photo->biodata_id,
                    'path' => asset('storage/' . $photo->path),
                    'created_at' => $photo->created_at,
                    'original_filename' => $photo->original_filename,
                ];
            });

            return response()->json([
                'photos' => $photos,
                'total' => Photo::pending()->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Get pending photos error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching pending photos',
            ], 500);
        }
    }
}
