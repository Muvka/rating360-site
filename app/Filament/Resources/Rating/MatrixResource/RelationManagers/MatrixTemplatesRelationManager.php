<?php

namespace App\Filament\Resources\Rating\MatrixResource\RelationManagers;

use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Shared\City;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class MatrixTemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'templates';

    protected static ?string $title = 'Шаблоны матрицы';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Select::make('company_employee_id')
                    ->getSearchResultsUsing(
                        fn (string $search) => Employee::where('last_name', 'like', "%$search%")
                            ->limit(20)
                            ->get()
                            ->pluck('full_name', 'id'))
                    ->getOptionLabelUsing(fn ($value): ?string => Employee::find($value)
                        ->full_name)
                    ->label('Сотрудник')
                    ->searchable()
                    ->reactive()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, callable $get, ?Model $record, RelationManager $livewire) {
                        return $rule
                            ->where('company_employee_id', $get('company_employee_id'))
                            ->where('rating_matrix_id', $livewire->ownerRecord->id);
                    })
                    ->required(),
                Section::make()
                    ->visible(fn (Get $get): bool => (bool) $get('company_employee_id'))
                    ->columns(3)
                    ->schema([
                        Placeholder::make('city')
                            ->label('Город')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->city
                                ?->name),
                        Placeholder::make('company')
                            ->label('Компания')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->company
                                ?->name),
                        Placeholder::make('division')
                            ->label('Отдел')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->division
                                ?->name),
                        Placeholder::make('subdivision')
                            ->label('Подразделение')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->subdivision
                                ?->name),
                        Placeholder::make('directions')
                            ->label('Направления')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->directions
                                ->pluck('name')
                                ->join(', ')),
                        Placeholder::make('position')
                            ->label('Должность')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->position
                                ?->name),
                        Placeholder::make('level')
                            ->label('Уровень сотрудника')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->level
                                ?->name),
                        Placeholder::make('direct_manager')
                            ->label('Непосредственный руководитель')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->directManager
                                ?->full_name),
                        Placeholder::make('functional_manager')
                            ->label('Функциональный руководитель')
                            ->content(fn (Get $get): ?string => Employee::find($get('company_employee_id'))
                                ?->functionalManager
                                ?->full_name),
                    ]),
                TableRepeater::make('clients')
                    ->relationship('editableClients')
                    ->label('Клиенты')
                    ->headers([
                        Header::make('company_employee_id')
                            ->label('Сотрудник'),
                        Header::make('type')
                            ->label('Клиент')
                            ->width('20%'),
                    ])
                    ->addActionLabel('Добавить клиента')
                    ->emptyLabel('Нет клиентов')
                    ->schema([
                        Select::make('company_employee_id')
                            ->getSearchResultsUsing(
                                fn (string $search) => Employee::where('last_name', 'like', "%$search%")
                                    ->limit(20)
                                    ->get()
                                    ->pluck('full_name', 'id'))
                            ->getOptionLabelUsing(fn ($value): ?string => Employee::find($value)
                                ->full_name)
                            ->label('Сотрудник')
                            ->hiddenLabel()
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Клиент')
                            ->options([
                                'inner' => 'Внутренний',
                                'outer' => 'Внешний',
                            ])
                            ->selectablePlaceholder(false)
                            ->default('inner')
                            ->hiddenLabel(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['clientsWithoutSelf', 'employee'])
                ->whereHas('employee', fn (Builder $query) => $query->whereNull('deleted_at'))
            )
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
                    ->color(fn (Model $record) => $record->clients->count() >= 8 ? 'success' : 'danger'),
                TextColumn::make('inner_clients_count')
                    ->label('Внутренних')
                    ->counts('innerClients')
                    ->sortable(),
                TextColumn::make('outer_clients_count')
                    ->label('Внешних')
                    ->counts('outerClients')
                    ->sortable(),
                TextColumn::make('employee.city.name')
                    ->label('Город')
                    ->sortable(query: function (EloquentBuilder $query, string $direction): EloquentBuilder {
                        return $query
                            ->leftJoin('company_employees', 'company_employees.id', '=', 'rating_matrix_templates.company_employee_id')
                            ->leftJoin('cities', 'cities.id', '=', 'company_employees.city_id')
                            ->orderBy('cities.name', $direction);
                    }),
                TextColumn::make('employee.company.name')
                    ->label('Компания')
                    ->sortable(query: function (EloquentBuilder $query, string $direction): EloquentBuilder {
                        return $query
                            ->leftJoin('company_employees', 'company_employees.id', '=', 'rating_matrix_templates.company_employee_id')
                            ->leftJoin('companies', 'companies.id', '=', 'company_employees.company_id')
                            ->orderBy('companies.name', $direction);
                    }),
                TextColumn::make('employee.directManager.full_name')
                    ->label('Непосредственный руководитель')
                    ->sortable(query: function (EloquentBuilder $query, string $direction): EloquentBuilder {
                        return $query
                            ->leftJoin('company_employees as ce1', 'ce1.id', '=', 'rating_matrix_templates.company_employee_id')
                            ->leftJoin('company_employees as ce2', 'ce2.id', '=', 'ce1.direct_manager_id')
                            ->orderBy('ce2.last_name', $direction);
                    }),
                TextColumn::make('employee.functionalManager.full_name')
                    ->label('Функциональный руководитель')
                    ->placeholder('-')
                    ->sortable(query: function (EloquentBuilder $query, string $direction): EloquentBuilder {
                        return $query
                            ->leftJoin('company_employees as ce1', 'ce1.id', '=', 'rating_matrix_templates.company_employee_id')
                            ->leftJoin('company_employees as ce2', 'ce2.id', '=', 'ce1.functional_manager_id')
                            ->orderBy('ce2.last_name', $direction);
                    }),
            ])
            ->defaultSort('sort')
            ->reorderable()
            ->filters([
                SelectFilter::make('city_id')
                    ->options(fn () => City::get()->pluck('name', 'id')->toArray())
                    ->label('Город')
                    ->query(function (EloquentBuilder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas(
                                'employee.city',
                                fn (EloquentBuilder $query) => $query->where('id', '=', (int) $data['value'])
                            );
                        }
                    }),
                SelectFilter::make('company_id')
                    ->options(fn () => Company::get()->pluck('name', 'id')->toArray())
                    ->label('Компания')
                    ->query(function (EloquentBuilder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas(
                                'employee.company',
                                fn (EloquentBuilder $query) => $query->where('id', '=', (int) $data['value'])
                            );
                        }
                    }),
                SelectFilter::make('direct_manager_id')
                    ->options(fn () => Employee::get()->pluck('full_name', 'id')->toArray())
                    ->label('Непосредственный руководитель')
                    ->searchable()
                    ->query(function (EloquentBuilder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas(
                                'employee',
                                fn (EloquentBuilder $query) => $query->where('direct_manager_id', '=', (int) $data['value'])
                            );
                        }
                    }),
                SelectFilter::make('functional_manager_id')
                    ->options(fn () => Employee::get()->pluck('full_name', 'id')->toArray())
                    ->label('Функциональный руководитель')
                    ->searchable()
                    ->query(function (EloquentBuilder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas(
                                'employee',
                                fn (EloquentBuilder $query) => $query->where('functional_manager_id', '=', (int) $data['value'])
                            );
                        }
                    }),
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
                    Tables\Actions\Action::make('edit_client')
                        ->label('Изменить сотрудника')
                        ->icon('heroicon-s-pencil')
                        ->url(fn (Model $record): string => EmployeeResource::getUrl('edit', [$record->employee])),
                    Tables\Actions\EditAction::make()
                        ->label('Изменить шаблон')
                        ->modalWidth('4xl'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
