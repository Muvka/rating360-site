<?php

namespace App\Observers\Company;

use App\Models\Company\Employee;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\MatrixTemplateClient;

class EmployeeObserver
{
    public function updated(Employee $employee): void
    {
        //        $isManager = $employee->level?->is_manager;
        //
        //        if (! $isManager) {
        //            $directSubordinates = $employee->directSubordinates;
        //
        //            if ($directSubordinates) {
        //                $directSubordinates->each(function ($subordinate) {
        //                    $subordinate->direct_manager_id = null;
        //                    $subordinate->save();
        //                });
        //            }
        //
        //            $functionalSubordinates = $employee->functionalSubordinates;
        //
        //            if ($functionalSubordinates) {
        //                $functionalSubordinates->each(function ($subordinate) {
        //                    $subordinate->functional_manager_id = null;
        //                    $subordinate->save();
        //                });
        //            }
        //
        //            $employee->managerAccess()->detach();
        //        }

        if ($employee->isDirty(['direct_manager_id', 'functional_manager_id'])) {
            MatrixTemplate::where('company_employee_id', $employee->id)
                ->each(function (MatrixTemplate $template) use ($employee) {
                    $managerClients = [];

                    $template->clients()
                        ->where('type', 'manager')
                        ->delete();

                    if ($employee->direct_manager_id) {
                        $managerClients[] = new MatrixTemplateClient([
                            'company_employee_id' => $employee->direct_manager_id,
                            'type' => 'manager',
                        ]);
                    }

                    if ($employee->functional_manager_id) {
                        $managerClients[] = new MatrixTemplateClient([
                            'company_employee_id' => $employee->functional_manager_id,
                            'type' => 'manager',
                        ]);
                    }

                    $template->clients()
                        ->saveMany($managerClients);
                });
        }
    }
}
