<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('app_general.moodle_auth_enabled', false);
    }

    public function down(): void
    {
        $this->migrator->delete('app_general.moodle_auth_enabled');
    }
};
