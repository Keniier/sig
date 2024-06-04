<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
