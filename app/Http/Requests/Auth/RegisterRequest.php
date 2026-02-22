<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required','email','max:255','unique:Usuario,email'],
            'password' => ['required','string','min:8','max:72'],
            'nombre' => ['nullable','string','max:100'],
            'apellido' => ['nullable','string','max:100'],
        ];
    }
}