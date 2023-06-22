<?php

namespace App\Observers\Rating;

use App\Models\Rating\MatrixTemplate;

class MatrixTemplateObserver
{
    /**
     * Handle the MatrixTemplate "created" event.
     */
    public function created(MatrixTemplate $matrixTemplate): void
    {
        $matrixTemplate->clients()->create([
            'company_employee_id' => $matrixTemplate->employee->id,
            'type' => 'self',
        ]);

        if ($matrixTemplate->employee->directManager) {
            $matrixTemplate->clients()->create([
                'company_employee_id' => $matrixTemplate->employee->directManager->id,
                'type' => 'manager',
            ]);
        }

        if ($matrixTemplate->employee->functionalManager) {
            $matrixTemplate->clients()->create([
                'company_employee_id' => $matrixTemplate->employee->functionalManager->id,
                'type' => 'manager',
            ]);
        }
    }
}
