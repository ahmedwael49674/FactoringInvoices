<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class UpdateInvoice extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'            => "required",
            "total_amount"  => "filled",
            "currency_id"   => ["filled", Rule::in(array_keys(Currency::AvailableCurrencies))],
            "due_date"      => "filled|date",
        ];
    }
}
