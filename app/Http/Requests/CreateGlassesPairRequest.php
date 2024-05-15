<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\StockGreaterThanZero;
use App\Models\Frame;
use App\Models\Lens;

class CreateGlassesPairRequest extends FormRequest
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
            'frame_id' => ['required', 'int', new StockGreaterThanZero(Frame::class)],
            'lens_id' => ['required', 'int', new StockGreaterThanZero(Lens::class)]
        ];
    }
}
