<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalaryReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasEmployee()
    {
        return $this->hasOne(User::class, 'id', 'employee_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
    public function bank()
    {
        return $this->belongsTo(BankAccount::class ,'bank_account_id' ,'id');
    }
    public function department() {
        return $this->belongsTo(Department::class);
    }
}
