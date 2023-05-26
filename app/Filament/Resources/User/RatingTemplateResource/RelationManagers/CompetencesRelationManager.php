<?php

namespace App\Filament\Resources\User\RatingTemplateResource\RelationManagers;

use App\Filament\Resources\User\RatingTemplateResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CompetencesRelationManager extends RelationManager
{
    protected static string $relationship = 'competences';

    protected static ?string $label = 'Компетенция';

    protected static ?string $pluralLabel = 'Компетенции';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema(RatingTemplateResource::getCompetenceFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название'),
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
