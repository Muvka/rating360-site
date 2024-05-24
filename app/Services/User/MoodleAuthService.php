<?php

namespace App\Services\User;

use App\Models\Company\Employee;
use App\Providers\RouteServiceProvider;
use App\Settings\Shared\GeneralSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoodleAuthService
{
    public function __construct(private readonly GeneralSettings $settings)
    {
    }

    public function login(Request $request): RedirectResponse
    {
        auth()->logout();

        if (! $this->settings->moodle_auth_enabled) {
            return redirect()->route('client.user.login.show');
        }

        if (! $request->userToken || ! $this->settings->moodle_token) {
            return redirect()->away($this->settings->moodle_account_url);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->settings->moodle_token,
        ])->get($this->settings->moodle_user_api_url.'/'.$request->userToken);

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $data = null;
        }

        if (! $data || empty($data['result'])) {
            return redirect()->away($this->settings->moodle_account_url);
        }

        $email = $data['result']['email'] ?? '';
        $sourceId = $data['result']['id'] ?? null;

        if (! $email && ! $sourceId) {
            return redirect()->away($this->settings->moodle_account_url);
        }

        $user = Employee::when($sourceId, function (Builder $query, string $sourceId) {
            $query->where('source_id', $sourceId);
        })
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $nameParts = explode(' ', $data['result']['name']);

            $user = Employee::create([
                'source_id' => $sourceId,
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1],
                'email' => $email,
            ]);
        } elseif (! $user->source_id && $sourceId) {
            $user->source_id = $sourceId;
            $user->save();
        }

        auth()->login($user, true);

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
