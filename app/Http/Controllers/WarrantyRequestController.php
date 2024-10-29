<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Channel;
use App\Models\IncludedItem;
use App\Models\PaymentMode;
use App\Models\ProblemDetail;
use App\Models\Province;
use App\Models\StoreDropOff;
use App\Models\WarrantyTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class WarrantyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];

        $data['problem_details'] =  Cache::remember('problem', now()->addDays(1), function(){
            return ProblemDetail::active()->get();//done
        });

        $data['mode_of_payment'] =  Cache::remember('mode_of_payment', now()->addDays(1), function(){
            return PaymentMode::active()->get();//done
        });

        $data['items_included'] =  Cache::remember('items_included', now()->addDays(1), function(){
            return IncludedItem::active()->get();//done
        });

        $data['channels'] =  Cache::remember('channels', now()->addDays(1), function(){
            return Channel::active()->get();//done
        });

        $data['province'] =  Cache::remember('province', now()->addDays(1), function(){
            return Province::getAll()->get();//done
        });

        $data['brands'] =  Cache::remember('brands', now()->addDays(1), function(){
            return Brand::getAll()->get();//done
        });

        $data['stores_drop_off'] =  Cache::remember('stores_drop_off', now()->addDays(1), function(){
            return StoreDropOff::getAll()->get();//done
        });

        return view('warranty.create')->with('result', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //create warranty here...
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            'search' => 'required|exists:returns_tracking_status,return_reference_no'
        ]);

        if($request->search){
            $tracking = [];
            $route = Route::getFacadeRoot()->current()->uri();
            $tracking['route'] = $route;
            $result = [];
            $seachParam = mb_substr($request->search, 8, 8, "UTF-8");

            switch ($seachParam) {
                case 'E': case 'R':
                    $result = WarrantyTracking::getTrackingTimeline($request->search);
                    break;
                default:
                    $result = [];
                    break;
            }

            $tracking['trackingresult'] = $result;
            return view('index')->with('result', $tracking);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
