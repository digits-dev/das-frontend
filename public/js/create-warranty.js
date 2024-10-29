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
    let brand = $("#brand").val();
    let channel = $("#channels").val();

    // Displaying Mode of Refund
    if (brand === "APPLE" || brand === "BEATS") {
        // $("#bank_refund").attr("hidden", true);
    } else if (!isEmptyOrSpaces(channel) && channel == 4) {
        if (brand === "APPLE" || brand === "BEATS") {
            // $("#bank_refund").attr("hidden", true);
        } else {
            // $("#bank_refund").attr("hidden", false);
        }
    }

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



