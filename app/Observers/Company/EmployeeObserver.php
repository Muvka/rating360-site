<?php

namespace App\Observers\Company;

use App\Models\Company\Employee;

class EmployeeObserver
{
    public function updated(Employee $employee): void
    {
        $levelId = $employee->level?->id;

        if (!$levelId || (int)$levelId === 5) {
            $directSubordinates = $employee->directSubordinates;

            if ($directSubordinates) {
                $directSubordinates->each(function ($subordinate) {
                    $subordinate->direct_manager_id = null;
                    $subordinate->save();
                });
            }

            $functionalSubordinates = $employee->functionalSubordinates;

            if ($functionalSubordinates) {
                $functionalSubordinates->each(function ($subordinate) {
                    $subordinate->functional_manager_id = null;
                    $subordinate->save();
                });
            }

            $employee->managerAccess()->detach();
        }
    }
}
