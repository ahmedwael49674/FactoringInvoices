<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class DeleteInvoice extends RequestAbstract
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
        ];
    }
}
