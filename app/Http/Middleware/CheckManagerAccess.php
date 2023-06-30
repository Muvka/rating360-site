<?php

namespace App\Http\Middleware;

use App\Models\Company\Employee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManagerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! Auth::user()?->employee?->isManager()) {
            abort(403, 'Вам запрещён доступ к этой странице');
        }

        $employeeId = $request->route('employeeId');
        $employee = Employee::find($employeeId);

        if ($employee && $employee->direct_manager_id !== Auth::user()?->employee->id) {
            abort(403, 'Вам запрещён доступ к этой странице');
        }

        return $next($request);
    }
}
