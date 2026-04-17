<?php

namespace App\Services\Website;

use App\Jobs\CreateFreeTrialSubscriptionJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
    use App\Jobs\TransferGuestCartJob;
use Illuminate\Validation\ValidationException;

class UserAuthService
{
    /**
     * Handle user registration.
     */
    public function register(array $data, ?string $guestIp = null): void
    {
        $user = DB::transaction(function () use ($data, $guestIp) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'city_id' => $data['city'],
            ]);
            CreateFreeTrialSubscriptionJob::dispatch($user);

            if ($guestIp) {
                TransferGuestCartJob::dispatch($user, $guestIp);
            }

            return $user;
        });

        Auth::login($user);
    }

    public function login(array $credentials, ?string $guestIp = null): void
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
                'password' => trans('auth.failed'),
            ]);
        }

        session()->regenerate();

        if ($guestIp) {
            TransferGuestCartJob::dispatch(Auth::user(), $guestIp);
        }
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
