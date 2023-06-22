<?php

namespace App\Filament\Pages\Shared;

use App\Settings\AppGeneralSettings;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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
                        ->disableItemMovement(false)
                        ->label('Email администраторов')
                        ->headers(['Адрес'])
                        ->emptyLabel('Нет адресов')
                        ->createItemButtonLabel('Добавить email')
                        ->orderable()
                        ->schema([
                            TextInput::make('address')
                                ->placeholder('example@localhost.ru')
                                ->disableLabel()
                                ->maxLength(128)
                                ->email()
                                ->required(),
                        ]),
                ]),
        ];
    }
}
