<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
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
            $employees  = Employee::where('company_id', $user->company_id)->get();
        }

        return response()->json([
            'status' => true,
            'employees ' => $employees
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $path;
        }

        $employee = Employee::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Empleado creados exitosamente',
            'employee' => $employee,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee = Employee::find($employee);
        return response()->json([
            'status' => true, 
            'employee' => $employee 
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if ($request->hasFile('profile_picture')) {
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }

            $path = $request->file('profile_picture')->store('company_icons', 'public');
            $validatedData['profile_picture'] = $path;
        }

        $employee->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Empresa y usuario actualizados exitosamente',
            'employee' => $employee->fresh(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $delete = $employee->delete();

        if ($delete) {
            return response()->json([
                'status' => true,
                'message' => 'Empleado eliminado'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Company not found.',
        ], 404);
    }
}
