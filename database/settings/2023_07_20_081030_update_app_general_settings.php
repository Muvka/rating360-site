<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('app_general.notification_rating_start', '');
        $this->migrator->delete('app_general.moodle_url');
        $this->migrator->add('app_general.moodle_account_url', 'https://edu.zhcom.ru/my');
        $this->migrator->add('app_general.moodle_user_api_url', 'https://edu.zhcom.ru/api/users/byToken');
    }

    public function down(): void
    {
        $this->migrator->delete('app_general.notification_rating_start');
        $this->migrator->add('app_general.moodle_url', 'https://edu.zhcom.ru/my');
        $this->migrator->delete('app_general.moodle_account_url');
        $this->migrator->delete('app_general.moodle_user_api_url');
    }
};
