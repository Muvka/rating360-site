<?php

namespace App\Filament\Resources\Shared;

use App\Filament\Resources\Shared\UserResource\Pages;
use App\Models\Shared\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Общее';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $label = 'Пользователь';

    protected static ?string $pluralLabel = 'Пользователи';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(3)
                    ->schema(static::getGeneralFormSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('№')
                    ->getStateUsing(
                        static function (stdClass $rowLoop, HasTable $livewire): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->tableRecordsPerPage * (
                                        $livewire->page - 1
                                    ))
                            );
                        }
                    ),
                TextColumn::make('full_name')
                    ->label('Имя')
                    ->wrap()
                    ->sortable(['last_name'])
                    ->searchable(['first_name', 'last_name']),
                ToggleColumn::make('is_admin')
                    ->label('Администратор')
                    ->sortable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGeneralFormSchema($passwordRequired = true): array
    {
        return [
            TextInput::make('first_name')
                ->label('Имя')
                ->minLength(2)
                ->maxLength(64)
                ->required(),
            TextInput::make('middle_name')
                ->label('Отчество')
                ->maxLength(64),
            TextInput::make('last_name')
                ->label('Фамилия')
                ->maxLength(64),
            TextInput::make('password')
                ->label('Пароль')
                ->password()
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->required($passwordRequired),
            TextInput::make('email')
                ->label('Адрес электронной почты')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ];
    }
}
