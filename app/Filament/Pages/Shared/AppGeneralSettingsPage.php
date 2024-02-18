<?php

namespace App\Filament\Pages\Shared;

use App\Settings\AppGeneralSettings;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;

class AppGeneralSettingsPage extends SettingsPage
{
    protected static ?string $navigationGroup = 'Настройки';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $slug = 'app-general-settings';

    protected static ?string $title = 'Общие';

    protected static string $settings = AppGeneralSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    FileUpload::make('logotype')
                        ->label('Логотип')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('90')
                        ->imageResizeTargetHeight('90'),
                ]),
            Section::make('Инструкция')
                ->schema([
                    RichEditor::make('instruction_text')
                        ->label('Текст'),
                    TextInput::make('instruction_video')
                        ->label('Видео')
                        ->hint('Идентификатор видео'),
                ]),
            Section::make('Контакты')
                ->schema([
                    TableRepeater::make('admin_emails')
                        ->defaultItems(0)
                        ->label('Email администраторов')
                        ->headers([
                            Header::make('address')
                                ->label('Адрес'),
                        ])
                        ->emptyLabel('Нет адресов')
                        ->addActionLabel('Добавить email')
                        ->reorderable()
                        ->schema([
                            TextInput::make('address')
                                ->placeholder('example@localhost.ru')
                                ->hiddenLabel()
                                ->maxLength(128)
                                ->email()
                                ->required(),
                        ]),
                ]),
            Section::make('Moodle')
                ->schema([
                    TextInput::make('moodle_account_url')
                        ->label('Личный кабинет')
                        ->url()
                        ->required(),
                    TextInput::make('moodle_user_api_url')
                        ->label('Адрес пользовательского API')
                        ->url()
                        ->required(),
                    TextInput::make('moodle_token')
                        ->label('Токен'),
                ]),
            Section::make('Уведомления')
                ->schema([
                    Fieldset::make('Начало оценки')
                        ->columns(1)
                        ->schema([
                            Textarea::make('notification_rating_start_text')
                                ->label('Текст')
                                ->required(),
                            TextInput::make('notification_rating_start_url')
                                ->label('Ссылка')
                                ->url(),
                        ]),
                ]),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['notification_rating_start_text'] = explode("\n", $data['notification_rating_start_text']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['notification_rating_start_text'] = implode("\n", $data['notification_rating_start_text']);

        return $data;
    }
}
