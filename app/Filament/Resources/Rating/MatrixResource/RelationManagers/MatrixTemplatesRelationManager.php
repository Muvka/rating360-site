<?php

namespace App\Filament\Resources\Rating\MatrixResource\RelationManagers;

use App\Filament\Resources\Rating\MatrixResource;
use App\Models\Company\Employee;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\Rules\Unique;

class MatrixTemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'templates';

    protected static ?string $label = 'Шаблон матрицы';

    protected static ?string $pluralLabel = 'Шаблоны матрицы';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Select::make('company_employee_id')
                    ->getSearchResultsUsing(
                        fn(string $search) => Employee::where('last_name', 'like', "%{$search}%")
                            ->limit(20)
                            ->get()
                            ->pluck('full_name', 'id'))
                    ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                        ->full_name)
                    ->label('Сотрудник')
                    ->searchable()
                    ->reactive()
                    ->unique(callback: function (Unique $rule, callable $get, ?Model $record, RelationManager $livewire) {
                        return $rule
                            ->where('company_employee_id', $get('company_employee_id'))
                            ->where('rating_matrix_id', $livewire->ownerRecord->id);
                    }, ignoreRecord: true)
                    ->required(),
                Card::make()
                    ->visible(fn(Closure $get): bool => (bool) $get('company_employee_id'))
                    ->columns(3)
                    ->schema([
                        Placeholder::make('city')
                            ->label('Город')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->city
                                ?->name),
                        Placeholder::make('company')
                            ->label('Компания')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->company
                                ?->name),
                        Placeholder::make('division')
                            ->label('Отдел')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->division
                                ?->name),
                        Placeholder::make('subdivision')
                            ->label('Подразделение')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->subdivision
                                ?->name),
                        Placeholder::make('directions')
                            ->label('Направления')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->directions
                                ->pluck('name')
                                ->join(', ')),
                        Placeholder::make('position')
                            ->label('Должность')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->position
                                ?->name),
                        Placeholder::make('level')
                            ->label('Уровень сотрудника')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->level
                                ?->name),
                        Placeholder::make('direct_manager')
                            ->label('Непосредственный руководитель')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->directManager
                                ?->full_name),
                        Placeholder::make('functional_manager')
                            ->label('Функциональный руководитель')
                            ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                                ?->functionalManager
                                ?->full_name),
                    ]),
                TableRepeater::make('clients')
                    ->relationship('editableClients')
                    ->label('Клиенты')
                    ->headers(['Сотрудник', 'Клиент'])
                    ->createItemButtonLabel('Добавить клиента')
                    ->emptyLabel('Нет клиентов')
                    ->columnWidths(['type' => '20%'])
                    ->schema([
                        Select::make('company_employee_id')
                            ->getSearchResultsUsing(
                                fn(string $search) => Employee::where('last_name', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->pluck('full_name', 'id'))
                            ->getOptionLabelUsing(fn($value): ?string => Employee::find($value)
                                ->full_name)
                            ->label('Сотрудник')
                            ->disableLabel()
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Клиент')
                            ->options([
                                'manager' => 'Руководитель',
                                'inner' => 'Внутренний',
                                'outer' => 'Внешний',
                            ])
                            ->disablePlaceholderSelection()
                            ->default('inner')
                            ->disableLabel(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Сотрудник')
                    ->weight('bold')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('clients_without_self_count')
                    ->label('Всего клиентов')
                    ->counts('clientsWithoutSelf')
                    ->sortable()
                    ->color(fn(Model $record) => $record->clients->count() >= 8 ? 'success' : 'danger'),
                TextColumn::make('inner_clients_count')
                    ->label('Внутренних')
                    ->counts('innerClients')
                    ->sortable(),
                TextColumn::make('outer_clients_count')
                    ->label('Внешних')
                    ->counts('outerClients')
                    ->sortable(),
                TextColumn::make('employee.city.name')
                    ->label('Город'),
                TextColumn::make('employee.company.name')
                    ->label('Компания'),
                TextColumn::make('employee.directManager.full_name')
                    ->label('Непосредственный руководитель'),
                TextColumn::make('employee.functionalManager.full_name')
                    ->label('Функциональный руководитель')
                    ->placeholder('-'),
            ])
            ->defaultSort('sort')
            ->reorderable()
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('4xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = 999999;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth('4xl'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return parent::getTableQuery()->with('clientsWithoutSelf');
    }
}
