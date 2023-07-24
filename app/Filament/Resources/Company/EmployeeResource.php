<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\EmployeeResource\Pages;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\DirectSubordinatesRelationManager;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\FunctionalSubordinatesRelationManager;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\ManagerAccessRelationManager;
use App\Models\Company\Employee;

/**/

use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Hash;
use stdClass;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'Компании';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $label = 'Сотрудник';

    protected static ?string $pluralLabel = 'Сотрудники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Пользователь')
                    ->columns(3)
                    ->schema([
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
                            ->dehydrated(fn($state) => filled($state)),
                        TextInput::make('email')
                            ->label('Адрес электронной почты')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                Section::make('Информация')
                    ->columns(3)
                    ->schema([
                        Select::make('city_id')
                            ->label('Город')
                            ->relationship('city', 'name')
                            ->required(),
                        Select::make('company_id')
                            ->label('Компания')
                            ->relationship('company', 'name')
                            ->required(),
                        Select::make('company_division_id')
                            ->label('Отдел')
                            ->relationship('division', 'name')
                            ->searchable(),
                        Select::make('company_subdivision_id')
                            ->label('Подразделение')
                            ->relationship('subdivision', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('directions')
                            ->relationship('directions', 'name')
                            ->label('Направления')
                            ->multiple(),
                        Select::make('company_position_id')
                            ->label('Должность')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('company_level_id')
                            ->label('Уровень сотрудника')
                            ->relationship('level', 'name')
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (?Employee $record, Select $component, $state) {
                                $record->{$component->getName()} = $state;
                                $record->saveQuietly();
                            }),
                    ]),
                Section::make('Руководители')
                    ->columns()
                    ->schema([
                        Select::make('direct_manager_id')
                            ->label('Непосредственный')
                            ->getSearchResultsUsing(
                                fn(string $search) => Employee::where('last_name', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->pluck('full_name', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                                ->full_name)
                            ->searchable()
                            ->required(
                                fn(Closure $get) => ! in_array($get('company_level_id'), ['1', '2'])
                            ),
                        Select::make('functional_manager_id')
                            ->label('Функциональный')
                            ->getSearchResultsUsing(
                                fn(string $search) => Employee::where('last_name', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->pluck('full_name', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                                ->full_name)
                            ->searchable(),
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
                TextColumn::make('full_name')
                    ->label('Имя')
                    ->wrap()
                    ->sortable(['last_name'])
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('company.name')
                    ->label('Компания')
                    ->sortable(),
                TextColumn::make('level.name')
                    ->label('Уровень сотрудника')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_admin')
                    ->label('Администратор')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('city')
                    ->label('Город')
                    ->relationship('city', 'name'),
                SelectFilter::make('company')
                    ->label('Компания')
                    ->relationship('company', 'name'),
                SelectFilter::make('division')
                    ->label('Отдел')
                    ->relationship('division', 'name'),
                SelectFilter::make('subdivision')
                    ->label('Подразделение')
                    ->relationship('subdivision', 'name')
                    ->searchable(),
                SelectFilter::make('direction')
                    ->label('Направление')
                    ->relationship('directions', 'name')
                    ->searchable(),
                SelectFilter::make('position')
                    ->label('Должность')
                    ->relationship('position', 'name')
                    ->searchable(),
                SelectFilter::make('level')
                    ->label('Уровень')
                    ->relationship('level', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Подчинённые', [
                DirectSubordinatesRelationManager::class,
                FunctionalSubordinatesRelationManager::class,
            ]),
            ManagerAccessRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getRelationTableSchema($withCompany = true): array
    {
        $tableSchema = [
            TextColumn::make('full_name')
                ->label('Имя')
                ->sortable(['last_name'])
                ->searchable(['first_name', 'last_name']),
        ];

        if ($withCompany) {
            $tableSchema[] = TextColumn::make('company.name')
                ->label('Компания')
                ->sortable();
        }

        return $tableSchema;
    }
}
