<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FormItem extends FormRequest
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
                //
            ];
        }
        return [
            'name' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric'
        ];
    }
}
