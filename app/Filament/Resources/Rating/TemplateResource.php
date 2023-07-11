<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\TemplateResource\RelationManagers\CompetencesRelationManager;
use App\Filament\Resources\Rating\TemplateResource\Pages;
use App\Models\Rating\Template;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationIcon = 'heroicon-o-template';

    protected static ?string $label = 'Шаблон оценки';

    protected static ?string $pluralLabel = 'Шаблоны оценок';

    protected static ?string $navigationLabel = 'Шаблоны оценок';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Общий шаблон')
                            ->maxLength(128)
                            ->required(),
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
            CompetencesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
