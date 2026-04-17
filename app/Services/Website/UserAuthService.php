<?php

namespace App\Services\Website;

use App\Jobs\CreateFreeTrialSubscriptionJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    /**
     * Handle user registration.
     */
    public function register(array $data): void
    {
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'city_id' => $data['city'],
            ]);
            CreateFreeTrialSubscriptionJob::dispatch($user);

            return $user;
        });

        Auth::login($user);
    }

    public function login(array $credentials): void
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
                'password' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();
    }

    /**
     * Handle user logout.
     */
    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
    }
}
