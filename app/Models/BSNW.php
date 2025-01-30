<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BSNW extends Model
{
    use HasFactory;

    protected $table = 'bsnw';
    protected $fillable = [
        'date',
        'time',
        'line',
        'percent',
        'tank',
        'structure',
    ];
    public $timestamps = false;
}
