<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('super-admin');  // Only super-admins can update
    }

    public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('admin')->id,


        'password' => 'nullable|string|min:8|confirmed',
    ];
}

}
