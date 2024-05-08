<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvanceSalaryRequest;
use App\Repository\Services\AdvanceSalaryService;
use Illuminate\Support\Facades\Redirect;

class AdvanceSalaryController extends Controller
{
    public function __construct(public AdvanceSalaryService $advanceSalaryService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        // if(request('search')){
        //     Employee::firstWhere('name', request('search'));
        // }

        return view('advance-salary.index', [
            'advance_salaries' => $this->advanceSalaryService
                ->advancedSalaryWithEmployee(request(["search"]), $row),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('advance-salary.create', [
            'employees' => Employee::all()->sortBy('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvanceSalaryRequest $request)
    {

        if ($request->date) {
            // format date only shows the year and month
            $getYm = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m');
        }

        if ($this->advanceSalaryService->advanceSalaryByIdAndDate($request->employee_id, $getYm)->isEmpty()) {
            $this->advanceSalaryService->advanceSalaryCreate($request);

            return Redirect::route('advance-salary.create')->with('success', 'Advance Salary Paid Successfully!');
        } else {
            return Redirect::route('advance-salary.create')->with('warning', 'Advance Salary Already Paid!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AdvanceSalary $advanceSalary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdvanceSalary $advanceSalary)
    {
        return view('advance-salary.edit', [
            'employees' => Employee::all()->sortBy('name'),
            'advance_salary' => $advanceSalary,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdvanceSalaryRequest $request, AdvanceSalary $advanceSalary)
    {

        // format date only shows the YM (year and month)
        $newYm = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m');
        $oldYm = Carbon::createFromFormat('Y-m-d', $advanceSalary->date)->format('Y-m');

        $advanced = AdvanceSalary::where('employee_id', $request->id)
            ->whereDate('date', 'LIKE',  $newYm . '%')
            ->first();

        if (!$advanced && $newYm == $oldYm) {
            $validatedData = $request->validate($rules);
            AdvanceSalary::where('id', $advanceSalary->id)->update($validatedData);

            return Redirect::route('advance-salary.edit', $advanceSalary->id)->with('success', 'Advance Salary Updated Successfully!');
        } else {
            return Redirect::route('advance-salary.edit', $advanceSalary->id)->with('warning', 'Advance Salary Already Paid!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceSalary $advanceSalary)
    {
        AdvanceSalary::destroy($advanceSalary->id);

        return Redirect::route('advance-salary.index')->with('success', 'Advance Salary has been deleted!');
    }
}
