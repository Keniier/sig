<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_picture',
        'first_name',
        'last_name',
        'email',
        'type_card',
        'id_card',
        'birth_date',
        'civil_status',
        'address',
        'phone_number',
        'education',
        'degree',
        'eps_entity',
        'afp_entity',
        'bank_account',
        'bank_entity',
        'type_contract',
        'license_category',
        'license_issuance',
        'license_expiration',
        'start_date_contract',
        'end_date_contract',
        'status',
        'company_id',
        'category_id',
    ];

    public function company (){
        return $this->belongsTo(Company::class);
    }

    public function category (){
        return $this->belongsTo(EmployeeCategory::class);
    }

    public static function getByCompany(){
        return Employee::where('company_id', Auth::user()->company_id);
    }
}
