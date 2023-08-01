<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->delete('app_general.notification_rating_start');
        $this->migrator->add('app_general.notification_rating_start_text', []);
        $this->migrator->add('app_general.notification_rating_start_url', '');
    }

    public function down(): void
    {
        $this->migrator->add('app_general.notification_rating_start', '');
        $this->migrator->delete('app_general.notification_rating_start_text');
        $this->migrator->delete('app_general.notification_rating_start_url');
    }
};
