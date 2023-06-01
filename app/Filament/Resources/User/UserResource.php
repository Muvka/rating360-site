<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\UserResource\Pages;
use App\Models\User\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 40;

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
                    ->schema([
                        TextInput::make('name')
                            ->label('Имя')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                        TextInput::make('email')
                            ->label('Адрес электронной почты')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        CheckboxList::make('roles')
                            ->relationship('roles', 'name')
                            ->label('Роли')
                            ->reactive()
                            ->required()
                            ->columnSpanFull()
                            ->columns(4),

                    ]),
                Section::make('Информация')
                    ->columns(3)
                    ->schema([
                        Select::make('company_id')
                            ->label('Компания')
                            ->relationship('company', 'name')
                            ->required(fn(Closure $get) => ! in_array('1', $get('roles'))),
                        Select::make('subdivision_id')
                            ->label('Подразделение')
                            ->relationship('subdivision', 'name')
                            ->required(fn(Closure $get) => ! in_array('1', $get('roles'))),
                        Fieldset::make('Руководители')
                            ->visible(fn(Closure $get) => in_array('3', $get('roles')))
                            ->schema([
                                Select::make('direct_manager_id')
                                    ->label('Непосредственный')
                                    ->options(function () {
                                        return User::whereRelation('roles', 'slug', 'manager')
                                            ->get()
                                            ->pluck('name', 'id');
                                    })
                                    ->required(),
                                Select::make('functional_manager_id')
                                    ->label('Функциональный')
                                    ->options(function () {
                                        return User::whereRelation('roles', 'slug', 'manager')
                                            ->get()
                                            ->pluck('name', 'id');
                                    }),
                            ]),
                    ]),
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
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Компания')
                    ->placeholder('Нет компании')
                    ->sortable(),
                TagsColumn::make('roles.name')
                    ->label('Роли')
                    ->separator(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->label('Компания')
                    ->relationship('company', 'name'),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Роль'),
            ])
            ->actions([
//                Tables\Actions\Action::make('Тест')
//                    ->icon('heroicon-o-user')
//                    ->button()
//                    ->action(fn (User $record) => $record->roles()->attach(1))
//                    ->visible(fn (User $record): bool => !$record->isAdmin())
//                    ->color('success'),
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
}
