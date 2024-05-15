<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFrameRequest;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Frame;
use Illuminate\Support\Facades\Gate;

class FrameController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Frame::class);

        $frames = Frame::query()->with('currencies')->get();

        $frames = $frames->map(function($frame){
            $pricesJson = $frame->currencies->map(function($currency){
                return [
                    'id' => $currency->id,
                    'name' => $currency->name,
                    'price' => $currency->pivot->price
                ];
            });

            $frame['prices'] = $pricesJson;

            $frame->setHidden(['currencies']);

            return $frame;
        });
        
        return response()->json($frames);
    }

    public function store(StoreFrameRequest $request)
    {
        Gate::authorize('create', Frame::class);

        $fields = $request->all();

        $prices = $fields['prices'];

        unset($fields['prices']);

        $createdModel = Frame::create($fields);

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

    public function update(Request $request, $id)
    {
        $model = Frame::with('currencies')->findOrFail($id);

        Gate::authorize('update', $model);

        $fields = $request->all();

        $prices = $fields['prices'] ?? [];

        unset($fields['prices']);

        $model->update($fields);

        $pricesJsonHashMap = $model->currencies->mapWithKeys(function($currency, $id){
            return [
                $currency->name => [
                    'id' => $currency->id,
                    'name' => $currency->name,
                    'price' => $currency->pivot->price
                ]
            ];
        });

        if (count($prices) > 0) {
            foreach($prices as $currency => $price) {
                if(isset($pricesJsonHashMap[$currency])){
                    $pricesJsonHashMap[$currency] =[
                        'id' => $pricesJsonHashMap[$currency]['id'],
                        'name'=> $pricesJsonHashMap[$currency]['name'],
                        'price'=> $price
                    ];
                }
            }

            $newPricesSync = [];

            foreach($pricesJsonHashMap as $priceJson){
                $newPricesSync[$priceJson['id']] = ['price' => $priceJson['price']];
            }

            $model->currencies()->sync($newPricesSync);
        }

        $model['prices'] = array_values($pricesJsonHashMap->toArray());

        $model->makeHidden('currencies');

        return response()->json($model, 201);
    }

    public function show(Request $request, $id)
    {
        $model = Frame::with('currencies')->findOrFail($id);

        Gate::authorize('view', $model);

        $pricesJson = $model->currencies->map(function($currency, $id){
            return [
                'id' => $currency->id,
                'name' => $currency->name,
                'price' => $currency->pivot->price
            ];
        });

        $model['prices'] = $pricesJson;

        $model->makeHidden('currencies');

        return response()->json($model);
    }

    public function destroy(Request $request, $id)
    {
        $model = Frame::findOrFail($id);

        Gate::authorize('delete', $model);

        return response()->json([
            'msg' => 'deleted successfully'
        ]);
    }
}
