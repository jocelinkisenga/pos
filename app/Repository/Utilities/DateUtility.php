<?php

declare(strict_types=1);

namespace App\Repository\Utilities;

use Illuminate\Support\Carbon;

class DateUtility {
    public function newDate(string $newdate){
       return Carbon::createFromFormat('Y-m-d', $newdate)->format('Y-m');
    }

    public function oldDate($Adv){

    }
}
