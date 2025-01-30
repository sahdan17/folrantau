<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class HistOnOff extends Model
{
    use HasFactory;

    protected $table = 'histonoff';
    protected $fillable = [
        'idSpot',
        'ket',
        'timestamp',
        'count'
    ];
    public $timestamps = false;
}