<?php

namespace App\Filament\Resources\Rating\EmployeeResource\Pages;

use App\Filament\Resources\Rating\EmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function form(Form $form): Form
    {
        return $form->schema([]);
    }
}
