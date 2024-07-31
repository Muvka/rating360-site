<?php

namespace App\Filament\Resources\Company\EmployeeResource\RelationManagers;

use App\Filament\Resources\Company\EmployeeResource;
use App\Models\Company\Employee;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DirectSubordinatesRelationManager extends RelationManager
{
    protected static string $relationship = 'directSubordinates';

    protected static ?string $title = 'Непосредственные подчиненные';

    protected static ?string $label = 'Непосредственный подчиненный';

    protected static ?string $pluralLabel = 'Непосредственные подчиненные';

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
            ->columns(EmployeeResource::getRelationTableSchema())
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Employee $record): string => route('filament.admin.resources.company.employees.edit', $record->id)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->company_level_id && $ownerRecord->company_level_id !== 5;
    }
}
