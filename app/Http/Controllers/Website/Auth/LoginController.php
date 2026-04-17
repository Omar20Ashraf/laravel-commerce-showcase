<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\LoginRequest;
use App\Services\Website\UserAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly UserAuthService $userAuthService
    ) {}

    /**
     * Show the login form.
     */
    public function show(): View
    {
        return view('website.auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $this->userAuthService->login(credentials: $request->validated());

        return redirect()->intended();
    }

    /**
     * Log the user out.
     */
    public function logout(): RedirectResponse
    {
        $this->userAuthService->logout();

        return redirect('/');
    }
}
