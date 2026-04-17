<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\RegisterRequest;
use App\Services\Website\UserAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserAuthService $userAuthService
    ) {}

    /**
     * Show the registration form.
     */
    public function show(): View
    {
        return view('website.auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->userAuthService->register(data: $request->validated(), guestIp: $request->ip());

        return redirect()->intended();
    }
}
