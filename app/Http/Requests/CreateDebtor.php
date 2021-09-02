<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class CreateDebtor extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => "required",
            'email'          => "required|unique:debtors,email",
            'phone'          => "required",
            'contact_info'   => "array",
        ];
    }
}
