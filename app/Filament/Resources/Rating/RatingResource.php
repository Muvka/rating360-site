<?php

namespace App\Filament\Resources\Rating;

use App\Filament\Resources\Rating\RatingResource\Pages;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\Rating;
use App\Models\Rating\Result;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RyanChandler\FilamentProgressColumn\ProgressColumn;
use stdClass;

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
                Card::make()
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->label('Название')
                            ->placeholder('Оценка отдела IT')
                            ->maxLength(128)
                            ->required(),
//                        Select::make('status')
//                            ->label('Cтатус')
//                            ->options([
//                                'draft' => 'Черновик',
//                                'in progress' => 'Идёт',
//                                'paused' => 'На паузе',
//                                'closed' => 'Закрыта',
//                            ])
//                            ->disablePlaceholderSelection()
//                            ->default('draft'),
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
                ProgressColumn::make('progress')
                    ->label('Прогресс')
                    ->progress(function (Rating $record): int {
                        if ($record->status === 'closed') {
                            return 100;
                        }

                        $totalClients = 0;
                        $finishedClients = 0;
                        $matrixTemplates = $record->matrix
                            ->templates()
                            ->with('clients')
                            ->get();
                        $results = Result::with('clients')
                            ->where('rating_id', $record->id)
                            ->get();

                        foreach ($matrixTemplates as $matrixTemplate) {
                            $matrixClients = $matrixTemplate->clients
                                ->pluck('company_employee_id');
                            $resultClients = $results->firstWhere('company_employee_id', $matrixTemplate->company_employee_id)
                                ?->clients
                                ?->pluck('company_employee_id');

                            $intersect = $matrixClients->intersect($resultClients);

                            $totalClients += $matrixClients->count();
                            $finishedClients += $intersect->count();
                        }

//                        $clients = $record->matrix
//                            ->templates()
//                            ->select('id', 'company_employee_id')
//                            ->with('clients:id,rating_matrix_template_id,company_employee_id')
//                            ->get()
//                            ->reduce(function (array $carry, MatrixTemplate $matrixTemplate) use ($record) {
//                                $result = Result::select('id', 'rating_id', 'company_employee_id')
//                                    ->withCount([
//                                        'clients' => function (Builder $query) use ($matrixTemplate) {
//                                            $query->whereIn('company_employee_id', $matrixTemplate->clients->pluck('company_employee_id'));
//                                        }
//                                    ])
//                                    ->where('rating_id', $record->id)
//                                    ->where('company_employee_id', $matrixTemplate->company_employee_id)
//                                    ->first();
//
//                                $carry['total'] += $matrixTemplate->clients->count();
//                                $carry['finished'] += $result ? $result->clients_count : 0;
//
//                                return $carry;
//                            }, [
//                                'total' => 0,
//                                'finished' => 0,
//                            ]);

                        return (int) $totalClients === 0 ? $totalClients : round(($finishedClients / $totalClients) * 100);
                    })
                    ->color('success'),
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
                TextColumn::make('template.name')
                    ->label('Шаблон')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('matrix.name')
                    ->label('Матрица')
                    ->wrap()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Статус')
                    ->enum([
                        'draft' => 'Черновик',
                        'in progress' => 'Идёт',
                        'paused' => 'На паузе',
                        'closed' => 'Закрыта',
                    ])
                    ->colors([
                        'secondary' => static fn($state): bool => $state === 'draft',
                        'success' => static fn($state): bool => $state === 'in progress',
                        'primary' => static fn($state): bool => $state === 'paused',
                        'danger' => static fn($state): bool => $state === 'closed',
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
                            $record->setAttribute('status', 'in progress');
                            $record->save();
                        })
                        ->visible(fn(Rating $record): bool => in_array($record->status, ['draft', 'paused']))
                        ->color('success'),
                    Tables\Actions\Action::make('pause')
                        ->label('Остановить')
                        ->icon('heroicon-o-pause')
                        ->action(function (Rating $record) {
                            $record->setAttribute('status', 'paused');
                            $record->save();
                        })
                        ->visible(fn(Rating $record): bool => $record->status === 'in progress')
                        ->color('secondary'),
                    Tables\Actions\Action::make('close')
                        ->label('Завершить')
                        ->icon('heroicon-o-x-circle')
                        ->action(function (Rating $record) {
                            $record->setAttribute('status', 'closed');
                            $record->save();
                        })
                        ->visible(fn(Rating $record): bool => in_array($record->status, ['in progress', 'paused']))
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
