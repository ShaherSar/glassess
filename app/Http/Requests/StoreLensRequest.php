<?php

namespace App\Http\Requests;

use App\Models\Lens;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\StorePricesInputFieldRule;

class StoreLensRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'color' => ['required', 'string'],
            "prescription_type" => ['required', "in:" . implode(',', array_keys(Lens::getPrescriptionTypes()))],
            'prices' => [new StorePricesInputFieldRule()],
            "lens_type" => ['required', "in:" . implode(',', array_keys(Lens::getLensesTypes()))],
            'stock' => ['required', 'integer']
        ];
    }
}
