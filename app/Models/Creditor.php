<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Creditor extends Model
{
    use HasFactory;
    
    const UpdatableAttributes = [ 'name', 'contact_info', 'email', 'phone'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'contact_info', 'email', 'phone',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'contact_info' => 'array', 'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    
    /**
     * Get the invoices that owned by the creditor.
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice');
    }
}
