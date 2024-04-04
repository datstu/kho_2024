@extends('layouts.default')
@section('content')

<style>
    .card-header a.btn-warning {
        color: #fff;
        float: right;
    }
</style>
<link href="{{ asset('public/css/pages/styleShippingOrders.css'); }}" rel="stylesheet">
<?php 
?>
<link href="{{ asset('public/css/pages/styleOrders.css'); }}" rel="stylesheet">



<hr>
<h1> test iframe</h1>
<iFrame src="https://donhang.ghn.vn/?order_code=G8NXFDUV" width="680" height="480" allowfullscreen></iFrame>
<hr>

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
          <div class="order-tracking-customer">
            <div class="info-order">
                <div class="container">
                    <div class="row">
                        <div class="p-t-0 p-b-0 m-b-20 col-lg-6 col-md-6 col-12">
                            <div class="header-order" style="">
                                <div class="title text-bold">THÔNG TIN ĐƠN HÀNG</div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Mã đơn hàng:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->order_code}}</span></span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Ngày lấy dự kiến:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->picktime}}</span></span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Ngày giao dự kiến:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->leadtime}}</span></span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Trạng thái hiện tại:</div>
                                        </div>
                                        <div class="value text-bold">
                                            <div class="status">{{ $data->order_info->status_name}}</div><br><span
                                                class="block-center block-left block-wrap"><span
                                                    class="text-normal"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-t-0 p-b-0 m-b-20 col-lg-6 col-md-6 col-12">
                            <div class="header-order" style="">
                                <div class="title text-bold">NGƯỜI NHẬN</div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Họ và tên:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->to_name}}</span></span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Điện thoại:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->to_phone}}</span></span></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="table-row">
                                        <div class="label">
                                            <div>Địa chỉ:</div>
                                        </div>
                                        <div class="value text-bold"><span class="block-center block-left block-wrap"><span
                                                    class="">{{$data->order_info->to_address}}</span></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="mt-1 mb-1">
                <div class="container">
                    <div class="order-history-container">
                        <div class="card-order-container">
                            <div class="card-order-header"><span>Lịch sử đơn hàng</span></div>
                            <div class="card-order-content">
                                <div class="responsive-table-log" style="">
                                    <?= $strLogs; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div></div>
    </div>
<script type="text/javascript">



function districtClick(name, id) {
    $("#districtFor").val(name);
    $("#listDistrict").removeClass('show');
    $("#listDistrict").addClass('hidden');
    $("#districtFor").attr('data-district-id', id);

    var _token = $("input[name='_token']").val();
    $.ajax({
        url: "{{ route('get-ward-by-id') }}",
        type: 'GET',
        data: {
            _token: _token,
            id
        },
        success: function(data) {
            if (data.length > 0) {
                console.log(data);
                let str = '';

                $.each(data, function(index, value) {
                    str += '<a onclick="wardClick(\'' + value.WardName + '\', ' + '\'' + value.WardCode +
                        '\')" class="option-ward" data-ward-name="' + value.WardCode +
                        '" data-ward-id="' + value.WardCode + '">' + value.WardName +
                        '</a>';
                });

                $('#listWard').html(str);
            }
        }
    });
}

function myFunctionDistrict() {
    document.getElementById("listDistrict").classList.toggle("show");

}

function myFunctionWard() {
    document.getElementById("listWard").classList.toggle("show");

}

function myFunctionProvince() {
    document.getElementById("listProvince").classList.toggle("show");

}

function filterFunctionDistrict() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("districtFor");
    filter = input.value.toUpperCase();
    div = document.getElementById("listDistrict");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function filterFunctionWard() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("wardFor");
    filter = input.value.toUpperCase();
    div = document.getElementById("listWard");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function filterFunctionProvince() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("provinceFor");
    filter = input.value.toUpperCase();
    div = document.getElementById("listProvince");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

$(".option-product-province").click(function() {
    let id = $(this).data("province-id");
    let name = $(this).data("province-name");
    $("#provinceFor").val(name);
    $("#provinceFor").attr('data-province-id', id);

    $("#listProvince").removeClass('show');
    $("#listProvince").addClass('hidden');

    var _token = $("input[name='_token']").val();

    $("#wardFor").removeAttr('data-ward-id');
    $("#wardFor").val('');
    $("#districtFor").removeAttr('data-district-id');
    $("#districtFor").val('');
    $.ajax({
        url: "{{ route('get-district-by-id') }}",
        type: 'GET',
        data: {
            _token: _token,
            id
        },
        success: function(data) {
            if (data.length > 0) {
                // console.log(data);
                let str = '';

                $.each(data, function(index, value) {
                    str += '<a onclick="districtClick(\'' + value.DistrictName + '\', ' +
                        value.DistrictID + ')" class="option-ward" data-ward-name="' + value
                        .DistrictName +
                        '" data-ward-id="' + value.DistrictID + '">' + value.DistrictName +
                        '</a>';
                });

                $('#listDistrict').html(str);
            }
        }
    });

});
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");

}


function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function deleteProduct(id, price) {


    var $input = $('#product-' + id).find('input');
    var count = parseInt($input.val());

    var $inputQty = $('#sum-qty').find('input');
    $inputQty.val(parseInt($inputQty.val()) - count);
    $inputQty.change();
    $('#product-' + id).parent().remove();

    let priceOld = +$("input[name='price']").attr("data-product-price");
        newPrice = priceOld - price*count;
        if (newPrice <= 0) {
            newPrice = 0
        }

        newPriceFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
        $("input[name='price']").attr('data-product-price', newPrice);
    return false;
}

function minus(id, price) {

    var $input = $('#product-' + id).find('input');
    var count = parseInt($input.val()) - 1;
    // count = count < 1 ? 1 : count;


    if (count >= 1) {
        $input.val(count);
        $input.change();

        var $inputQty = $('#sum-qty').find('input');
        $inputQty.val(parseInt($inputQty.val()) - 1);
        $inputQty.change();

        let priceOld = +$("input[name='price']").attr("data-product-price");
        newPrice = priceOld - price;
        newPriceFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
        $("input[name='price']").attr('data-product-price', newPrice);
    }

    return false;
}

function plus(id, price) {
    event.preventDefault();
    var $input = $('#product-' + id).find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.change();

    var $inputQty = $('#sum-qty').find('input');
    $inputQty.val(parseInt($inputQty.val()) + 1);
    $inputQty.change();
    
    let priceOld = +$("input[name='price']").attr("data-product-price");
    newPrice = priceOld + price;
    newPriceFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
        .format(newPrice,);
    $("input[name='price']").val(newPriceFormat);
    $("input[name='price']").attr('data-product-price', newPrice);
    return false;
}

$(document).ready(function() {

    $("#submit").click(function(e) {
        e.preventDefault();

        $('.loader').show();
        var _token      = $("input[name='_token']").val();
        var phone       = $("input[name='phone']").val();
        var name        = $("input[name='name']").val();
        var sex         = $("select[name='sex']").val();
        var province    = $("input[name='province']").attr('data-province-id');
        var district    = $("input[name='district']").attr('data-district-id');
        var ward        = $("input[name='ward']").attr('data-ward-id');
        var address     = $("input[name='address']").val();
        var qty         = $("input[name='sum-qty']").val();
        var assignSale  = $("select[name='assign-sale']").val();
        var note        = $("#note").val();
        var id          = $("input[name='id']").val();
        var status      = $("select[name='status']").val();
        
        let listProduct = [];
        $(".number input").each(function(index) {
            let productId = $(this).data("product_id");
            let val = Number($(this).val());
            listProduct.push({
                id: productId,
                val: val
            });
        });

        var isPriceSale = $("input[name='priceSale']:checked").val();
        var price = $("input[name='price']").val();
        if (isPriceSale == 'on') {
            isPriceSale = 1;
            price       = Number(price.replace(/[^0-9.-]+/g,""));
            
        } else {
            isPriceSale = 0;
            price = $("input[name='price']").attr("data-product-price");
        }

        $.ajax({
            url: "{{ route('save-orders') }}",
            type: 'POST',
            data: {
                _token: _token,
                phone,
                name: name,
                price: price,
                qty: qty,
                id,
                sex,
                products: JSON.stringify(listProduct),
                qty,
                price,
                address,
                province,
                district,
                ward,
                assignSale,
                isPriceSale,
                note,
                status
            },
            success: function(data) {
                console.log(data);
                if ($.isEmptyObject(data.errors)) {
                    $(".error_msg").html('');
                    $("#notifi-box").show();
                    $("#notifi-box").html(data.success);
                    $("#notifi-box").slideDown('fast').delay(5000).hide(0);
                } else {
                    $('.error_msg').text('');
                    let resp = data.errors;
                    for (index in resp) {
                        // console.log(index);
                        // console.log(resp[index]);
                        $("#" + index).html(resp[index]);
                    }
                }
                $('.loader').hide();
            }
        });

    });

    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        // blur: function() { 
        //   formatCurrency($(this), "blur");
        // }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val + 'đ';

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }

    $(".option-product").click(function() {
        let id = $(this).data("product-id");
        let name = $(this).data("product-name");
        let price = $(this).data("product-price");

        $("input[name='products[]']").val(id);

        $("#myDropdown").removeClass('show');
        $("#myDropdown").addClass('hidden');


        let priceOld = +$("input[name='price']").attr("data-product-price");
       
        newPrice = priceOld + price;
        console.log('newPrice', newPrice);

        newPriceFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            .format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
        $("input[name='price']").attr('data-product-price', newPrice);
    
    
        if ($('#product-' + id).length > 0) {
            var $input = $('#product-' + id).find('input');
            $input.val(parseInt($input.val()) + 1);
            $input.change();


        } else {
            let str = '<div id="product-' + id + '" class="text-right col-4 number product-' + id +
                '"><button onclick="minus(' + id +
                ', ' + price +
                ')" type="button" class=" minus">-</button><input class="qty-input" data-product_id="' +
                id + '" disabled type="text" value="1"/><button onclick="plus(' + id +
                ', ' + price +
                ')" type="button" class="plus">+</button></div>';
            str += '<button onclick="deleteProduct(' + id +
                ', ' + price +
                ')" type="button" class="col-2 del" >X</button>';
            $("#list-product-choose").append('<div class="row product mb-0">' +
                '<div class="col-6 name">' + name +
                '</div>' + str + '</div>');
        }

        var $inputQty = $('#sum-qty').find('input');
        $inputQty.val(parseInt($inputQty.val()) + 1);
        $inputQty.change();
    });

    $("#priceSaleFor").click(function() {
        if ($(this).is(':checked') ) {
            $("input[name='price']").prop("disabled", false);
            $("input[name='price']").focus();
            // $("input[name='price']").show();
            // let price =  $("input[name='price']").val();
            // console.log(price);
            // $("input[name='promotion']").attr("placeholder", price).blur();
        } else {
            // $("input[name='promotion']").hide();
            $("input[name='price']").prop("disabled", true);
            let price           = $("input[name='price']").attr("data-product-price");
            console.log(price);
            let newPriceFormat  = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                .format(price,);
            $("input[name='price']").val(newPriceFormat);
        }
    });

    //  setTimeout(function() { 
    //       $('.error_msg').text('');
    //   }, 5000);

});
</script>
@stop