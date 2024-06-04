<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * View to listing of the resource.
     */
    public function view()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $companies  = Company::all();
        if ($user && $user->role === 'superadmin') {
        } else {
            $companies  = $user->company;
        }

        return response()->json([
            'status' => true,
            'companies ' => $companies
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'path_icon' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email', // Validación única para el email
            'password' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Iniciar transacción para asegurar atomicidad
        DB::beginTransaction();

        try {
            if ($request->hasFile('path_icon')) {
                $path = $request->file('path_icon')->store('company_icons', 'public');
                $validatedData['path_icon'] = $path;
            }

            $company = Company::create($validatedData);

            $user_company = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'company_id' => $company->id,
            ]);

            // Confirmar transacción si ambas creaciones son exitosas
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Empresa y usuario creados exitosamente',
                'company' => $company,
                'user' => $user_company,
            ], 200);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error al crear la empresa y/o el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

        /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company = Company::find($company);
        return response()->json([
            'status' => true, 
            'company' => $company 
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $company)
    {
        $company = Company::findOrFail($company);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $company->user->id, // Unique validation excluding current user
            'city' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'path_icon' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Iniciar transacción para asegurar atomicidad
        DB::beginTransaction();

        try {
            if ($request->hasFile('path_icon')) {
                if ($company->path_icon) {
                    Storage::disk('public')->delete($company->path_icon);
                }

                $path = $request->file('path_icon')->store('company_icons', 'public');
                $validatedData['path_icon'] = $path;
            }

            $company->update($validatedData);

            $company->user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Confirmar transacción si ambas creaciones son exitosas
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Empresa y usuario actualizados exitosamente',
                'company' => $company->fresh(),
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error al actualizar la empresa y/o el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $deleted = $company->delete();

        if ($deleted) {
            return response()->json([
                'status' => true,
                'message' => 'Company deleted successfully.',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Company not found.',
        ], 404);
    }
}
