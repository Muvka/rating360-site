<?php

namespace App\Filament\Resources\Rating\MatrixResource\RelationManagers;

use App\Filament\Resources\Rating\MatrixResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

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
                TextColumn::make('employee.user.full_name')
                    ->label('Сотрудник')
                    ->weight('bold'),
                TextColumn::make('employee.city.name')
                    ->label('Город'),
                TextColumn::make('employee.company.name')
                    ->label('Компания'),
                TextColumn::make('employee.directManager.user.full_name')
                    ->label('Непосредственный руководитель'),
                TextColumn::make('employee.functionalManager.user.full_name')
                    ->label('Функциональный руководитель')
                    ->placeholder('-'),
                TextColumn::make('inner_clients_count')
                    ->label('Внутренних')
                    ->counts('innerClients'),
                TextColumn::make('outer_clients_count')
                    ->label('Внешних')
                    ->counts('outerClients'),
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
}
