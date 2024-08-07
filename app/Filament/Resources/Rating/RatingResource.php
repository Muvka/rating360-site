<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\RatingResource\Pages;
use App\Models\Rating\Rating;
use App\Services\Rating\ProgressService;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $navigationGroup = 'Оценка';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $label = 'Оценка';

    protected static ?string $pluralLabel = 'Оценки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Оценка отдела IT')
                            ->maxLength(128)
                            ->required(),
                        Select::make('rating_template_id')
                            ->relationship('template', 'name')
                            ->label('Шаблон')
                            ->required(),
                        Select::make('rating_matrix_id')
                            ->relationship('matrix', 'name')
                            ->label('Матрица')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Номер')->rowIndex(),
                ProgressColumn::make('progress')
                    ->label('Прогресс')
                    ->progress(function (Rating $record): int {
                        return (new ProgressService())->getProgress($record);
                    }),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('launched_at')
                    ->label('Дата запуска')
                    ->date('d.m.Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('templateWithTrashed.name')
                    ->label('Шаблон')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('matrixWithTrashed.name')
                    ->label('Матрица')
                    ->wrap()
                    ->sortable(),
                ToggleColumn::make('show_results_before_completion')
                    ->label('Результаты до завершения'),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
//                    ->enum([
//                        'draft' => 'Черновик',
//                        'in progress' => 'Идёт',
//                        'paused' => 'На паузе',
//                        'closed' => 'Закрыта',
//                    ])
                    ->colors([
                        'secondary' => static fn ($state): bool => $state === 'draft',
                        'success' => static fn ($state): bool => $state === 'in progress',
                        'primary' => static fn ($state): bool => $state === 'paused',
                        'danger' => static fn ($state): bool => $state === 'closed',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('launch')
                        ->label('Запустить')
                        ->icon('heroicon-o-play')
                        ->action(function (Rating $record) {
                            if (! $record->template || ! $record->matrix) {
                                Notification::make()
                                    ->danger()
                                    ->title('Невозможно запустить оценку')
                                    ->body('Шаблон или матрица не выбраны')
                                    ->send();

                                return;
                            }

                            $record->setAttribute('status', 'in progress');
                            $record->save();
                        })
                        ->visible(fn (Rating $record): bool => in_array($record->status, ['draft', 'paused']))
                        ->color('success'),
                    Tables\Actions\Action::make('pause')
                        ->label('Остановить')
                        ->icon('heroicon-o-pause')
                        ->action(function (Rating $record) {
                            $record->setAttribute('status', 'paused');
                            $record->save();
                        })
                        ->visible(fn (Rating $record): bool => $record->status === 'in progress')
                        ->color('gray'),
                    Tables\Actions\Action::make('close')
                        ->label('Завершить')
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Rating $record) {
                            $record->setAttribute('status', 'closed');
                            $record->save();
                        })
                        ->visible(fn (Rating $record): bool => in_array($record->status, ['in progress', 'paused']))
                        ->color('danger')
                        ->requiresConfirmation(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return $record->status !== 'closed';
    }

    public static function canDelete(Model $record): bool
    {
        return $record->status !== 'closed';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatings::route('/'),
            'create' => Pages\CreateRating::route('/create'),
            'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}
