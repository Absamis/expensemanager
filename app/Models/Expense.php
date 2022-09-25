<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $primaryKey = "exp_id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = ["exp_id", "userid", "merchant", "date", "amount", "remark", "receipt"];

    public function user()
    {
        $this->belongsTo(User::class, "userid");
    }
}
