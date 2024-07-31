<?php

namespace App\Filament\Resources\Rating\TemplateResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CompetencesRelationManager extends RelationManager
{
    protected static string $relationship = 'competences';

    protected static ?string $title = 'Компетенции';

    protected static ?string $label = 'Компетенция';

    protected static ?string $pluralLabel = 'Компетенции';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Компетенция')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
