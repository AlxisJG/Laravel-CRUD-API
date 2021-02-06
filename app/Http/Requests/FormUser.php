<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class FormUser extends FormRequest
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
        if ($this->getMethod() == 'PUT') {
            return [
                'email' => 'unique:users|email:rfc',
                'phone' => 'numeric',
                'birth_date' => 'date',
                'username' => 'unique:users',
                'password' => 'string|confirmed'

            ];
        }
        return [
            'name' => 'required',
            'email' => 'required|unique:users|email:rfc',
            'phone' => 'numeric',
            'birth_date' => 'date',
            'username' => 'required|unique:users',
            'password' => 'required|string|confirmed'
        ];
    }
}
