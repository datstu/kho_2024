@extends('layouts.default')
@section('content')

<style>
    .card-ghn {
        padding-top: 10px !important;
    }
    .card-ghn:hover {
        /* opacity: 0.1;    */
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

    .choose-shipping {
        /* height:100px;
        width :100px;
        background:red; */
        /* display:block;
        opacity:1;
        transition : all .3s;
        -wekit-transition : all .3s;
        -moz-transition : all .3s; */
    }
    .choose-shipping.active .card-ghn{
        /* opacity: 0; */
        border: 2px solid rgba(0, 0, 0, 0.35);
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }
</style>
<link href="{{ asset('public/css/pages/styleOrders.css'); }}" rel="stylesheet">
<div class="body flex-grow-1 p-3">
    <div class="container">
        <div class="row choose-shipping">
            <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                <label for="" style="font-style: italic">Đơn vị vận chuyển</label>
                <select name="typeDate" id="typeDate-filter" class="border-select-box-se">       
                    {{-- <option value="999">-------</option> --}}
                    <option value="1">Giao hàng nhanh</option>
                </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 form-group">
                <label for="" style="font-style: italic">DS cửa hàng</label>
                <select name="shop_id" class="border-select-box-se">       
                    {{-- <option value="999">-------</option> --}}
                    <option value="1">Shop 2kg</option>
                    <option value="1">Giao 5kg</option>
                    <option value="1">Giao 10kg</option>
                </select>
            </div>   
        </div>
        <div class="row content-ship">
            <div class="col-xs-12 col-sm-8 col-md-6 form-group">
                <div class="from-address w-50p m-r-12">
                    <div class="title-card">Bên gửi</div>
                    <div class="clo-F26522 fz-14"><span>0986987791 Đạm tôm, Tricho, Aplus</span> <i class="fa fa-phone m-l-8"></i>
                        <span>0986987791</span></div><div class="clo-404040 fz-14 fw-500">số 4 nguyễn thị chiên nhánh 2</div>
                        <div class="clo-F26522 fw-500 border-bottom-1 w-fit-content pointer">
                            <i>Sửa địa chỉ gửi hàng</i></div><br>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                <label for="" style="font-style: italic">Ca lấy hàng</label>
                <select name="pick_shift" class="border-select-box-se">       
                    {{-- <option value="999">-------</option> --}}
                    <option value="1">Sáng nay</option>
                    <option value="1">chiều nay</option>
                    <option value="1">Sáng mai</option>
                </select>
            </div>

            <div class="col-xs-12 col-sm-8 col-md-6 form-group">
                <div class="from-address w-50p m-r-12">
                    <div class="title-card">Bên nhận</div>
                    <div class="clo-F26522 fz-14"><span>0986987791 Đạm tôm, Tricho, Aplus</span> <i class="fa fa-phone m-l-8"></i>
                        <span>0986987791</span></div><div class="clo-404040 fz-14 fw-500">số 4 nguyễn thị chiên nhánh 2</div>
                        <div class="clo-F26522 fw-500 border-bottom-1 w-fit-content pointer">
                            <i>Sửa địa chỉ gửi hàng</i></div><br>
                </div>
            </div>
        </div>
        
</div>
<script type="text/javascript">
function wardClick(name, id) {
    $("#wardFor").val(name);
    $("#listWard").removeClass('show');
    $("#listWard").addClass('hidden');
    $("#wardFor").attr('data-ward-id', id);
}


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

$(document).ready(function() {


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
            $("input[name='id-shpping-has']").show();
            $("input[name='id-shpping-has']").focus();
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

    $("input[name='hasShipping']").click(function() {
        if ($(this).val() == 'yes') {
            $("input[name='id_shipping_has']").show();
            $("input[name='id_shipping_has']").focus();
        } else {
            $("input[name='id_shipping_has']").hide();
        }
    });
    
    $("#nextGHN").click(function() {
        console.log('click next ghn');
        $('.choose-shipping').toggleClass('active');
        // $('.choose-shipping').hide(0).delay(5000);
         $('.content-ship').show();
         $('input[name="vendor_ship"]').val('GHN');
        
    });

});
</script>
@stop