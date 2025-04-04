@extends('layouts.default')
@section('content')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>
    .row {
        margin: unset;
    }
    .select2-container {
        width: 100% !important;
    }
    .selectedClass .select2-container {
        box-shadow: rgb(0, 123, 255) 0px 1px 1px 1px;
    }
    .select-assign, .select2-container--default .select2-selection--single {
        background-color: inherit !important;
        /* border: none; */
    }

</style>
<?php 
$listStatus = Helper::getListStatus();
$isLeadSale = Helper::isLeadSale(Auth::user()->role);
$checkAll = isFullAccess(Auth::user()->role);
$flagAccess = false;

$name = $phone = '';
if (isset($saleCare)) {
    $name = $saleCare->full_name;
    $phone = $saleCare->phone;
}
?>

<script src="{{asset('public/js/number-format/cleave.min.js')}}"></script>
<link href="{{ asset('public/css/pages/styleOrders.css')}}" rel="stylesheet">
<div class="body flex-grow-1">
    <form>
        {{ csrf_field() }}
        <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>
            @if(isset($order))
            <div class="card-body card-orders" style="padding:10px 0;">
                <input type="hidden" name="id" value="{{$order->id}}">
                <input value="{{$order->sale_care}}" class="hidden form-control" name="sale-care">

                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <label class="form-label" for="phoneFor">Số điện
                                    thoại</label>
                                <input value="{{$order->phone}}" class="form-control"
                                    name="phone" id="phoneFor" type="text">
                                <p class="error_msg" id="phone"></p>
                            </div>
                            <div class="col-sm-12 col-lg-6">
                                <label class="form-label" for="nameFor">Tên khách
                                    hàng</label>
                                <input value="{{$order->name}}" class="form-control"
                                    name="name" id="nameFor" type="text">
                                <p class="error_msg" id="name"></p>
                            </div>
                            <div class="col-sm-6 col-md-6 form-group">
                                <label class="form-label" for="distric-filter">Quận - Huyện<span class="required-input">(*)</span></label>
                                <select name="district" id="distric-filter" class="form-control">       
                                    <option value="-1">--Chọn quận/huyện--</option>
                                    @foreach ($listProvince as $item)
                                    <option <?= ($item['id'] == $order->district) ? "selected" : '';?> value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                                <p class="error_msg" id="district"></p>
                            </div>
                            <div class="col-sm-6 col-md-6 form-group">
                                <label class="form-label" for="ward-filter">Phường - xã<span class="required-input">(*)</span></label>
                                <select name="ward" id="ward-filter" class="form-control">
                                    @if (isset($listWard))
                                    @foreach ($listWard as $ward)
                                    <option <?= ($ward['id'] == $order->ward) ? "selected" : '';?> value="{{$ward['id']}}">{{$ward['name']}}</option>
                                    @endforeach
                                    
                                    @else
                                    <option value="-1">--Chọn phường/ xã--</option>
                                    @endif
                                </select>
                                <p class="error_msg" id="ward"></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="addressFor">Địa chỉ chi tiết</label>
                                <input value="{{$order->address}}" class="form-control"
                                    name="address" id="addressFor" type="text">
                                <label class="error_msg" id="address" for="addressFor"></label>
                            </div>

                            @if ($checkAll || $isLeadSale)
                            <div class="col-6">
                                <label class="form-label" >Chọn Sale</label>
                                <select class="form-control" name="assign-sale">

                                @if (isset($listSale))
                                @foreach ($listSale as $item)
                                    <option <?php echo ($item->id == $order->assign_user) ? 'selected' : '';?> value="{{$item->id}}">{{$item->real_name}}</option>
                                @endforeach
                                @endif

                                </select>
                                <p class="error_msg" id="price"></p>
                            </div>
                            @else 
                            <div class="col-6 hidden">
                                <select class="form-control" name="assign-sale">
                                    <option value="{{Auth::user()->id}}"></option>
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label for="note" class="form-label">Ghi chú:</label>
                            <textarea name="note" class="form-control" id="note" rows="4">{{$order->note}} </textarea>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="status">Trạng thái:</label>
                            
                            <select name="status" id="status"
                                class="form-control">
                            
                                @foreach ($listStatus as $k => $val)
                                <option <?= (int)$order->status == (int)$k ? 'selected' : ''; ?> value="{{$k}}">{{$val}}</option>
                                @endforeach

                            </select>
                            <p class="error_msg" id="sex"></p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-8">
                        <div class="row product-list-order">
                            <div class=" col-8">
                                <input class="hidden" name="products[]">
                                <div type="button" onclick="myFunction()" class=" btn btn-outline-secondary">Sản phẩm</div>
                                @if(isset($listProduct))

                                <div id="myDropdown"
                                    class="position-absolute dropdown-content">
                                    <input type="text" placeholder="Search.." id="myInput"
                                        onkeyup="filterFunction()">
                                    @foreach ($listProduct as $value)
                                    <a class="option-product"
                                        data-product-price="{{$value->price}}"
                                        data-product-name="{{$value->name}}"
                                        data-product-id="{{$value->id}}">{{$value->name}}</a>

                                    @endforeach
                                </div>

                                @endif
                                <p class="error_msg" id="qty"></p>
                            </div>
                            
                        </div>
                                
                        <div class="row">
                            <div class="col-12">
                                <div id="list-product-choose"></div>
                                <table class="table table-bordered table-line" style="margin-bottom:15px; font-size: 13px; ">
                                    <thead>
                                        <tr>
                                            
                                            <th colspan="1" class="text-center no-wrap col-spname" style="min-width: 155px">Tên sản phẩm</th>           
                                            <th colspan="1" class="text-center no-wrap">Đơn giá</th>
                                            <th colspan="1" class="text-center no-wrap">SL Tổng</th>
                                            <th colspan="1" class="text-center no-wrap">Thành tiền</th>
                                            <th colspan="1" class="text-center no-wrap"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list-product-choose">
                                        <?php $sumQty = $totalTmp = 0;
                                foreach (json_decode($order->id_product) as $item) {
                                    $product = getProductByIdHelper($item->id);
                                    
                                    if ($product) {
                                        $sumQty += $item->val;
                                        $totalTmp += $item->val * $product->price;
                            ?>

                <tr class="number dh-san-pham product-{{$product->id}}">
                    <td class="text-left">
                        <span class="no-combo">{{$product->name}}</span><br>
                    </td>
                    <td class="no-wrap" style="width: 80px">{{number_format($product->price)}}</td>
                    <td class="no-wrap" style="width: 45px">
                        <button onclick="minus({{$product->id}}, {{$product->price}})" type="button" class=" minus">-</button>
                    
                    <input class="qty-input" name="product-{{$product->id}}" data-product_id="{{$product->id}}" readonly type="text" value="{{$item->val}}"/>
                    <button onclick="plus({{$product->id}}, {{$product->price}})" type="button" class="plus">+</button>
                    </td>
                    <td class="no-wrap totalPriceProduct-{{$product->id}}" style="width: 30px">{{number_format($item->val * $product->price)}}</td>
                    <td class="text-center" style="width: 50px;">
                        <button onclick="deleteProduct({{$product->id}}, {{$product->price}})" type="button" class="col-2" ><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                            
                            <?php
                                        
                                    }
                                    
                                }
                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="no-wrap text-right" colspan="2">Tạm tính:</td>
                                            <td class="no-wrap text-center" colspan="1">
                                                <input style="text-align: right;" value="{{$sumQty}}" name="sum-qty" class="form-control" readonly
                                                type="number"></td>
                                            <td colspan="1" class="no-wrap total-tmp">{{number_format($totalTmp)}}</td>
                                            <td colspan="1"></td>
                                        </tr>
                                    
                                        <tr>
                                            <td class="no-wrap text-right" colspan="3">Tổng đơn:
                                                {{-- <br> --}}
                                                <input {{ $order->is_price_sale ? 'checked' : '' }} name="priceSale" type="checkbox" id="xxx" class="form-check-input">
                                                <label class="form-label" for="xxx">Khuyến mãi</label>
                                            </td>
                                            <td class="no-wrap" colspan="1">
                                                <input {{ $order->is_price_sale ? '' : 'readonly' }} value="{{number_format($order->total)}}"
                                                    class="price_class form-control" name="price"
                                                    id="priceFor"
                                                    type="text"
                                                    data-product-price={{$totalTmp}} /> 
                                            </td>
                                            <td></td>
                                        </tr>
                                        
                                    </tfoot>
                                </table>
                            
                            </div>
                    <div class="col-12">
                        <div id="list-product-choose">

                            <?php 
                                foreach (json_decode($order->id_product) as $item) {
                                    $product = getProductByIdHelper($item->id);
                                    if ($product) {
                            ?>

                            
                            <?php
                                        
                                    }
                                    
                                }
                            ?>
                            
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" style="text-align: end;">
                    <button id="submit" class="mb-1 btn btn-primary create-bill">Lưu</button>
                </div>
            </div>
            @else
            {{-- <div class=""><strong>Thêm đơn hàng mới</strong></div> --}}

            <div class="card-body card-orders" style="padding:10px 0;">
                <div class="body flex-grow-1">
                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <div class="row">
                                <?php $saleCareId = request()->get('saleCareId');?>
                                
                                <input value="<?= ($saleCareId) ?: $saleCareId ?>" class="hidden form-control" name="sale-care">
                                <div class="col-sm-12 col-lg-6">
                                    <label class="form-label" for="phoneFor">Số điện thoại<span class="required-input">(*)</span></label>
                                    <input autofocus placeholder="0973409613" class="form-control" name="phone"
                                        id="phoneFor" type="text" value="{{$phone}}">
                                    <p class="error_msg" id="phone"></p>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <label class="form-label" for="nameFor">Tên khách hàng<span class="required-input">(*)</span></label>
                                    <input placeholder="Họ và tên" class="form-control" name="name"
                                        id="nameFor" type="text" value="{{$name}}">
                                    <p class="error_msg" id="name"></p>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <label class="form-label" for="distric-filter">Quận - Huyện<span class="required-input">(*)</span></label>
                                    <select name="district" id="distric-filter" class="form-control">       
                                        <option value="-1">--Chọn quận/huyện--</option>
                                        @foreach ($listProvince as $item)
                                        <option value="{{$item['id']}}">{{$item['name']}}</option>

                                        @endforeach
                                    </select>
                                    <p class="error_msg" id="district"></p>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 form-group">
                                    <label class="form-label" for="ward-filter">Phường - xã<span class="required-input">(*)</span></label>
                                    <select name="ward" id="ward-filter" class="form-control">       
                                        <option value="-1">--Chọn phường/ xã--</option>
                                    </select>
                                    <p class="error_msg" id="ward"></p>
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="addressFor">Địa chỉ chi tiết<span class="required-input">(*)</span></label>
                                    <input placeholder="số nhà - tên đường/ thôn/ ấp" class="form-control" name="address"
                                        id="addressFor" type="text">
                                    <label class="error_msg" id="address" for="addressFor"></label>
                                </div>

                                <?php $checkAll = isFullAccess(Auth::user()->role);?>
                                @if ($checkAll || $isLeadSale)
                                    <div class="col-lg-6">
                                        <label class="form-label">Chọn Sale:</label>
                                        <select class="form-control" name="assign-sale" >

                                        @if (isset($listSale))
                                        @foreach ($listSale as $item)
                                            <option value="{{$item->id}}">{{$item->real_name}}</option>
                                        @endforeach
                                        @endif

                                        </select>
                                        <p class="error_msg" id="price"></p>
                                    </div>
                                @else 
                                    <div class="col-6 hidden">
                                        <select class="form-control" name="assign-sale">
                                            <option value="{{Auth::user()->id}}"></option>
                                        </select>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <label for="note" class="form-label">Ghi chú:</label>
                                    <textarea name="note" class="form-control" id="note" rows="4"></textarea>
                                    <p></p>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label class="form-label" for="statusFor">Trạng thái:</label>
                                    <select name="status" id="statusFor"
                                        class="form-control">

                                        @foreach ($listStatus as $k => $val)
                                            <option value="{{$k}}">{{$val}}</option>
                                        @endforeach

                                    </select>
                                    <p class="error_msg" id="sex"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-8">
                            <div class="row product-list-order">
                                <div class="col-8">
                                    <input class="hidden" name="products[]">
                                    <div type="button" onclick="myFunction()" class="btn btn-outline-secondary">--Chọn sản phẩm--<span class="required-input">(*)</span> ⮟</div>
                                    @if(isset($listProduct))

                                    <div id="myDropdown" class="position-absolute dropdown-content">
                                        <input type="text" placeholder="--Tìm sản phẩm--" id="myInput"
                                            onkeyup="filterFunction()">
                                        @foreach ($listProduct as $value)
                                        <a class="option-product" data-product-name="{{$value->name}}"
                                            data-product-id="{{$value->id}}"
                                            data-product-price="{{$value->price}}"
                                            >{{$value->name}}</a>

                                        @endforeach
                                    </div>

                                    @endif
                                    <p class="error_msg" id="qty"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div id="list-product-choose"></div>
                                    <table class="table table-bordered table-line" style="margin-bottom:15px; font-size: 13px; ">
                                        <thead>
                                            <tr>
                                                <th colspan="1" class="text-center no-wrap col-spname" style="min-width: 155px">Tên sản phẩm</th>           
                                                <th colspan="1" class="text-center no-wrap">Đơn giá</th>
                                                <th colspan="1" class="text-center no-wrap">SL Tổng</th>
                                                <th colspan="1" class="text-center no-wrap">Thành tiền</th>
                                                <th colspan="1" class="text-center no-wrap"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list-product-choose">
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="no-wrap text-right" colspan="2">Tạm tính:</td>
                                                <td class="no-wrap text-center" colspan="1">
                                                    <input style="text-align: right;" value="0" name="sum-qty" class="form-control" readonly
                                                    type="number"></td>
                                                <td class="no-wrap total-tmp" colspan="1">0</td>
                                                <td colspan="1"></td>
                                            </tr>
                                        
                                            <tr>
                                                <td class="no-wrap text-right" colspan="3">Tổng đơn:
                                                    {{-- <br> --}}
                                                    <input name="priceSale" type="checkbox" id="xxx" class="form-check-input">
                                                    <label class="form-label" for="xxx">Khuyến mãi</label>
                                                </td>
                                                <td class="no-wrap" colspan="1">
                                                    <input readonly value="0"
                                                        class="price_class form-control" name="price"
                                                        id="priceFor"
                                                        type="text"
                                                        data-product-price=0 /> 
                                                </td>
                                                <td colspan="1"></td>
                                            </tr>
                                            
                                        </tfoot>
                                    </table>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" style="text-align: end;">
                    <button id="submit" class="mb-1 btn btn-primary create-bill">Chốt đơn</button>
                </div>
            </div>
            @endif
            
        </div>

    </form>
    {{-- <div class="row text-right">
        <div><button class="refresh btn btn-info">Refresh</button></div>
    </div> --}}
    <span class="loader hidden">
        <img src="{{asset('public/images/rocket.svg')}}" alt="">
    </span>

</div>
<script type="text/javascript">

function wardClick(name, id) {
    $("#wardFor").val(name);
    $("#listWard").removeClass('show');
    $("#listWard").addClass('hidden');
    $("#wardFor").attr('data-ward-id', id);
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
    var $input = $('input[name="product-' + id + '"');
    var count = parseInt($input.val());

    var $inputQty = $('input[name="sum-qty"');
    $inputQty.val(parseInt($inputQty.val()) - count);
    $inputQty.change();
    $('#product-' + id).parent().remove();
    $('tr.product-' + id).remove();
    let priceOld = +$("input[name='price']").attr("data-product-price");
        newPrice = priceOld - price*count;
        if (newPrice <= 0) {
            newPrice = 0
        }

        newPriceFormat = new Intl.NumberFormat().format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
        $("input[name='price']").attr('data-product-price', newPrice);
        $('.total-tmp').html(newPriceFormat);
    return false;
}

function minus(id, price) {

    var $input = $('input[name="product-' + id + '"');
    var count = parseInt($input.val()) - 1;
    // count = count < 1 ? 1 : count;


    if (count >= 1) {
        $input.val(count);
        $input.change();

        var $inputQty = $('input[name="sum-qty"');
        $inputQty.val(parseInt($inputQty.val()) - 1);
        $inputQty.change();

        let priceOld = +$("input[name='price']").attr("data-product-price");
        newPrice = priceOld - price;
        newPriceFormat = new Intl.NumberFormat().format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
        $("input[name='price']").attr('data-product-price', newPrice);
        $('.total-tmp').html(newPriceFormat);

        var qty = $input.val();
        totalPriceByProduct = price * qty;
        totalPriceByProductFM = new Intl.NumberFormat().format(totalPriceByProduct,);
        $('.totalPriceProduct-' + id).html(totalPriceByProductFM);
    }

    return false;
}

function plus(id, price) {
    event.preventDefault();
    var $input = $('input[name="product-' + id + '"');
    $input.val(parseInt($input.val()) + 1);
    $input.change();

    var $inputQty = $('input[name="sum-qty"');
    $inputQty.val(parseInt($inputQty.val()) + 1);
    $inputQty.change();
    
    let priceOld = +$("input[name='price']").attr("data-product-price");
    newPrice = priceOld + price;
    newPriceFormat = new Intl.NumberFormat().format(newPrice,);
    $("input[name='price']").val(newPriceFormat);
    $("input[name='price']").attr('data-product-price', newPrice);
    $('.total-tmp').html(newPriceFormat);

    var qty = $input.val();
    totalPriceByProduct = price * qty;
    totalPriceByProductFM = new Intl.NumberFormat().format(totalPriceByProduct,);
    $('.totalPriceProduct-' + id).html(totalPriceByProductFM);
    return false;
}

$(document).ready(function() {

    $("#submit").click(function(e) {
        e.preventDefault();

        $('.body .loader').show();
        $('.body .row').css("opacity", "0.5");
        $('.body .row').css("position", "relative");

        var _token      = $("input[name='_token']").val();
        var phone       = $("input[name='phone']").val();
        var name        = $("input[name='name']").val();
        var sex         = $("select[name='sex']").val();
        var ward        = $("input[name='ward']").attr('data-ward-id');
        var address     = $("input[name='address']").val();
        var qty         = $("input[name='sum-qty']").val();
        var assignSale  = $("select[name='assign-sale']").val();
        var note        = $("#note").val();
        var id          = $("input[name='id']").val();
        var status      = $("select[name='status']").val();
        var saleCareId  = $("input[name='sale-care']").val();
        var district  = $("select[name='district']").val();
        var ward  = $("select[name='ward']").val();

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
            price = price.replace(/[^0-9]+/g, "")
        } else {
            isPriceSale = 0;
            price = $("input[name='price']").attr("data-product-price");
        }

        // saleCare  = saleCare ? saleCare : 0;
        // var url = "<?php echo URL::to('/save-orders/" + saleCare + "'); ?>";
        console.log(assignSale)
        $.ajax({
            url: "{{route('save-orders')}}",
            type: 'POST',
            data: {
                _token: _token,
                saleCareId,
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
                    if (data.link) {
                        window.location.href = data.link;
                    }
                } else {
                    $('.error_msg').text('');
                    let resp = data.errors;
                    for (index in resp) {
                        // console.log(index);
                        // console.log(resp[index]);
                        $("#" + index).html(resp[index]);
                    }
                }

                // $('.body').css("opacity", '1');
                // $('.loader').hide();

                $('.body .loader').hide();
                $('.body .row').css("opacity", "1");
                $('.body .row').css("position", "relative");

            }
        });

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
        let productPrice = $(this).data("product-price");
        productPriceFM = new Intl.NumberFormat().format(productPrice,);

        $("input[name='products[]']").val(id);

        $("#myDropdown").removeClass('show');
        $("#myDropdown").addClass('hidden');
    
        var inputQty = $('input[name="sum-qty"');
        inputQty.val(parseInt(inputQty.val()) + 1);
        inputQty.change();

        var totalPriceByProduct = 0;

        if ($('.product-' + id).length <= 0) {
            var newStr = '';
            newStr += `
                <tr class="number dh-san-pham product-` + id +`">
                    
                    <td class="text-left">
                        <span class="no-combo">` + name + `</span><br>
                    </td>
                    <td class="no-wrap" style="width: 80px">` + productPriceFM + `</td>
                    <td class="no-wrap" style="width: 45px">
                        <button onclick="minus(` + id +
                    ', ' + productPrice + `)" type="button" class=" minus">-</button>
                    
                    <input class="qty-input" name="product-` + id + `" data-product_id="` +
                    id + `" readonly type="text" value="1"/>
                    <button onclick="plus(` + id +
                    ', ' + productPrice + `)" type="button" class="plus">+</button>
                    </td>
                    <td class="no-wrap totalPriceProduct-` + id + `" style="width: 30px">` + productPriceFM + `</td>
                    <td class="text-center" style="width: 50px;">
                        <button onclick="deleteProduct(` + id +
                    ', ' + productPrice + `)" type="button" class="col-2" ><i class="fa fa-trash"></i></button>
                    </td>
                    </tr>`;
            $(".list-product-choose").append(newStr);

            // let str = '<div class="text-right col-4 number product-' + id +
            //     '"><button onclick="minus(' + id +
            //     ', ' + price +
            //     ')" type="button" class=" minus">-</button><input class="qty-input" name="product-' + id + '" data-product_id="' +
            //     id + '" readonly type="text" value="1"/><button onclick="plus(' + id +
            //     ', ' + price +
            //     ')" type="button" class="plus">+</button></div>';
            // str += '<button onclick="deleteProduct(' + id +
            //     ', ' + price +
            //     ')" type="button" class="col-2 del" >X</button>';
            // $("#list-product-choose").append('<div class="row product mb-0">' +
            //     '<div class="col-6 name">' + name +
            //     '</div>' + str + '</div>');
            totalPriceByProduct = productPrice;
        } else {
            var $input = $('input[name="product-' + id + '"');
            $input.val(parseInt($input.val()) + 1);
            $input.change();

            var qty = $input.val();
            totalPriceByProduct = productPrice * qty;
            totalPriceByProductFM = new Intl.NumberFormat().format(totalPriceByProduct,);
            $('.totalPriceProduct-' + id).html(totalPriceByProductFM);

        }

        priceOld = $("input[name='price']").attr("data-product-price");
        
        // console.log('priceOld', priceOld)
        // console.log('productPrice', productPrice)
        newPrice = parseInt(priceOld) + productPrice;
        newPriceFormat = new Intl.NumberFormat().format(newPrice,);
            // newPriceFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
            // .format(newPrice,);
        $("input[name='price']").val(newPriceFormat);
       
        $("input[name='price']").attr('data-product-price', newPrice);
        
        $('.total-tmp').html(newPriceFormat);
 
    });

    // $("priceSaleFor").click(function() {
        $("input[name='priceSale']").click(function() {
        
        console.log('is clicked')
        if ($(this).is(':checked')) {
            $("input[name='price']").prop("readonly", false);
            $("input[name='price']").focus();
        } else {
            // $("input[name='promotion']").hide();
            $("input[name='price']").prop("readonly", true);
            let price           = $("input[name='price']").attr("data-product-price");
            console.log(price);
            let newPriceFormat  = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                .format(price,);
            $("input[name='price']").val(newPriceFormat);
        }
    });

    $('.refresh').click(function() {
        location.reload(true)
    });

});

document.querySelectorAll('.price_class').forEach(inp => new Cleave(inp, {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
}));

</script>
<script>
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results) {
        return results[1];
    }
    return 0;
}

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    $(function() {
        $('#distric-filter').select2();
        $('#ward-filter').select2();
    });
</script>

<script>
    $(document).ready(function() {
        var baseLink = location.href.slice(0,location.href.lastIndexOf("/"));
        // var link = baseLink + '/public/json/simplified_json_generated_data_vn_units.json';
        var link = baseLink + '/public/json/local.json';
        var listProvince = fetch(link)
            .then((res) => {
                if (!res.ok) {
                    throw new Error
                        (`HTTP error! Status: ${res.status}`);
                }
                return res.json();
            });

        $('#distric-filter').on('change', function() {
            var id = this.value;
            var _token  = $("input[name='_token']").val();
            $.ajax({
                url: "{{ route('get-ward-by-id-distric') }}",
                type: 'GET',
                data: {
                    _token: _token,
                    id
                },
                success: function(data) {
                    if (data.length > 0) {
                        
                        let str = '';
                        $.each(data, function(index, value) {
                            console.log(value);
                            str += `<option value="` +value.id+ `">` + value.name + `</option>`;
                            
                        });

                        $('#ward-filter').html(str);
                        $('#ward-filter').select2();
                    }
                }
            });
        })
    });
</script>
@stop