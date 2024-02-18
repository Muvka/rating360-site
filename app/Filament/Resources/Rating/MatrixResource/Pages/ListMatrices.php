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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Импорт')
                ->color('gray')
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
                        ->visible(fn (\Filament\Forms\Get $get) => (bool) $get('file'))
                        ->columns(1)
                        ->schema([
                            Select::make('matrix_id')
                                ->label('Выбрать')
                                ->hiddenLabel()
                                ->options(Matrix::all()->pluck('name', 'id'))
                                ->hidden(fn (\Filament\Forms\Get $get) => (bool) $get('create'))
                                ->required(),
                            Toggle::make('create')
                                ->reactive()
                                ->label('Создать новую матрицу'),
                            TextInput::make('name')
                                ->label('Название')
                                ->placeholder('Общая матрица')
                                ->visible(fn (\Filament\Forms\Get $get) => $get('create'))
                                ->maxLength(128)
                                ->required(),
                        ]),
                ]),
            Actions\CreateAction::make(),
        ];
    }

    private function import(array $data): void
    {
        if (! empty($data['matrix_id'])) {
            $matrix = Matrix::findOrFail($data['matrix_id']);
        } else {
            $matrix = Matrix::create([
                'name' => $data['name'],
            ]);
        }

        Excel::import(new MatrixTemplateImport($matrix), $data['file'], 'public');
    }
}
