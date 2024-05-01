<?php
declare(strict_types=1);
namespace App\Repository\Services;

use App\Models\AdvanceSalary;

class AdvanceSalaryService {

    public function advanceSalaryByname(string $search) {


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


    public function advancedSalary() {

    }

}
