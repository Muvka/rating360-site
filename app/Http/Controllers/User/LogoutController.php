<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function logout()
    {
        auth()->logout();

        return redirect()->route('client.user.login.show');
    }
}
