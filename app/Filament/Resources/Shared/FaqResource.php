<?php

namespace App\Filament\Resources\Shared;

use App\Filament\Resources\Shared\FaqResource\Pages;
use App\Models\Shared\Faq;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationGroup = 'Общее';

    protected static ?int $navigationSort = 70;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $label = 'Ответ на вопрос';

    protected static ?string $pluralLabel = 'Ответы на вопросы';

    protected static ?string $navigationLabel = 'Ответы на вопросы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('question')
                            ->label('Вопрос')
                            ->maxLength(255)
                            ->placeholder('Текст вопроса')
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('answer')
                            ->label('Ответ')
                            ->placeholder('Текст ответа')
                            ->rows(5)
                            ->maxLength(65535)
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_published')
                            ->label('Опубликован')
                            ->inline(false),
                        TextInput::make('sort')
                            ->label('Сортировка')
                            ->placeholder('1')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Номер')->rowIndex(),
                TextColumn::make('question')
                    ->label('Вопрос')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort')
                    ->label('Сортировка')
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label('Опубликован')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
