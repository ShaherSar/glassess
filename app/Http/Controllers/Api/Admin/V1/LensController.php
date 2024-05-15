<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lens;
use App\Http\Requests\StoreLensRequest;
use App\Http\Requests\UpdateLensRequest;
use App\Models\Currency;
use Illuminate\Support\Facades\Gate;

class LensController extends Controller
{
    public function index (Request $request)
    {
        Gate::authorize('viewAny', Lens::class);

        $lenses = Lens::query()->with('currencies')->get();

        $lenses = $lenses->map(function($lens){
            $pricesJson = $lens->currencies->map(function($currency){
                return [
                    'id' => $currency->id,
                    'name' => $currency->name,
                    'price' => $currency->pivot->price
                ];
            });

            $lens['prices'] = $pricesJson;

            $lens->setHidden(['currencies']);

            return $lens;
        });
        
        return response()->json($lenses);
    }

    public function store (StoreLensRequest $request)
    {
        Gate::authorize('create', Lens::class);

        $fields = $request->all();

        $prices = $fields['prices'];

        unset($fields['prices']);

        $createdModel = Lens::create($fields);

        $currencies = Currency::all();

        $currenciesIdsNameHashMap = $currencies->mapWithKeys(function ($currency, $key) {
            return [$currency->name => $currency->id];
        });

        $currenciesWithPrices = [];
        $pricesJson = [];

        foreach ($prices as $currency => $price) {
            $currencyId = $currenciesIdsNameHashMap[$currency];

            $currenciesWithPrices[$currencyId] = ['price' => $price];
            $pricesJson[] = [
                'id' => $currencyId,
                'name' => $currency,
                "price" => $price 
            ];
        }

        $createdModel->currencies()->sync($currenciesWithPrices);

        $createdModel['prices'] = $pricesJson;

        return response()->json($createdModel, 201);
    }

    public function update (UpdateLensRequest $request, $id)
    {
        $model = Lens::findOrFail($id);

        Gate::authorize('update', $model);

        $model->update($request->all());

        return response()->json($model, 201);
    }

    public function show (Request $request, $id)
    {
        $model = Lens::findOrFail($id);

        Gate::authorize('view', $model);

        return response()->json($model);
    }

    public function destroy (Request $request, $id)
    {
        $model = Lens::findOrFail($id);

        Gate::authorize('delete', $model);

        return response()->json([
            'msg' => 'deleted successfully'
        ]);
    }
}
