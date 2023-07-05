<?php

namespace App\Filament\Resources\Company;

use App\Filament\Resources\Company\EmployeeResource\Pages;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\DirectSubordinatesRelationManager;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\FunctionalSubordinatesRelationManager;
use App\Filament\Resources\Company\EmployeeResource\RelationManagers\ManagerAccessRelationManager;
use App\Filament\Resources\Shared\UserResource;
use App\Models\Company\Employee;

/**/

use Closure;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
                    ->relationship('user')
                    ->columns(3)
                    ->schema(UserResource::getGeneralFormSchema(false)),
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
                            ->searchable()
                            ->required(),
                        Select::make('company_subdivision_id')
                            ->label('Подразделение')
                            ->relationship('subdivision', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('directions')
                            ->relationship('directions', 'name')
                            ->label('Направления')
                            ->multiple(),
                        Select::make('company_employee_position_id')
                            ->label('Должность')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('company_employee_level_id')
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
                                fn(string $search) => Employee::with('user')
                                    ->whereHas('user', function (Builder $query) use ($search) {
                                        $query->where('last_name', 'like', "%{$search}%");
                                    })
                                    ->limit(20)
                                    ->get()
                                    ->pluck('user.full_name', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                                ?->user
                                ->full_name)
                            ->searchable()
                            ->required(),
                        Select::make('functional_manager_id')
                            ->label('Функциональный')
                            ->getSearchResultsUsing(
                                fn(string $search) => Employee::with('user')
                                    ->whereHas('user', function (Builder $query) use ($search) {
                                        $query->where('last_name', 'like', "%{$search}%");
                                    })
                                    ->limit(20)
                                    ->get()
                                    ->pluck('user.full_name', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                                ?->user
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
                TextColumn::make('user.full_name')
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
            TextColumn::make('user.fullName')
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
