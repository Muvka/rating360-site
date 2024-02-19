<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\User\MoodleAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return Inertia::render('User/LoginFormPage', [
            'title' => 'Авторизация',
        ]);
    }

    public function login(LoginRequest $request)
    {
        if (auth()->attempt($request->validated(), true)) {
            $request->session()->regenerate();

            return redirect()->to(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Неверный email пользователя или пароль. Пожалуйста, убедитесь, что вы вводите правильные учетные данные и повторите попытку',
        ]);
    }

    public function moodleLogin(Request $request, MoodleAuthService $moodleAuthService)
    {
        return $moodleAuthService->login($request);
    }
}
