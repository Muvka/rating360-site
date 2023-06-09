<?php

namespace App\Filament\Resources\Rating\MatrixResource\Pages;

use App\Filament\Resources\Rating\MatrixResource;
use App\Imports\Rating\MatrixTemplateImport;
use App\Models\Rating\Matrix;
use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListMatrices extends ListRecords
{
    protected static string $resource = MatrixResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('import')
                ->label('Импорт')
                ->color('secondary')
                ->action(function (array $data): void {
                    $this->import($data);
                })
                ->modalButton('Импортировать')
                ->form([
                    FileUpload::make('file')
                        ->label('Таблица')
                        ->disk('public')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(),
                    Fieldset::make()
                        ->label('Матрица')
                        ->visible(fn(Closure $get) => (boolean) $get('file'))
                        ->columns(1)
                        ->schema([
                            Select::make('matrix_id')
                                ->label('Выбрать')
                                ->disableLabel()
                                ->options(Matrix::all()->pluck('name', 'id'))
                                ->hidden(fn(Closure $get) => (boolean) $get('is_new'))
                                ->required(),
                            Toggle::make('is_new')
                                ->reactive()
                                ->label('Создать'),
                            TextInput::make('name')
                                ->label('Название')
                                ->placeholder('Общая матрица')
                                ->visible(fn(Closure $get) => $get('is_new'))
                                ->maxLength(128)
                                ->required(),
                        ]),
                ]),
            Actions\CreateAction::make(),
        ];
    }

    private function import(array $data): void
    {
        $matrix = null;

        if ( ! empty($data['matrix_id'])) {
            $matrix = Matrix::findOrFail($data['matrix_id']);
        } else {
            $matrix = Matrix::create([
                'name' => $data['name'],
            ]);
        }

        Excel::import(new MatrixTemplateImport($matrix), $data['file'], 'public');
    }
}
