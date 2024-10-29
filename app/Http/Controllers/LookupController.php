<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\PaymentMode;
use App\Models\StoreDropOff;
use App\Models\StoreMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LookupController extends Controller
{
    public function getStores(Request $request){
        Log::debug('get-store'.json_encode($request->stores));
        $stores = Cache::remember('stores'.$request->stores, now()->addDays(1), function() use ($request){
            if($request->stores){
                return StoreMaster::getByChannel($request->stores)->get();
            }
            return StoreMaster::getAll()->get();
        });

        return response()->json($stores);
    }

    public function getBackendStores(Request $request){
        Log::debug('get-store-backend'.json_encode($request->store_backend));

        if($request->store_backend){
            $storeKey = "store{$request->store_backend}{$request->purchase_location}";
            $storeId = Cache::remember($storeKey, now()->addDays(1), function() use($request) {
                return StoreMaster::getByName($request->purchase_location, $request->store_backend)->select('id')->first();
            });

            $storeBackend = StoreDropOff::getBackendStoresById($storeId->id)->get();
            return response()->json($storeBackend);
        }

        $storeBackend = StoreDropOff::getBackendStores()->get();
        return response()->json($storeBackend);
    }

    public function getStoreDropOff(Request $request){
        $storeDropOff = Cache::remember('stores_drop_off', now()->addDays(1), function(){
            return StoreDropOff::getAll()->get();
        });

        return response()->json($storeDropOff);
    }

    public function getBranchDropOff(Request $request){
        if($request->drop_off_store){
            $storeKey = "store_dropoff{$request->drop_off_store}";
            $storeId = Cache::remember($storeKey, now()->addDays(1), function() use($request) {
                return StoreMaster::getByName($request->location, $request->drop_off_store)->select('id')->first();
            });

            if($storeId->id){
                $storeBranchDrop = StoreDropOff::getBranchDropOffById($storeId->id)->get();
                return response()->json($storeBranchDrop);
            }

            $storeBranchDrop = StoreDropOff::getBranchDropOffById($storeId->id)->where('store_dropoff_privilege', 'YES')->get();
            return response()->json($storeBranchDrop);
        }

        $storeBranchDrop = StoreDropOff::getBackendStores()->get();
        return response()->json($storeBranchDrop);
    }

    public function getCity(Request $request){
        Log::debug('get-city'.json_encode($request->provinces));
        $city = Cache::remember('city'.$request->provinces, now()->addDays(1), function() use ($request){
            if($request->provinces){
                return City::getByProvince($request->provinces)->get();
            }
            return City::getAll()->get();
        });

        return response()->json($city);
    }

    public function getRefundMode(Request $request){
        Log::debug('get-refund-mode'.json_encode($request->mode_of_refund));
        $refundMode = Cache::remember('get-refund-mode'.$request->mode_of_refund, now()->addDays(1), function() use ($request){
            if($request->mode_of_refund){
                return PaymentMode::getById($request->mode_of_refund)->get();
            }
            return PaymentMode::getAll()->get();
        });

        return response()->json($refundMode);
    }
}
