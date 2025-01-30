<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UniqueDates extends Model
{
    protected $table = 'unique_dates'; // Nama view
    public $timestamps = false; // View biasanya tidak memiliki kolom timestamp
}
