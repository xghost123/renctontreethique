<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Mosque;
use App\Models\MosqueMembership;
use Illuminate\Http\Request;

class ProfileWizardController extends Controller
{
    /**
     * Get my current profile state (for the wizard + approval flow)
     */
    public function state(Request $request)
    {
        $user = $request->user();
        $biodata = Biodata::where('user_id', $user->id)->first();

        $membership = MosqueMembership::where('user_id', $user->id)
            ->where('status', 'approved')->with('mosque')->first();

        $pendingJoin = \App\Models\MosqueJoinRequest::where('user_id', $user->id)
            ->where('status', 'pending')->with('mosque')->first();

        return response()->json([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'biodata' => $biodata ? $this->serializeBiodata($biodata) : null,
            'completion' => $biodata ? $this->computeCompletion($biodata) : 0,
            'status' => $this->status($biodata),
            'mosque' => $membership ? $membership->mosque : null,
            'pending_join' => $pendingJoin ? $pendingJoin->mosque : null,
        ]);
    }

    private function status(?Biodata $biodata): string
    {
        if (!$biodata) return 'no_profile';
        $s = $biodata->profilstatus ?? 'new';
        if ($s === 'validated' || $s === 'active') return 'active';
        if ($s === 'rejected') return 'rejected';
        return 'pending_approval';
    }

    private function computeCompletion(Biodata $b): int
    {
        $required = [
            'age', 'city', 'nationality', 'permanent_country', 'origine', 'spoken_langage',
            'maritial_status', 'job', 'tall', 'ethnicity',
            'salafy', 'hijra', 'practice_religion_years', 'dress_code_text', 'scholars',
            'health', 'occult', 'bio', 'looking_for', 'prohibitive_criteria',
        ];
        if ($b->gender === 'female') array_push($required, 'polygamy', 'has_tutor');
        if ($b->gender === 'male') array_push($required, 'whatsapp', 'body_type');
        $total = count($required); $done = 0;
        foreach ($required as $f) {
            if ($b->$f !== null && $b->$f !== '') $done++;
        }
        return $total ? (int) round($done / $total * 100) : 0;
    }

    private function serializeBiodata(Biodata $b): array
    {
        return [
            'id' => $b->id,
            'identifier' => $b->identifier,
            'kounia' => $b->kounia,
            'gender' => $b->gender,
            'age' => $b->age,
            'city' => $b->city,
            'whatsapp' => $b->whatsapp,
            'nationality' => $b->nationality,
            'permanent_country' => $b->permanent_country,
            'origine' => $b->origine,
            'spoken_langage' => $b->spoken_langage,
            'maritial_status' => $b->maritial_status,
            'boys' => $b->boys,
            'girls' => $b->girls,
            'dependentchildren' => $b->dependentchildren,
            'children_details' => $b->children_details,
            'polygamy' => $b->polygamy,
            'has_tutor' => $b->has_tutor,
            'tutorname' => $b->tutorname,
            'tutorphone' => $b->tutorphone,
            'tutoraffiliation' => $b->tutoraffiliation,
            'job' => $b->job,
            'tall' => $b->tall,
            'ethnicity' => $b->ethnicity,
            'body_type' => $b->body_type,
            'salafy' => $b->salafy,
            'hijra' => $b->hijra,
            'practice_religion_years' => $b->practice_religion_years,
            'dress_code_text' => $b->dress_code_text,
            'scholars' => $b->scholars,
            'health' => $b->health,
            'occult' => $b->occult,
            'bio' => $b->bio,
            'looking_for' => $b->looking_for,
            'prohibitive_criteria' => $b->prohibitive_criteria,
            'madhab' => $b->madhab,
            'prayer_level' => $b->prayer_level,
            'photo_blurred' => (bool) $b->photo_blurred,
            'verified_only_contact' => (bool) $b->verified_only_contact,
            'is_terms_accepted' => (bool) $b->is_terms_accepted,
            'profilstatus' => $b->profilstatus,
            'completeness' => $b->completeness,
            'is_approved' => (bool) $b->is_approved,
            'mosque_id' => $b->mosque_id,
        ];
    }

    /**
     * Save one wizard step (zawajuna field mapping)
     */
    public function saveStep(Request $request)
    {
        $request->validate([
            'step' => 'required|in:1,2,3,4,5',
            'data' => 'required|array',
        ]);

        $user = $request->user();
        $step = (int) $request->step;
        $data = $request->data;

        $biodata = Biodata::firstOrNew(['user_id' => $user->id]);
        $biodata->user_id = $user->id;

        // Auto-generate identifier on first creation (timestamp + random, like zawajuna)
        if (!$biodata->identifier) {
            $biodata->identifier = time() . random_int(0, 99);
        }

        $allowed = $this->stepFields($step);
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $value = $data[$field];
                if ($value === '' || $value === null) {
                    $biodata->$field = null;
                } else {
                    $biodata->$field = $value;
                }
            }
        }

        $biodata->completeness = $this->computeCompletion($biodata);
        $biodata->save();

        return response()->json([
            'success' => true,
            'completion' => $biodata->completeness,
            'biodata' => $this->serializeBiodata($biodata->fresh()),
        ]);
    }

    /**
     * Submit profile — auto-activated (no admin approval needed for profiles)
     * Moderation now applies to MESSAGES, not profiles.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'cgu_accepted' => 'required|accepted',
            'attest' => 'required|accepted',
        ]);

        $user = $request->user();
        $biodata = Biodata::where('user_id', $user->id)->first();

        if (!$biodata) {
            return response()->json(['error' => 'Complétez votre profil d\'abord.'], 422);
        }

        $completion = $this->computeCompletion($biodata);
        if ($completion < 80) {
            return response()->json([
                'error' => "Votre profil est incomplet ({$completion}%). Complétez au moins 80% avant de soumettre.",
            ], 422);
        }

        // AUTO-ACTIVATE: profiles no longer require admin/imam approval
        $biodata->update([
            'profilstatus' => 'active',
            'is_terms_accepted' => 1,
            'pending_approve' => 0,
            'is_approved' => 1,
            'approved_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre profil est actif ! Vous pouvez maintenant découvrir les membres de votre mosquée.',
            'status' => 'active',
        ]);
    }

    private function stepFields(int $step): array
    {
        return match ($step) {
            1 => ['kounia', 'age', 'city', 'whatsapp', 'nationality', 'permanent_country', 'origine', 'spoken_langage'],
            2 => ['maritial_status', 'polygamy', 'boys', 'girls', 'dependentchildren', 'children_details', 'has_tutor', 'tutorname', 'tutorphone', 'tutoraffiliation'],
            3 => ['job', 'tall', 'ethnicity', 'body_type'],
            4 => ['salafy', 'hijra', 'practice_religion_years', 'dress_code_text', 'scholars', 'madhab', 'prayer_level'],
            5 => ['health', 'occult', 'bio', 'looking_for', 'prohibitive_criteria'],
        };
    }
}
