<?php

namespace App\Rules;

use Closure;
use App\Models\Currency;
use Illuminate\Contracts\Validation\ValidationRule;

class StorePricesInputFieldRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currencies = Currency::all();

        $currenciesSentInRequest = array_keys($value);

        $currenciesFiltered = $currencies->filter(function ($currency) use ($currenciesSentInRequest) {
            return in_array($currency->name, $currenciesSentInRequest);
        });
        

        if ($currenciesFiltered->count() != $currencies->count()) {
            $fail('not all currencies are provided in request');
        }

        if(count(array_unique(array_values($value))) != count(array_values($value))) {
            $fail("prices are not unique per currency");
        }
    }
}
