<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shared_general.faq_notification_email', '');
    }

    public function down(): void
    {
        $this->migrator->delete('shared_general.faq_notification_email');
    }
};
