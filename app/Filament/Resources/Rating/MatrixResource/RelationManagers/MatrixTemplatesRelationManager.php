<?php

namespace App\Filament\Resources\Rating\MatrixResource\RelationManagers;

use App\Filament\Resources\Rating\MatrixResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MatrixTemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'templates';

    protected static ?string $label = 'Шаблон матрицы';

    protected static ?string $pluralLabel = 'Шаблоны матрицы';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema(MatrixResource::getTemplateFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Сотрудник')
                    ->weight('bold')
                    ->sortable(),
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
