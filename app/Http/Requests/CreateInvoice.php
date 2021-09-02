<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class CreateInvoice extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "total_amount"  => "required",
            "debtor_id"     => "required|exists:debtors,id",
            "creditor_id"   => "required|exists:creditors,id",
            "currency_id"   => ["required", Rule::in(array_keys(Currency::AvailableCurrencies))],
            "due_date"      => "required|date",
        ];
    }
}
