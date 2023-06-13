<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\MatrixResource\Pages;
use App\Filament\Resources\Rating\MatrixResource\RelationManagers\MatrixTemplatesRelationManager;
use App\Models\Company\Employee;
use App\Models\Rating\Matrix;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use stdClass;

class MatrixResource extends Resource
{
    protected static ?string $model = Matrix::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-view-grid';

    protected static ?string $label = 'Матрица';

    protected static ?string $pluralLabel = 'Матрицы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema(static::getGeneralFormSchema())
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
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Название'),
            ])
            ->filters([
                //
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
            MatrixTemplatesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatrices::route('/'),
            'create' => Pages\CreateMatrix::route('/create'),
            'edit' => Pages\EditMatrix::route('/{record}/edit'),
        ];
    }

    public static function getGeneralFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Название')
                ->placeholder('Общая матрица')
                ->maxLength(128)
                ->required(),
        ];
    }

    public static function getTemplateFormSchema(): array
    {
        return [
            Select::make('company_employee_id')
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
                ->label('Сотрудник')
                ->searchable()
                ->reactive()
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
                            ?->user
                            ->full_name),
                    Placeholder::make('functional_manager')
                        ->label('Функциональный руководитель')
                        ->content(fn(Closure $get): ?string => Employee::find($get('company_employee_id'))
                            ?->functionalManager
                            ?->user
                            ->full_name),
                ]),
            TableRepeater::make('clients')
                ->relationship('clients')
                ->label('Клиенты')
                ->headers(['Клиент', 'Внешний'])
                ->createItemButtonLabel('Добавить клиента')
                ->emptyLabel('Нет клиентов')
                ->columnWidths(['outer' => '100px'])
                ->schema([
                    Select::make('company_employee_id')
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
                        ->label('Клиент')
                        ->disableLabel()
                        ->searchable()
                        ->required(),
                    Toggle::make('outer')
                        ->label('Внешний')
                        ->disableLabel(),
                ])
        ];
    }
}
