<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class IndexInvoicesByCreditor extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'creditor_id' => "required|exists:creditors,id",
        ];
    }
}
