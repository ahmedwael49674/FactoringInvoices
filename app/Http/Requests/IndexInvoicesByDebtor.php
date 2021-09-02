<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class IndexInvoicesByDebtor extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'debtor_id' => "required|exists:debtors,id",
        ];
    }
}
