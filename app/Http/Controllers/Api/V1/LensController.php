<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lens;
use Illuminate\Support\Facades\Gate;

class LensController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Lens::class);

        $lenses = Lens::query()->get();

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
}
