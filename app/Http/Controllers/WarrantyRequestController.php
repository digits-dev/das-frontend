<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Channel;
use App\Models\City;
use App\Models\IncludedItem;
use App\Models\PaymentMode;
use App\Models\ProblemDetail;
use App\Models\Province;
use App\Models\StoreDropOff;
use App\Models\Warranty;
use App\Models\WarrantyBackend;
use App\Models\WarrantyOnline;
use App\Models\WarrantyOnlineBackend;
use App\Models\WarrantyTracking;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class WarrantyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
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
            return ProblemDetail::active()->get();
        });

        $data['mode_of_payment'] =  Cache::remember('mode_of_payment', now()->addDays(1), function(){
            return PaymentMode::active()->get();
        });

        $data['items_included'] =  Cache::remember('items_included', now()->addDays(1), function(){
            return IncludedItem::active()->get();
        });

        $data['channels'] =  Cache::remember('channels', now()->addDays(1), function(){
            return Channel::active()->get();
        });

        $data['province'] =  Cache::remember('province', now()->addDays(1), function(){
            return Province::getAll()->get();
        });

        $data['brands'] =  Cache::remember('brands', now()->addDays(1), function(){
            return Brand::getAll()->get();
        });

        $data['stores_drop_off'] =  Cache::remember('stores_drop_off', now()->addDays(1), function(){
            return StoreDropOff::getAll()->get();
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

        $rules = [
            'digits_code' => 'required', // 9-digit numeric code
            'item_desc' => 'required|string|max:255',
            'brand' => 'required|string|max:50',
            'serial_number' => 'nullable|string|max:50',
            'problem_details' => 'required|array|min:1',
            'items_included' => 'required|array|min:1',
            'items_included_others' => 'nullable|string|max:255',
            'purchase_location' => 'required|integer|exists:channels,id',
            'store' => 'required|string|max:100',
            'branch' => 'required|string|max:100',
            'mode_of_return' => 'required|string|in:STORE DROP-OFF,DOOR-TO-DOOR',
            'store_drop_off' => 'required_if:mode_of_return,STORE DROP-OFF|string|max:100',
            'branch_dropoff' => 'required_if:mode_of_return,STORE DROP-OFF|string|max:100',
            'firstname' => 'required|string|max:150',
            'lastname' => 'required|string|max:150',
            'address_one' => 'required|string|max:255',
            'address_two' => 'nullable|string|max:255',
            'province' => 'required|string|exists:refprovince,provCode', // Assuming a province code validation
            'city' => 'required|string|exists:refcitymun,citymunCode', // Assuming a city code validation
            'brgy' => 'required|string|max:100',
            'country' => 'required|string|in:Philippines',
            'email_address' => 'required|email|max:100',
            'contact_number' => 'required|regex:/^09\d{9}$/',
            'order_no' => 'required|string|max:50',
            'purchase_date' => 'required|date|before:today',
            'payment' => 'required|array|min:1',
            'purchase_amount' => 'required|numeric|min:0',
            // 'bankname' => 'nullable|required_if:mode_of_return,HOME PICK-UP|string|max:100',
            // 'bank_account_no' => 'nullable|required_if:mode_of_return,HOME PICK-UP|string|digits_between:8,16',
            // 'bank_account_name' => 'nullable|required_if:mode_of_return,HOME PICK-UP|string|max:100',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->with('failed', 'Something went wrong!'.$validator->errors());
        }

        $transaction_type = 0;

        $channelName = Cache::remember('channel'.$request->purchase_location, now()->addDays(1), function() use($request){
            return Channel::getChannelById($request->purchase_location)->channel_name;
        });

        $cityName = Cache::remember('city'.$request->city, now()->addDays(1), function() use($request){
            return City::getByCode($request->city)->citymunDesc;
        });

        $provinceName = Cache::remember('province'.$request->province, now()->addDays(1), function() use($request){
            return Province::getByCode($request->province)->provDesc;
        });

        $modeOfPayment = '';
        foreach ($request->payment as $paymentId) {
            $payment = Cache::remember('payment'.$paymentId, now()->addDays(1), function() use($paymentId){
                return PaymentMode::getNameById($paymentId)->payment_name;
            });

            if ($payment) {
                $modeOfPayment .= $payment . ', ';
            }
        }
        $modeOfPayment = rtrim($modeOfPayment, ', ');

        $includedItems = '';
        foreach ($request->items_included as $includedItem) {
            $item = Cache::remember('items_included'.$includedItem, now()->addDays(1), function() use($includedItem){
                return IncludedItem::getById($includedItem)->items_description_included;
            });

            if ($item) {
                $includedItems .= $item .', ';
            }
        }
        $includedItems = rtrim($includedItems, ', ');

        $problemDetails = '';
        foreach ($request->problem_details as $problemDetail) {
            $problem = Cache::remember('problems'.$problemDetail, now()->addDays(1), function() use($problemDetail){
                return ProblemDetail::getById($problemDetail)->problem_details;
            });

            if ($problem) {
                $problemDetails .= $problem .', ';
            }
        }
        $problemDetails = rtrim($problemDetails, ', ');

        if($request->purchase_location == Channel::RETAIL) {
            $status = 1;
            // Check if 'store_drop_off' is set and contains 'SERVICE' with brand conditions
            if (!empty($request->store_drop_off) && str_contains($request->store_drop_off, 'SERVICE')) {
                if (in_array($request->brand, ['APPLE', 'BEATS'])) {
                    $transaction_type = 3;
                    $status = 29;
                }
            }
        }
        else{
            // Set transaction type to 1 if 'store_drop_off' contains 'SERVICE'
            if (!empty($request->store_drop_off) && str_contains($request->store_drop_off, 'SERVICE')) {
                $transaction_type = 1;
            }
        }

        $address = "$request->address_one $request->address_two $request->brgy $cityName $provinceName $request->country";

        $dataHeader = [
            'returns_status'   		=> 1,
            'returns_status_1'   	=> $status,
            'purchase_location'     => $channelName,
            'store' 			    => $request->store,
            'customer_last_name'    => $request->lastname,
            'customer_first_name'   => $request->firstname,
            'address'               => $address,
            'email_address'         => $request->email_address,
            'contact_no'            => $request->contact_number,
            'order_no'              => $request->order_no,
            'purchase_date'         => $request->purchase_date,
            'mode_of_payment'       => $modeOfPayment,
            // 'mode_of_refund'        => $request->refund,
            'items_included'        => $includedItems,
            'items_included_others' => $request->items_included_others,
            'mode_of_return'        => $request->mode_of_return,
            'store_dropoff'         => $request->store_drop_off,
            'branch'                => $request->branch,
            'branch_dropoff'        => $request->branch_dropoff,
            'created_at'            => now(),
            'transaction_type'      => $transaction_type
        ];

        $dataLines = [
            'digits_code'     		=> $request->digits_code,
            'item_description'    	=> $request->item_desc,
            'brand'   				=> $request->brand,
            'cost'               	=> $request->purchase_amount,
            'quantity'         		=> $request->qty ?? 1,
            'problem_details'       => $problemDetails,
            'problem_details_other'	=> $request->problem_details_other,
            'serialize'             => ($request->serial_number) ? 1 : 0,
            'created_at'            => now()
        ];

        $warrantyTrx = [];
        $refNum = '';
        $errorMessage = [];

        if($request->purchase_location == Channel::RETAIL) {
            try {
                DB::connection('backend')->beginTransaction();
                $warrantyBackend = WarrantyBackend::firstOrCreate([
                    'order_no' => $dataHeader['order_no'],
                    'email_address' => $dataHeader['email_address'],
                    'contact_no' => $dataHeader['contact_no'],
                ], $dataHeader);

                $warrantyBackendLine = $warrantyBackend->lines()->firstOrCreate([
                    'digits_code' => $dataLines['digits_code']
                ], $dataLines);

                if(!empty($request->serial_number)){
                    $warrantyBackendLine->serials()->firstOrCreate([
                        'serial_number' => $request->serial_number
                    ],[
                        'returns_header_id' => $warrantyBackend->id,
                        'serial_number' => $request->serial_number,
                        'created_at' => now()
                    ]);
                }

                DB::connection('backend')->commit();

                DB::beginTransaction();
                //frontend
                $dataHeader['return_reference_no'] = $warrantyBackend->return_reference_no;
                $warranty = Warranty::firstOrCreate([
                    'order_no' => $dataHeader['order_no'],
                    'email_address' => $dataHeader['email_address'],
                    'contact_no' => $dataHeader['contact_no'],
                ], $dataHeader);

                $warrantyLine = $warranty->lines()->firstOrCreate([
                    'digits_code' => $dataLines['digits_code']
                ], $dataLines);

                if(!empty($request->serial_number)){
                    $warrantyLine->serials()->firstOrCreate([
                        'serial_number' => $request->serial_number
                    ],[
                        'returns_header_id' => $warranty->id,
                        'serial_number' => $request->serial_number,
                        'created_at' => now()
                    ]);
                }

                if (!$warrantyBackend->wasRecentlyCreated) {
                    $errorMessage[] = "Ref # {$warrantyBackend->return_reference_no} already exist in the system!";
                }
                else{
                    WarrantyTracking::insert([
                        'return_reference_no' => $warrantyBackend->return_reference_no,
                        'returns_status' => 1,
                        'created_at' => now()
                    ]);
                }

                $refNum = $warrantyBackend->return_reference_no;
                $warrantyTrx = $warrantyBackend;

                DB::commit();
            } catch (Exception $e) {
                DB::connection('backend')->rollBack();
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->back()->with('failed', 'Something went wrong!'.$e->getMessage());
            }
        }
        else{
            try {
                DB::connection('backend')->beginTransaction();

                $warrantyOnlineBackend = WarrantyOnlineBackend::firstOrCreate([
                    'order_no' => $dataHeader['order_no'],
                    'email_address' => $dataHeader['email_address'],
                    'contact_no' => $dataHeader['contact_no'],
                ], $dataHeader);

                $warrantyOnlineBackendLine = $warrantyOnlineBackend->lines()->firstOrCreate([
                    'digits_code' => $dataLines['digits_code']
                ], $dataLines);

                $warrantyOnlineBackendLine->serials()->firstOrCreate([
                    'serial_number' => $request->serial_number
                ],[
                    'returns_header_id' => $warrantyOnlineBackend->id,
                    'serial_number' => $request->serial_number,
                    'created_at' => now()
                ]);

                DB::connection('backend')->commit();

                DB::beginTransaction();
                //frontend
                $dataHeader['return_reference_no'] = $warrantyOnlineBackend->return_reference_no;
                $warrantyOnl = WarrantyOnline::firstOrCreate([
                    'order_no' => $dataHeader['order_no'],
                    'email_address' => $dataHeader['email_address'],
                    'contact_no' => $dataHeader['contact_no'],
                ], $dataHeader);

                $warrantyOnlLine = $warrantyOnl->lines()->firstOrCreate([
                    'digits_code' => $dataLines['digits_code']
                ], $dataLines);

                $warrantyOnlLine->serials()->firstOrCreate([
                    'serial_number' => $request->serial_number
                ],[
                    'returns_header_id' => $warrantyOnl->id,
                    'serial_number' => $request->serial_number,
                    'created_at' => now()
                ]);

                if (!$warrantyOnlineBackend->wasRecentlyCreated) {
                    $errorMessage[] = "Ref # {$warrantyOnlineBackend->return_reference_no} already exist in the system!";
                }
                else{
                    WarrantyTracking::insert([
                        'return_reference_no' => $warrantyOnlineBackend->return_reference_no,
                        'returns_status' => 1,
                        'created_at' => now()
                    ]);
                }

                $refNum = $warrantyOnlineBackend->return_reference_no;
                $warrantyTrx = $warrantyOnlineBackend;

                DB::commit();
            } catch (Exception $e) {
                DB::connection('backend')->rollBack();
                DB::rollBack();
                Log::error($e->getMessage());
                return redirect()->back()->with('failed', 'Something went wrong!'.$e->getMessage());
            }

        }

        if(!empty($errorMessage)){
            return back()->with('failed', implode(" ",$errorMessage));
        }

        return back()->with([
            'success'=>"Your Reference Number is {$refNum}",
            'tracking'=>$warrantyTrx
        ]);

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

}
