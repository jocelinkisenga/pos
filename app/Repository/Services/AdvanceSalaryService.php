<?php
declare(strict_types=1);
namespace App\Repository\Services;

class AdvanceSalaryService {

    public function advanceSalaryByname(string $search) {


    }

    public function advanceSalaryById(int $advancedId){

    }

    public function advancedSalaryWithEmploy() {
     return   AdvanceSalary::with(['employee'])
                ->orderByDesc('date')
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()
    }


    public function advancedSalary() {

    }

}
