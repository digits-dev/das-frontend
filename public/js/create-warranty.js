function showStores() {
    $("#selectedStores").removeAttr("disabled");
    $("#mode_of_return").removeAttr("disabled");

    // Hide Store Drop Off and Branch Drop Off
    $('#store_drop_off_div, #branch_branch_dropoff_div').hide();
    $('#store_drop_off, #branch_dropoff').attr("required", false);

    $("#mode_of_return_div").removeClass("col-md-4").addClass("col-md-6");
    $("#store_drop_off_div, #branch_branch_dropoff_div").removeClass("col-md-4").addClass("col-md-6");

    // Displaying Store Dropdown
    let channel = $("#channels").val();
    $.ajax({
        url: "get-stores",
        type: "POST",
        data: {
            'stores': channel,
            '_token': $("#token").val()
        },
        success: function(result) {
            let showData = ["<option value='' selected disabled>Choose store here...</option>"];
            $.each(result, function(i, store) {
                if (channel == 6 || channel == 4) {
                    showData.push("<option value='" + store.store_name + "'>" + store.store_name + "</option>");
                }
            });
            $('#selectedStores').empty().html(showData.join(''));
        }
    });

    // Mode of Return Dropdown
    let addOption = channel == 4
        ? `<option value="" selected disabled>Choose mode of return here...</option>
        <option value="DOOR-TO-DOOR">Door-to-Door (Cash on Pick-up)</option>
        <option value="STORE DROP-OFF">Store Drop-Off</option>`
        : `<option value="" selected disabled>Choose mode of return here...</option>
        <option value="STORE DROP-OFF">Store Drop-Off</option>`;
    $("#mode_of_return").html(addOption);

}

function showCustomerLocation() {
    $("#branch").removeAttr("disabled");
    let store_backend = $("#selectedStores").val();
    let purchase_location = $("#channels").val();

    $.ajax({
        url: "get-backend-stores",
        type: "POST",
        data: {
            'store_backend': store_backend,
            'purchase_location': purchase_location,
            '_token': $("#token").val()
        },
        success: function(result) {
            let showData = ["<option value='' selected disabled>Choose branch here...</option>"];
            $.each(result, function(i, branch) {
                showData.push("<option value='" + branch.branch_id + "'>" + branch.branch_id + "</option>");
            });
            $("#branch").html(showData.join(''));
        }
    });
}

function selectedBrand() {
    // let brand = $("#brand").val();
    // let channel = $("#channels").val();

    $.ajax({
        url: "/store-drop-off",
        type: "POST",
        data: {
            '_token': $("#token").val()
        },
        success: function(result) {
            let showData = ["<option value='' selected disabled>Choose store drop-off here...</option>"];
            $.each(result, function(i, store) {
                if (store.store_drop_off_name) {
                    showData.push("<option value='" + store.store_drop_off_name + "'>" + store.store_drop_off_name + "</option>");
                }
            });
            $('#store_drop_off').empty().html(showData.join(''));
        }
    });
}

function showDropOff() {
    const channelPick = $('#channels').val();

    $('#store_drop_off').children(':not(:first)').remove();

    const filteredStores = channelPick == 4
        ? store_drop_off_list.filter(store => store.store_drop_off_name !== 'OPEN SOURCE')
        : store_drop_off_list;

    filteredStores.forEach(store => {
        $('#store_drop_off').append(`<option value="${store.store_drop_off_name}">${store.store_drop_off_name}</option>`);
    });

    const mode = $('#mode_of_return').val();
    const showDropOffDivs = mode === "STORE DROP-OFF";

    $('#store_drop_off_div, #branch_branch_dropoff_div').toggle(showDropOffDivs);
    $('#store_drop_off, #branch_dropoff').attr("required", showDropOffDivs);

    $('#store_drop_off, #branch_dropoff').val('');

    const modeOfReturnClass = showDropOffDivs ? "col-md-4" : "col-md-6";

    $('#mode_of_return_div, #store_drop_off_div, #branch_branch_dropoff_div')
        .removeClass('col-md-4 col-md-6')
        .addClass(modeOfReturnClass);
}

function showBranch() {
    $("#branch_dropoff").removeAttr("disabled");
    const dropOffStore = $("#store_drop_off").val();
    const location = $("#channels").val();

    $.ajax({
        url: "/branch-drop-off",
        type: "POST",
        data: {
            'drop_off_store': dropOffStore,
            'location': location,
            '_token': $("#token").val()
        },
        success: function(result) {
            let showData = ["<option value='' selected disabled>Choose branch drop-off here...</option>"];

            if (result.length === 0) {
                $('#branch_branch_dropoff_div').hide();
                $('#branch_dropoff').attr("required", false);
            } else {
                $('#branch_branch_dropoff_div').show();
                $('#branch_dropoff').attr("required", true);
                result.forEach((branch, i) => {
                    showData.push(`<option value="${branch.branch_id}">${branch.branch_id}</option>`);
                });
            }
            $("#branch_dropoff").html(showData.join(''));
        }
    });
}

function showCity() {
    $("#selectedcities").removeAttr("disabled");
    const provinces = $("#provinces").val();

    $.ajax({
        url: "/get-city",
        type: "POST",
        data: {
            'provinces': provinces,
            '_token': $("#token").val()
        },
        success: function(result) {
            const showData = ["<option value='' selected disabled>Choose city/municipality here...</option>"];

            result.forEach(city => {
                showData.push(`<option value="${city.citymunCode}">${city.citymunDesc}</option>`);
            });

            $("#selectedcities").html(showData.join(''));
        }
    });
}

function selectedModeOfPayment() {
    const paymentValue = $('#payment').val();

    if (paymentValue) {
        const modeOfRefund = JSON.stringify(paymentValue);

        if (!modeOfRefund.includes("4") || !modeOfRefund.includes("1")) {
            $("#bank_text").attr("hidden", true);
        }

        $.ajax({
            url: "/refund-mode",
            type: "POST",
            data: {
                'mode_of_refund': modeOfRefund,
                '_token': $("#token").val()
            },
            success: function(result) {
                const showData = ["<option value='' selected disabled>Choose mode of refund here...</option>"];

                result.forEach(refund => {
                    if (refund.mode_of_refund) {
                        showData.push(`<option value="${refund.mode_of_refund}">${refund.mode_of_refund}</option>`);
                    }
                });
            }
        });
    }
}

function validatePurchaseAmount() {
    const amount = $("#purchase_amount").val();
    const regexPurchaseAmount = /^\d{0,9}(\.\d{1,2})?$/;
    const regexNumber = /[^.\d]/g;
    const purchaseAmount = amount.replace(regexNumber, '');

    $("#purchase_amount").val(purchaseAmount);

    if (!regexPurchaseAmount.test(purchaseAmount)) {
        $("#purchase_amount_error").html("Maximum of 9 digits allowed with up to 2 decimal places.");
    } else if (regexNumber.test(amount)) {
        $("#purchase_amount_error").html("Must enter a valid number.");
    } else {
        $("#purchase_amount_error").html('');
    }
}

function otherProblemDetail() {
    const problemDetailArray = $('#problem_details').val();

    if (problemDetailArray && problemDetailArray.includes('26')) {
        const addInputField = `
            <label class='mb-0'>Other Problem Detail</label>
            <input type="text" class="form-control" name="problem_details_other" id="problem_details_other" placeholder="Other Problem Details" required onkeydown="removeSpecials(event)">
            <span class="error-content" id="problem_details_other_error" hidden>This field is required.*</span>
        `;
        $("#show_other_problem").html(addInputField);
    } else {
        $("#show_other_problem").empty();
    }
}

function otherItemsIncluded() {
    const itemsIncludedArray = $('#items_included').val();

    if (itemsIncludedArray && itemsIncludedArray.includes('1')) {
        const addInputFields = `
            <label class='mb-0'>Other Items Included</label>
            <input type="text" class="form-control" name="items_included_others" id="items_included_others" placeholder="Other Items Included" required>
            <span class="error-content" id="items_included_others_error" hidden>This field is required.*</span>
        `;
        $("#show_other_item").html(addInputFields);
    } else {
        $("#show_other_item").empty();
    }
}

function validateForm() {
    const fields = [
        { id: "#channels", error: "#purchase_location_error" },
        { id: "#selectedStores", error: "#selectedStores_error" },
        { id: "#branch", error: "#branch_error" },
        { id: "#mode_of_return", error: "#mode_of_return_error" },
        { id: "#store_drop_off", error: "#store_drop_off_error", condition: () => $("#mode_of_return").val() === "STORE DROP-OFF" },
        { id: "#branch_dropoff", error: "#branch_dropoff_error", condition: () => $("#mode_of_return").val() === "STORE DROP-OFF", customMessage: "This field is required.*" },
        { id: "#firstname", error: "#firstname_error" },
        { id: "#lastname", error: "#lastname_error" },
        { id: "#address_one", error: "#address_one_error" },
        { id: "#provinces", error: "#provinces_error" },
        { id: "#selectedcities", error: "#selectedcities_error" },
        { id: "#brgy", error: "#brgy_error" },
        { id: "#email_address", error: "#email_address_error" },
        { id: "#contact_number", error: "#contact_number_error", validate: val => /^(09|\+639)[0-9]{9}$/.test(val), invalidMessage: "Required format is (09/+639)123456789.*" },
        { id: "#order_no", error: "#order_no_error" },
        { id: "#purchase_date", error: "#purchase_date_error", validate: val => /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(val), invalidMessage: "Please follow the format yyyy/mm/dd .*" },
        { id: "#payment", error: "#payment_error" },
        { id: "#purchase_amount", error: "#purchase_amount_error", validate: val => /^\d{0,9}(\.\d{1,2})?$/.test(val), invalidMessage: "Maximum of 9 digits with up to 2 decimals allowed.*" },
        { id: "#item_desc", error: "#item_desc_error" },
        { id: "#brand", error: "#brand_error", customMessage: "This field is required.*" },
        { id: "#problem_details", error: "#ProblemDetail_error" },
        { id: "#items_included", error: "#items_included_error" },
    ];

    function isEmptyOrSpaces(str) {
        // return !str || str.trim() === "";
        return (str || "").trim().length === 0;
    }

    function validateField({ id, error, condition, validate, invalidMessage, customMessage }) {
        const value = $(id).val();
        const hasError = condition ? condition() && isEmptyOrSpaces(value) : isEmptyOrSpaces(value);

        if (hasError || (validate && !validate(value))) {
            $(error).attr("hidden", false).html(customMessage || invalidMessage || "");
        } else {
            $(error).attr("hidden", true).html("");
        }
    }

    fields.forEach(validateField);

    // Additional conditional checks
    if ($("#mode_of_return").val() === "STORE DROP-OFF") {
        if (isEmptyOrSpaces($("#store_drop_off").val()) || isEmptyOrSpaces($("#branch_dropoff").val())) {
            return false;
        }
    }

    const otherChecks = [
        { id: "#problem_details", otherId: "#problem_details_other", error: "#problem_details_other_error", valueToCheck: "26" },
        { id: "#items_included", otherId: "#items_included_others", error: "#items_included_others_error", valueToCheck: "1" }
    ];

    for (let { id, otherId, error, valueToCheck } of otherChecks) {
        if ($(id).val() && $(id).val().includes(valueToCheck) && isEmptyOrSpaces($(otherId).val())) {
            $(error).attr("hidden", false);
            return false;
        }
        $(error).attr("hidden", true);
    }

    return true;
}
