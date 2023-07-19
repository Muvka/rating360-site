<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('app_general.instruction_text', '');
        $this->migrator->add('app_general.instruction_video', '');
        $this->migrator->add('app_general.admin_emails', []);
        $this->migrator->add('app_general.moodle_url', 'https://edu.zhcom.ru/my');
        $this->migrator->add('app_general.moodle_token', 'iZ/11FoeZ4HoqRDyRwIpyvBcAmnlgyAqOgh2RrwHt3s=');
    }

    public function down(): void
    {
        $this->migrator->delete('app_general.instruction_text');
        $this->migrator->delete('app_general.instruction_video');
        $this->migrator->delete('app_general.admin_emails');
        $this->migrator->delete('app_general.moodle_url');
        $this->migrator->delete('app_general.moodle_token');
    }
};
