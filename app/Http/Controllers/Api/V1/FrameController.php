<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frame;
use Illuminate\Support\Facades\Gate;

class FrameController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAnyUser', Frame::class);

        $frames = Frame::query()->where([
            'status' => Frame::STATUS_ACTIVE
        ])->get();

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
}
