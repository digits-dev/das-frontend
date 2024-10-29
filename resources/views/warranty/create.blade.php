@include('partials.header')

    <div class="search" style="padding-bottom: 40px;">
        <h1 class="title-homepage DesktopView" style="font-size: 45px;"><strong>Create A Warranty Request</strong></h1>
        <div class="create-form">
            <a href="{{ URL::to('/') }}" class="DesktopView"><i class="fa fa-arrow-left" aria-hidden="true"
                    style="font-size: 2em;color:dimgray;"></i></a>

            <!-- Start of Modal -->
            <div class="modal fade" id="myModal" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="float:right;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="thank-you-pop">
                                <img src="{{ asset('images/Green-Round-Tick.png') }}" alt="">
                                <h3 style="font-size:21px;font-weight:600;">
                                    Your Warranty Request is now being processed!
                                </h3>
                                <p>
                                    @if (!empty(session('tracking')))
                                        @if (session('tracking')->purchase_location == 'RETAIL STORE' && session('tracking')->mode_of_return == 'STORE DROP-OFF')
                                            You may proceed to the store to drop-off your item. Please bring the
                                            original
                                            packaging and its included accessories.
                                        @else
                                            Please check your email for a copy of your request. A representative will
                                            reach
                                            out to you in a few days to process your concern.
                                        @endif
                                    @endif
                                </p>
                                <h3 class="cupon-pop">{{ session('success') }}</h3>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::to('/') }}"><button type="button" class="btn hvr-hover"
                                    style="color:white; background-color:#3C8DBC;">Go Back To Home</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal -->

            @if ($message = Session::get('success'))
                <script>
                    $(function() {
                        $('#myModal').modal('show');
                    });
                </script>
            @endif

            @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>Failed to Submit. {{ session('failed') }}</strong>
                </div>
            @endif

            <form class="mt-3 review-form-box" method="post" action="{{ route('saveWarrantyRequest') }}">
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">

                <div class="form-row">
                    <div class="form-group col-md-12"> <!-- Item Code -->
                        <label class="mb-0" for="digits_code">Item Code</label>
                        <input type="text" class="form-control" id="digits_code" name="digits_code"
                            placeholder="Item Code">
                    </div>
                    <div class="form-group col-md-12"> <!-- Item Description -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="item_desc">Item Description</label>
                        <input type="text" class="form-control" name="item_desc" id="item_desc"
                            placeholder="Item Description" required>
                        <span class="error-content" id="item_desc_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-12"> <!-- Brand -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="brand">Brand</label>
                        <select name="brand" id="brand" class="form-control limitedNumbSelect2"
                            onchange="selectedBrand()" style="width:100%;" required>
                            <option value="" selected disabled>Choose brand here...</option>
                            @foreach ($result['brands'] as $key => $brand)
                                <option value="{{ $brand->brand_description }}">{{ $brand->brand_description }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error-content" id="brand_error" hidden></span>
                    </div>
                    <div class="form-group col-md-12" style="z-index: 0"> <!-- Serial Number -->
                        <label class="mb-0" for="serial_number">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number"
                            placeholder="Serial Number">
                    </div>
                    <div class="form-group col-md-12"> <!-- Problem Details -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="problem_details">Problem Details</label>
                        <select data-placeholder="Choose problem details here..." id="problem_details"
                            name="problem_details[]" id="ProblemDetail" onchange="OtherProblemDetail()"
                            class="form-control limitedNumbSelect2" style="width:100%;" multiple="true" required>
                            @foreach ($result['problem_details'] as $key => $problem_detail)
                                <option value="{{ $problem_detail->id }}">{{ $problem_detail->problem_details }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error-content" id="ProblemDetail_error" hidden>This field is required.*</span>
                    </div>

                    <div class="form-group col-md-12" id="show_other_problem"></div>


                    <div class="form-group col-md-12" style="background-color:#3C8DBC">
                        <p style="font-weight: bold; color: white; margin: 5px 5px; text-align: justify;">
                            Please indicate and return all accessories included inside the box.
                            We may not process your warranty due to incomplete requirements.
                        </p>
                    </div>


                    <div class="form-group col-md-12"> <!-- Items Included -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="items_included">Items Included</label>
                        <select data-placeholder="Choose items included here..." id="items_included"
                            name="items_included[]" id="items_included" onchange="OtherItemsIncluded()"
                            class="form-control limitedNumbSelect2" style="width:100%;" multiple="true" required>
                            @foreach ($result['items_included'] as $key => $item_included)
                                <option value="{{ $item_included->id }}">
                                    {{ $item_included->items_description_included }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error-content" id="items_included_error" hidden>This field is required.*</span>
                    </div>

                    <div class="form-group col-md-12" id="show_other_item"></div>
                </div>
                <hr />
                <div class="form-row">
                    <div class="form-group col-md-4" id="purchase_location_div"> <!-- Purchase Location -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="channels">Purchase Location</label>
                        <select class="form-control" name="purchase_location" id="channels" onchange="showStores()"
                            required>
                            <option value="" selected disabled>Choose store here...</option>
                            @foreach ($result['channels'] as $key => $channel)
                                <option value="{{ $channel->id }}">{{ $channel->channel_name }}</option>
                            @endforeach
                        </select>
                        <span class="error-content" id="purchase_location_error" hidden>This field is
                            required.*</span>
                    </div>

                    <div class="form-group col-md-4" id="store_div"> <!-- Store -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="selectedStores">Store</label>
                        <select class="form-control" name="store" id="selectedStores"
                            onchange="showCustomerLocation()" required disabled>
                            <option value="" selected disabled>Choose store here...</option>
                        </select>
                        <span class="error-content" id="selectedStores_error" hidden>This field is required.*</span>
                    </div>

                    <div class="form-group col-md-4" id="branch_div"> <!-- Branch -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="branch">Branch</label>
                        <select class="form-control" name="branch" id="branch" required disabled>
                            <option value="" selected disabled>Choose branch here...</option>
                        </select>
                        <span class="error-content" id="branch_error" hidden>This field is required.*</span>
                    </div>
                </div>
                <hr />
                <div class="form-row">
                    <div class="form-group col-md-6" id="mode_of_return_div"> <!-- Mode of Return -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="mode_of_return">Mode of Return</label>
                        <select class="form-control" name="mode_of_return" id="mode_of_return"
                            onchange="showDropOff()" required disabled>
                            <option value="" selected disabled>Choose mode of return here...</option>
                        </select>
                        <span class="error-content" id="mode_of_return_error" hidden>This field is required.*</span>
                    </div>

                    <div class="form-group col-md-6" id="store_drop_off_div"> <!-- Store Drop-Off -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="store_drop_off">Store Drop-Off</label>
                        <select class="form-control" name="store_drop_off" id="store_drop_off"
                            onchange="showBranch()">
                            <option value="" selected disabled>Choose store drop-off here...</option>
                        </select>
                        <span class="error-content" id="store_drop_off_error" hidden>This field is required.*</span>
                    </div>

                    <div class="form-group col-md-6" id="branch_branch_dropoff_div"> <!-- Branch Drop-Off -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="branch_dropoff">Branch Drop-Off</label>
                        <select class="form-control" name="branch_dropoff" id="branch_dropoff" disabled>
                            <option value="" selected disabled>Choose branch drop-off here...</option>
                        </select>
                        <span class="error-content" id="branch_dropoff_error"></span>
                    </div>
                </div>
                <br><br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="firstname">First Name</label> <!-- First Name -->
                        <input type="text" class="form-control" name="firstname" id="firstname"
                            placeholder="First Name" required>
                        <span class="error-content" id="firstname_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="lastname">Last Name</label> <!-- Last Name -->
                        <input type="text" class="form-control" name="lastname" id="lastname"
                            placeholder="Last Name" required>
                        <span class="error-content" id="lastname_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="address_one">Address Line 1</label>
                        <!-- Address Line 1 -->
                        <input type="text" class="form-control" name="address_one" id="address_one"
                            placeholder="Address Line 1" required>
                        <span class="error-content" id="address_one_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mb-0" for="address_two">Address Line 2</label> <!-- Address Line 2 -->
                        <input type="text" class="form-control" name="address_two" id="address_two"
                            placeholder="Address Line 2">
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="provinces">State/Province</label>
                        <!-- State/Province -->
                        <select class="form-control" name="province" id="provinces" onchange="showCity()" required>
                            <option value="" selected disabled>Choose state/province here...</option>
                            @foreach ($result['province'] as $provinces)
                                <option class="capitalize" value="{{ $provinces->provCode }}">
                                    {{ $provinces->provDesc }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error-content" id="provinces_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="selectedcities">City/Municipality</label>
                        <!-- City/Municipality -->
                        <select class="form-control" name="city" id="selectedcities" required disabled>
                            <option value="" selected disabled>Choose city/municipality here...</option>
                        </select>
                        <span class="error-content" id="selectedcities_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="brgy">Barangay</label> <!-- Barangay -->
                        <input type="text" class="form-control" name="brgy" id="brgy"
                            placeholder="Barangay" required>
                        <span class="error-content" id="brgy_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="country">Country</label> <!-- Country -->
                        <select class="form-control capitalize" name="country" id="country" placeholder="Country"
                            required>
                            <option value="Philippines" selected>Philippines</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="email_address">Email Address</label>
                        <!-- Email Address -->
                        <input type="email" class="form-control" name="email_address" id="email_address"
                            placeholder="Enter Email" required>
                        <span class="error-content" id="email_address_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="contact_number">Mobile Number</label>
                        <!-- Mobile Number -->
                        <input type="text" class="form-control" name="contact_number" id="contact_number"
                            pattern="^(09|\+639)[0-9]{9}$" placeholder="Mobile Number" required>
                        <span class="error-content" id="contact_number_error"></span>
                    </div>
                </div>
                <br>
                <br>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="order_no">Order Number/Invoice Number</label>
                        <!-- Order Number -->
                        <input type="text" class="form-control" name="order_no" id="order_no"
                            placeholder="Order Number/Invoice Number" required>
                        <span class="error-content" id="order_no_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="purchase_date">Purchase Date</label>
                        <!-- Purchase Date -->
                        <input type="text" id="purchase_date" name="purchase_date" id="purchase_date"
                            placeholder="yyyy/mm/dd" class="form-control" autocomplete="off" required />
                        <span class="error-content" id="purchase_date_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="payment">Original Mode of Payment</label>
                        <!-- Original Mode of Payment -->
                        <select data-placeholder="Choose mode of payment here..." name="payment[]" id="payment"
                            onchange="Selected_Mode_Of_Payment()" class="form-control limitedNumbSelect2"
                            style="width:100%;" multiple="true" required>
                            @foreach ($result['mode_of_payment'] as $key => $payment)
                                <option value="{{ $payment->id }}">{{ $payment->mode_of_payment }}</option>
                            @endforeach
                        </select>
                        <span class="error-content" id="payment_error" hidden>This field is required.*</span>
                    </div>
                    <br />
                    <div class="form-group col-md-6">
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="purchase_amount">Purchase Amount</label>
                        <!-- Purchase Amount -->
                        <input type="text" class="form-control" style="height:40px;" name="purchase_amount"
                            id="purchase_amount" oninput="ValidatePurchaseAmount()" MaxLength="12"
                            placeholder="Purchase Amount" required>
                        <span class="error-content" id="purchase_amount_error"></span>
                    </div>
                </div><br>

                <div class="form-row">
                    <div class="form-group col-md-12" id="bank_text" style="background-color:#3C8DBC"
                        hidden="true">
                        <p style="font-weight: bold; color: white; margin: 5px 5px; text-align: justify;">
                            The information below will be used to refund your transaction once we confirm the validity
                            of
                            your warranty.
                            Do not give us your Credit Card / Debit Card numbers, nor your CVC.
                        </p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6" id="bank_refund" hidden="true"> <!-- Mode of Refund -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="refund">Mode of Refund</label>
                        <select class="form-control" name="refund" id="refund" onchange="showBankDetails()"
                            disabled>
                            <option value="" selected disabled>Choose mode of refund here...</option>
                        </select>
                        <span class="error-content" id="refund_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6" id="bank_name_div" hidden="true"> <!-- Bank Name -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="bank_name">Bank Name</label>
                        <input type="text" class="form-control" name="bankname" id="bank_name"
                            placeholder="Bank Name">
                        <span class="error-content" id="bank_name_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6" id="bank_acc_no_div" hidden="true">
                        <!-- Bank Account Number -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="bank_account_no">Bank Account Number</label>
                        <input type="text" class="form-control" name="bank_account_no" id="bank_account_no"
                            placeholder="Bank Account Number">
                        <span class="error-content" id="bank_account_no_error" hidden>This field is required.*</span>
                    </div>
                    <div class="form-group col-md-6" id="bank_acc_name_div" hidden="true">
                        <!-- Bank Account Name -->
                        <span class="requiredField">*</span>
                        <label class="mb-0" for="bank_account_name">Bank Account Name</label>
                        <input type="text" class="form-control" name="bank_account_name" id="bank_account_name"
                            placeholder="Bank Account Name">
                        <span class="error-content" id="bank_account_name_error" hidden>This field is
                            required.*</span>
                    </div>
                </div><br>



                <div class="form-row">
                    <div class="form-group col-md-12">
                        <button type="button" id="submit" class="btn hvr-hover"
                            style="float: right; background-color: #3C8DBC;">Create</button>
                    </div>
                </div>

                <!-- Start of Confirm Submit Modal -->
                <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                Confirm Submit
                            </div>
                            <div class="modal-body">
                                Are you sure you want to submit the following details?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success success">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Confirm Submit Modal -->
            </form>
        </div>
    </div>

    @push('bottom')
        <script>
            $(document).ready(function(){
                $(".limitedNumbChosen").chosen({})
                .bind("chosen:maxselected", function (){});

                $(".limitedNumbSelect2").select2({});

                $('form').submit(function(){
                    $(this).find(':submit').attr('disabled','disabled');
                });
            });

            $(document).on('click', '#submit', function(e){
                e.preventDefault();
                var validated = ValidateForm();
                if(validated){
                    $('#confirm-submit').modal('show');
                }
            });

            const store_drop_off_list = {!! json_encode($result['stores_drop_off']) !!};
            $('#purchase_date').datepicker({
                format: 'yyyy/mm/dd',
                autoclose: true,
                endDate: new Date()
            });
        </script>

        <script src="{{asset('js/create-warranty.js')}}"></script>
    @endpush

@include('partials.footer')
