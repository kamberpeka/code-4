<?php

namespace DTApi\Http\Requests;

class JobStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (request()->user()->user_type == env('CUSTOMER_ROLE_ID'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_language_id' => 'required',
            // ToDo: fill all the rules
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // ToDo: fill all custom messages
        ];
    }
}
