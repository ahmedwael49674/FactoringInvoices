<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class UpdateDebtor extends RequestAbstract
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'             => "required",
            'email'          => [Rule::unique('creditors')->ignore($this->id),],
            'contact_info'   => "array",
        ];
    }
}
