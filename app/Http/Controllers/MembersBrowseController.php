<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\MosqueMembership;
use Illuminate\Http\Request;

class MembersBrowseController extends Controller
{
    /**
     * Browse opposite-gender members of MY mosque with filters
     * Strict isolation: only approved members of the SAME mosque
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Must be an approved member of a mosque
        $mosqueId = MosqueMembership::approvedMosqueId($user->id);
        if (!$mosqueId) {
            return response()->json([
                'error' => 'Rejoignez une mosquée et attendez l\'approbation de l\'imam.',
            ], 403);
        }

        // My own biodata (for gender + exclusion)
        $myBiodata = Biodata::where('user_id', $user->id)->first();
        if (!$myBiodata) {
            return response()->json(['error' => 'Complétez votre profil d\'abord.'], 403);
        }

        // Must be approved (moderated)
        if (!$myBiodata->is_approved) {
            return response()->json([
                'error' => 'Votre profil est en attente de validation.',
                'status' => 'pending_approval',
            ], 403);
        }

        $oppositeGender = $myBiodata->gender === 'male' ? 'female' : 'male';

        $query = Biodata::query()
            ->where('mosque_id', $mosqueId)
            ->where('gender', $oppositeGender)
            ->where('is_approved', 1)
            ->where('visible_to_mosque', 1)
            ->where('user_id', '!=', $user->id)
            ->with('user');

        // ─── Filters ───
        // Age range
        if ($request->filled('min_age')) {
            $query->where('age', '>=', (int) $request->min_age);
        }
        if ($request->filled('max_age')) {
            $query->where('age', '<=', (int) $request->max_age);
        }

        // Marital status (comma-separated multi)
        if ($request->filled('marital_status')) {
            $statuses = array_filter(explode(',', $request->marital_status));
            if ($statuses) $query->whereIn('maritial_status', $statuses);
        }

        // Country
        if ($request->filled('country')) {
            $query->where('permanent_country', $request->country);
        }

        // Madhab
        if ($request->filled('madhab')) {
            $query->where('madhab', $request->madhab);
        }

        // Prayer level / practice
        if ($request->filled('prayer_level')) {
            $query->where('prayer_level', $request->prayer_level);
        }
        if ($request->filled('practice_religion')) {
            $query->where('practice_religion', $request->practice_religion);
        }

        // Height range
        if ($request->filled('min_height')) {
            $query->where('height', '>=', (int) $request->min_height);
        }
        if ($request->filled('max_height')) {
            $query->where('height', '<=', (int) $request->max_height);
        }

        // Children preference
        if ($request->filled('children_pref')) {
            $query->where('children_pref', $request->children_pref);
        }

        // Salafi only
        if ($request->filled('salafy_only') && $request->salafy_only === '1') {
            $query->where('salafy', '!=', 'no');
        }

        // Polygamy (men only filter)
        if ($request->filled('polygamy')) {
            $query->whereIn('polygamy', ['yes', 'nodecision']);
        }

        // No children
        if ($request->filled('no_children') && $request->no_children === '1') {
            $query->where('boys', 0)->where('girls', 0);
        }

        // Origine (country of origin)
        if ($request->filled('origine')) {
            $query->where('origine', $request->origine);
        }

        // Ethnicity
        if ($request->filled('ethnicity')) {
            $query->where('ethnicity', $request->ethnicity);
        }

        // Search text (bio/name)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('bio', 'like', "%{$q}%")
                    ->orWhere('looking_for', 'like', "%{$q}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'recent');
        $query = match ($sort) {
            'age_asc' => $query->orderBy('age'),
            'age_desc' => $query->orderByDesc('age'),
            'completion' => $query->orderByDesc('completeness'),
            default => $query->orderByDesc('created_at'),
        };

        $members = $query->paginate(12)->through(function (Biodata $b) {
            return [
                'id' => $b->id,
                'user_id' => $b->user_id,
                'biodata_code' => $b->biodata_code,
                'name' => $b->user->name ?? null,
                'age' => $b->age,
                'height' => $b->height,
                'maritial_status' => $b->maritial_status,
                'have_children' => $b->have_children,
                'permanent_country' => $b->permanent_country,
                'permanent_city' => $b->permanent_city ?? null,
                'origine' => $b->origine ?? null,
                'nationality' => $b->nationality ?? null,
                'kounia' => $b->kounia ?? null,
                'job' => $b->job ?? null,
                'ethnicity' => $b->ethnicity ?? null,
                'salafy' => $b->salafy ?? null,
                'hijra' => $b->hijra ?? null,
                'boys' => $b->boys ?? 0,
                'girls' => $b->girls ?? 0,
                'polygamy' => $b->polygamy ?? null,
                'madhab' => $b->madhab,
                'prayer_level' => $b->prayer_level,
                'practice_religion' => $b->practice_religion ?? null,
                'bio' => mb_substr((string) $b->bio, 0, 160),
                'looking_for' => mb_substr((string) $b->looking_for, 0, 160),
                'photo_blurred' => (bool) $b->photo_blurred,
                'completeness' => $b->completeness,
                'is_verified' => (bool) ($b->verified_only_contact ?? false),
            ];
        });

        return response()->json([
            'mosque_id' => $mosqueId,
            'gender' => $oppositeGender,
            'members' => $members,
        ]);
    }

    /**
     * Filter options (static lists for the UI)
     */
    public function options()
    {
        return response()->json([
            'marital_statuses' => ['single', 'divorced', 'widowed'],
            'countries' => ['France', 'Belgique', 'Suisse', 'Maroc', 'Algérie', 'Tunisie', 'Sénégal', 'Mali', 'Canada', 'Royaume-Uni'],
            'madhab' => ['Hanafi', 'Maliki', 'Shafi\'i', 'Hanbali', 'Sans préférence'],
            'prayer_levels' => ['Pratiquant', 'Assidu aux 5 prières', 'Occasionnel', 'En chemin'],
            'practices' => ['Très pratiquant', 'Pratiquant', 'Modéré', 'Peu pratiquant'],
            'children_prefs' => ['Oui', 'Non', 'Peu importe'],
            'ethnicities' => ['Caucasien(ne)', 'Arabe', 'Berbère', 'Asiatique', 'Hispanique', 'Africain(e)', 'Métis(se)'],
            'salafy' => ['Oui', 'Non', 'Pas encore décidé(e)'],
            'hijra' => ['Court terme', 'Long terme', 'Déjà dans un pays musulman', 'Non planifié'],
            'origines' => ['France', 'Belgique', 'Suisse', 'Royaume-Uni', 'Allemagne', 'Maroc', 'Algérie', 'Tunisie', 'Égypte', 'Arabie Saoudite', 'Sénégal', 'Mali', 'Cameroun', 'Turquie'],
        ]);
    }
}
