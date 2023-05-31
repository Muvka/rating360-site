<?php

namespace App\Filament\Resources\User\RatingMatrixResource\RelationManagers;

use App\Filament\Resources\User\RatingMatrixResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class TemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'templates';

    protected static ?string $label = 'Шаблон матрицы';

    protected static ?string $pluralLabel = 'Шаблоны матрицы';

    protected static ?string $recordTitleAttribute = 'employee.name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema(RatingMatrixResource::getTemplateFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->wrap()
                    ->label('Сотрудник'),
                TextColumn::make('city')
                    ->label('Город'),
                TextColumn::make('company')
                    ->label('Компания'),
            ])
            ->defaultSort('sort')
            ->reorderable()
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('6xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = 999999;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('6xl'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
