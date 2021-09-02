<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    
    const ProtectedAttributes     = ['paid_date', 'open_date', 'status']; //user can't set or update these attributes
    const NotUpdatableAttributes  = ['paid_date', 'open_date', 'status', 'debtor_id']; //user update these attributes
    const Statuses                = ['initialize', 'open', 'paid'];
    const Initialize              = 'initialize';
    const Open                    = 'open';
    const Paid                    = 'paid';
    const TotalDebtLimit          = 20000;


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_amount' => 'decimal:2', 'created_at' => 'datetime:Y-m-d H:i:s', 'updated_at' => 'datetime:Y-m-d H:i:s',
        'due_date' => 'datetime:Y-m-d', 'paid_date' => 'datetime:Y-m-d H:i:s', 'open_date' => 'datetime:Y-m-d H:i:s',
        'debtor_id' => 'integer', 'creditor_id' => 'integer', 'currency_id' => 'integer',
    ];
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_amount', 'debtor_id', 'creditor_id', 'currency_id', 'due_date'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_paid', 'is_open'];
        
    /**
     * Model's boot function
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function (Invoice $invoice) {
            $invoice->status = $invoice->status ?? self::Initialize;
        });
    }

    /**
     * Get the debtor that belongs to Invoice.
     */
    public function debtor()
    {
        return $this->belongsTo('App\Models\Debtor');
    }
        
    /**
     * Get the Creditor that belongs to Invoice.
     */
    public function creditor()
    {
        return $this->belongsTo('App\Models\Creditor');
    }
        
    /**
     * Get the Currency that belongs to Invoice.
     */
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }
        
    /**
     * Get the Item that owned by Invoice.
     */
    public function items()
    {
        return $this->hasMany('App\Models\InvoiceItem');
    }

        
    /***************** Accessors ******************** */
    
    /**
     * check if the given invoice is Initialized
     *
     * @return bool
     */
    public function getIsInitializeAttribute($value):bool
    {
        return $this->attributes['status'] == self::Initialize;
    }
    
    /**
     * check if the given invoice is paid
     *
     * @return bool
     */
    public function getIsPaidAttribute($value):bool
    {
        return $this->attributes['status'] == self::Paid;
    }
    
    /**
     * check if the given invoice is open
     *
     * @return bool
     */
    public function getIsOpenAttribute($value):bool
    {
        return $this->attributes['status'] == self::Open;
    }
    
    /***************** scopes ******************** */

    /**
     * Scope a query to get only invoices with given debtor id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDebtor(Builder $query, int $id):Builder
    {
        return $query->whereDebtorId($id);
    }

    /**
     * Scope a query to get only invoices with given creditor id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreditor(Builder $query, int $id):Builder
    {
        return $query->whereCreditorId($id);
    }
}
