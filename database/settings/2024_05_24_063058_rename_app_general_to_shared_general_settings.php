<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->rename(from: 'app_general.instruction_text', to: 'shared_general.instruction_text');
        $this->migrator->rename(from: 'app_general.instruction_video', to: 'shared_general.instruction_video');
        $this->migrator->rename(from: 'app_general.admin_emails', to: 'shared_general.admin_emails');
        $this->migrator->rename(from: 'app_general.moodle_account_url', to: 'shared_general.moodle_account_url');
        $this->migrator->rename(from: 'app_general.moodle_user_api_url', to: 'shared_general.moodle_user_api_url');
        $this->migrator->rename(from: 'app_general.moodle_token', to: 'shared_general.moodle_token');
        $this->migrator->rename(from: 'app_general.notification_rating_start_text', to: 'shared_general.notification_rating_start_text');
        $this->migrator->rename(from: 'app_general.notification_rating_start_url', to: 'shared_general.notification_rating_start_url');
        $this->migrator->rename(from: 'app_general.logotype', to: 'shared_general.logotype');
        $this->migrator->rename(from: 'app_general.moodle_auth_enabled', to: 'shared_general.moodle_auth_enabled');
    }

    public function down(): void
    {
        $this->migrator->rename(from: 'shared_general.instruction_text', to: 'app_general.instruction_text');
        $this->migrator->rename(from: 'shared_general.instruction_video', to: 'app_general.instruction_video');
        $this->migrator->rename(from: 'shared_general.admin_emails', to: 'app_general.admin_emails');
        $this->migrator->rename(from: 'shared_general.moodle_account_url', to: 'app_general.moodle_account_url');
        $this->migrator->rename(from: 'shared_general.moodle_user_api_url', to: 'app_general.moodle_user_api_url');
        $this->migrator->rename(from: 'shared_general.moodle_token', to: 'app_general.moodle_token');
        $this->migrator->rename(from: 'shared_general.notification_rating_start_text', to: 'app_general.notification_rating_start_text');
        $this->migrator->rename(from: 'shared_general.notification_rating_start_url', to: 'app_general.notification_rating_start_url');
        $this->migrator->rename(from: 'shared_general.logotype', to: 'app_general.logotype');
        $this->migrator->rename(from: 'shared_general.moodle_auth_enabled', to: 'app_general.moodle_auth_enabled');
    }
};
