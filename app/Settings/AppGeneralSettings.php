<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppGeneralSettings extends Settings
{
    public ?string $instruction_text;

    public ?string $instruction_video;

    public ?array $admin_emails;

    public static function group(): string
    {
        return 'app_general';
    }
}
