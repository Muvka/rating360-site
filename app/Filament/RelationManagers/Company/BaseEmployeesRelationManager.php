<?php

namespace App\Filament\RelationManagers\Company;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;

class BaseEmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $title = 'Сотрудники';

    protected static ?string $label = 'Сотрудник';

    protected static ?string $pluralLabel = 'Сотрудники';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }
}
