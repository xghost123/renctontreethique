<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Biodata;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'translations' => __('frontend'),
            'locale' => session('localization', config('app.locale')),
            'locales' => config('localization.locales'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'gender' => 'required|in:male,female',
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|lowercase|email:rfc,dns|max:100|unique:' . User::class,
            'mobile' => 'required|string|regex:/^0[1-9][0-9]{8}$/|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(6)->max(20)],
            'agree_terms' => 'required|boolean|accepted',
            'agree_privacy' => 'required|boolean|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user) {
            $biodata = new Biodata();
            $biodata->user_id = $user->id;
            $biodata->biodata_code = mt_rand(100000, 999999) . '-' . uniqid();
            $biodata->gender = $validated['gender'];
            $biodata->user_mobile = $validated['mobile'];
            $biodata->user_email = $validated['email'];
            $biodata->save();

            if ($biodata) {
                event(new Registered($user));
                Auth::login($user);
            }
        }

        return redirect(route('profile.status', absolute: false));
    }
}
