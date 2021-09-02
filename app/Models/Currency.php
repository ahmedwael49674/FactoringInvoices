<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    const USD = 1;
    // const EUR = 2;
    const AvailableCurrencies =  [
        1 => ['name' =>'US Dollar' , 'code' => 'usd'],
        // 2 => ['name' =>'Euro'      , 'code' => 'eur'],
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code'
    ];
}
