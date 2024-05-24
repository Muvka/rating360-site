<?php

namespace App\Filament\Pages\Shared;

use App\Settings\Shared\GeneralSettings;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\SettingsPage;

class GeneralSettingsPage extends SettingsPage
{
    protected static ?string $navigationGroup = 'Настройки';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $slug = 'shared-general-settings-page';

    protected static ?string $title = 'Главные настройки';

    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Tabs::make('Разделы')
                    ->columns(1)
                    ->tabs([
                        Tabs\Tab::make('Общие')
                            ->columns(4)
                            ->schema([
                                FileUpload::make('logotype')
                                    ->label('Логотип')
                                    ->image()
                                    ->itemPanelAspectRatio(1)
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('90')
                                    ->imageResizeTargetHeight('90'),
                            ]),
                        Tabs\Tab::make('Инструкция')
                            ->schema([
                                RichEditor::make('instruction_text')
                                    ->label('Текст'),
                                TextInput::make('instruction_video')
                                    ->label('Видео')
                                    ->hint('Идентификатор видео'),
                            ]),
                        Tabs\Tab::make('Контакты')
                            ->columns()
                            ->schema([
                                TextInput::make('learning_center_email_address')
                                    ->label('Адрес почты учебного центра')
                                    ->placeholder('example@learningcenter.ru')
                                    ->email(),
                                Repeater::make('admin_emails')
                                    ->defaultItems(0)
                                    ->label('Email администраторов')
                                    ->addActionLabel('Добавить email')
                                    ->reorderable()
                                    ->simple(
                                        TextInput::make('address')
                                            ->placeholder('example@localhost.ru')
                                            ->hiddenLabel()
                                            ->maxLength(128)
                                            ->email()
                                            ->required(),
                                    ),
                            ]),
                        Tabs\Tab::make('Moodle')
                            ->schema([
                                Toggle::make('moodle_auth_enabled')
                                    ->label('Авторизация через Moodle')
                                    ->reactive(),
                                TextInput::make('moodle_account_url')
                                    ->label('Личный кабинет')
                                    ->url()
                                    ->visible(fn (Get $get) => $get('moodle_auth_enabled') === true)
                                    ->required(),
                                TextInput::make('moodle_user_api_url')
                                    ->label('Адрес пользовательского API')
                                    ->url()
                                    ->visible(fn (Get $get) => $get('moodle_auth_enabled') === true)
                                    ->required(),
                                TextInput::make('moodle_token')
                                    ->label('Токен')
                                    ->visible(fn (Get $get) => $get('moodle_auth_enabled') === true),
                            ]),
                        Tabs\Tab::make('Уведомления')
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
                        Tabs\Tab::make('FAQ')
                            ->columns(3)
                            ->schema([
                                TextInput::make('faq_notification_email')
                                    ->label('Адрес электронной почты для уведомлений')
                                    ->placeholder('example@mail.ru')
                                    ->email(),
                            ]),
                    ]),
            ]);
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
