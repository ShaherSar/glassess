<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGlassesPairRequest;
use App\Models\Frame;
use App\Models\GlassesPair;
use App\Models\Lens;

class GlassesController extends Controller
{
    public function create_pair(CreateGlassesPairRequest $request)
    {
        $userCurrencyId = auth()->user()->currency?->id;

        if(is_null($userCurrencyId)){
            return response()->json([
                'msg' => 'user does not have a currenct set yet.'
            ], 402);
        }

        $frame = Frame::query()->with([
            'currencies' => function($query) use ($userCurrencyId){
                return $query->where('currencies.id',  '=', $userCurrencyId);
            }
        ])->findOrFail($request->get('frame_id'));

        $lens = Lens::query()->with([
            'currencies' => function($query) use ($userCurrencyId){
                return $query->where('currencies.id',  '=', $userCurrencyId);
            }
        ])->findOrFail($request->get('lens_id'));

        $totalPrice = $frame->currencies->first()->pivot->price + $lens->currencies->first()->pivot->price;

        $glassesPair = GlassesPair::create([
            'user_id' => auth()->user()->id,
            'frame_id' => $request->get('frame_id'),
            'lens_id' => $request->get('lens_id'),
            'price' => $totalPrice,
            'currency' => auth()->user()->currency?->name
        ]);

        $lens->update([
            'stock' => $lens->stock -1 
        ]);

        $frame->update([
            'stock' => $lens->stock -1 
        ]);

        return response()->json($glassesPair, 201);
    }
}
