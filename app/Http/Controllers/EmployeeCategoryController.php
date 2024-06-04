<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'superadmin') {
            // 
        } else {
            $categories  = EmployeeCategory::where('company_id', $user->company_id)->get();
        }

        return response()->json([
            'status' => true,
            'categories ' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeCategory $employeeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeCategory $employeeCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeCategory $employeeCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeCategory $employeeCategory)
    {
        //
    }
}
