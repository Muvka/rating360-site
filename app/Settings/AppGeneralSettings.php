<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppGeneralSettings extends Settings
{
    public ?string $logotype;

    public ?string $instruction_text;

    public ?string $instruction_video;

    public ?array $admin_emails;

    public ?array $notification_rating_start_text;

    public ?string $notification_rating_start_url;

    public bool $moodle_auth_enabled;

    public ?string $moodle_account_url;

    public ?string $moodle_user_api_url;

    public ?string $moodle_token;

    public static function group(): string
    {
        return 'app_general';
    }
}
