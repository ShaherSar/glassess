<?php

namespace App\Rules;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\ValidationRule;

class StockGreaterThanZero implements ValidationRule
{

    protected Model $model;

    public function __construct(string $modelName)
    {
        $this->model = new $modelName();
    }
    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $modelInstance = $this->model->query()->findOrFail($value);

        if ($modelInstance->stock <= 0) {
            $fail("stock is not greater than zero");
        }
    }
}
