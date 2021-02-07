<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ResetPasswordRequest extends FormRequest
{
    public $errors = [];

    /**
     * Verifica si hay errores en la validacion
     *
     * @return bool
     */
    public function hasErrors()
    {
        return (bool) $this->errors;
    }

    /**
     * Devuelve un arreglo de errores
     *
     * @return Array $errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errors = $validator->errors()->getMessages();
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ];
    }
}
