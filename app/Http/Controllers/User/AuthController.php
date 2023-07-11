<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Settings\AppGeneralSettings;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request, AppGeneralSettings $settings)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        if ( ! $request->userToken || ! $settings->moodle_token) {
            return redirect()->away('https://edu.zhcom.ru/my');
        }

        $client = new Client();

        $response = $client->request('GET', 'https://edu.zhcom.ru/api/users/byToken/'.$request->userToken, [
            'headers' => [
                'Authorization' => 'Bearer '.$settings->moodle_token,
                'Accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);

        if ( ! $data || empty($data['result'])) {
            return redirect()->away('https://edu.zhcom.ru/my');
        }

        $user = Employee::where('email', $data['result']['email'])->first();

        if ( ! $user) {
            $nameParts = explode(' ', $data['result']["concat(firstname, ' ', lastname)"]);

            // TODO: Сохранять компанию и подразделение
            $user = Employee::create([
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1],
                'email' => $data['result']['email'],
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('client.rating.ratings.index');
    }
}
