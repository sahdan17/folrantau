<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Spot extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'spot';
    protected $fillable = [
        'namaSpot',
        'lokasiSpot',
        'codeSpot',
        'minValue',
        'stopValue',
    ];
    public $timestamps = false;
}
