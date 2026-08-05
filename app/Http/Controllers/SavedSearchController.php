<?php

namespace App\Http\Controllers;

use App\Models\SavedSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedSearchController extends Controller
{
    /**
     * Get all saved searches for the authenticated user
     * GET /api/saved-searches
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $searches = SavedSearch::where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $searches,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching saved searches: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching saved searches',
            ], 500);
        }
    }

    /**
     * Save a new search
     * POST /api/saved-searches
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'filters' => 'required|array',
            ]);

            // Check if user already has this saved search
            $existing = SavedSearch::where('user_id', $user->id)
                ->where('name', $validated['name'])
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'A search with this name already exists',
                ], 422);
            }

            $savedSearch = SavedSearch::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'filters' => $validated['filters'],
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Search saved successfully',
                'data' => $savedSearch,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error saving search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving search',
            ], 500);
        }
    }

    /**
     * Update a saved search
     * PUT /api/saved-searches/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $savedSearch = SavedSearch::findOrFail($id);

            // Verify ownership
            if ($savedSearch->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This is not your saved search',
                ], 403);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:500',
                'filters' => 'nullable|array',
                'is_active' => 'nullable|boolean',
            ]);

            $savedSearch->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Search updated successfully',
                'data' => $savedSearch,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Saved search not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating search',
            ], 500);
        }
    }

    /**
     * Delete a saved search
     * DELETE /api/saved-searches/{id}
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $savedSearch = SavedSearch::findOrFail($id);

            // Verify ownership
            if ($savedSearch->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - This is not your saved search',
                ], 403);
            }

            $savedSearch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Search deleted successfully',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Saved search not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting search',
            ], 500);
        }
    }
}
