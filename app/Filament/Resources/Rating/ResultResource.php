<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\ResultResource\Pages;
use App\Models\Rating\Result;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use stdClass;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $label = 'Статистика';

    protected static ?string $pluralLabel = 'Статистика';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                TextColumn::make('employee.user.full_name')
                    ->label('Сотрудник')
                    ->sortable(),
                TextColumn::make('rating.launched_at')
                    ->label('Дата запуска')
                    ->date('d.m.Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('rating.name')
                    ->label('Название')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResults::route('/'),
//            'create' => Pages\CreateResult::route('/create'),
            'view' => Pages\ViewResult::route('/{record}'),
//            'edit' => Pages\EditResult::route('/{record}/edit'),
        ];
    }
}