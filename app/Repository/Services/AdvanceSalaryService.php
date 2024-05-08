<?php
declare(strict_types=1);
namespace App\Repository\Services;

use App\Models\AdvanceSalary;

class AdvanceSalaryService {

    public function advanceSalaryByIdAndDate( $advanceSalaryId, string $getYearMonth) {

    return AdvanceSalary::where('employee_id', $advanceSalaryId)
            ->whereDate('date', 'LIKE',  $getYearMonth . '%')
            ->get();
    }

    public function advanceSalaryById(int $advancedId){

    }

    public function advancedSalaryWithEmployee(string $searchWords, $row) {
     return   AdvanceSalary::with(['employee'])
                ->orderByDesc('date')
                ->filter($searchWords)
                ->sortable()
                ->paginate($row)
                ->appends(request()->query());
    }

    public function advanceSalaryCreate($data) : void {
        AdvanceSalary::create(
[
        'employee_id' => $data->employee_id,
        'date' => $data->date,
        'advance_salary' => $data->advance_salary]
        );
    }

}
