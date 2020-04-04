<?php

namespace App\Http\Requests\Transactions;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'required|numeric|exists:accounts,id|different:to',
            'to' => 'required|numeric|exists:accounts,id|different:from',
            'details' => 'required|string',
            'amount' => 'required|numeric'
        ];
    }
}
