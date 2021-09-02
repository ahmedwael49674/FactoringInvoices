<?php

namespace App\Http\Requests;

use Pearl\RequestValidate\RequestAbstract;

class CreateCreditor extends RequestAbstract
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
            'email'          => "required|unique:creditors,email",
            'phone'          => "required",
            'contact_info'   => "array",
        ];
    }
}
