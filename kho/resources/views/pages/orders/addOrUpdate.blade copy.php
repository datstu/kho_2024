@extends('layouts.default')
@section('content')


<link href="{{ asset('public/css/pages/styleOrders.css'); }}" rel="stylesheet">
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <div class="row">
            <div id="notifi-box" class="hidden alert alert-success print-error-msg">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            </div>

            <div class="mt-1 col-12">
                <div class="card mb-4">
                    
                    @if(isset($order))
                  
                    <div class="card-header"><strong>Cập nhật đơn hàng #{{$order->id}} - {{date_format($order->created_at,"d-m-Y ")}}</span></div>
                    <div class="card-body card-orders">
                        <div class="example">
                            <div class="body flex-grow-1">
                                <div class="tab-content rounded-bottom">
                                    <form>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$order->id}}">
                                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                            <div class="row">
                                                <div class="col-7">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <label class="form-label" for="phoneFor">Số điện
                                                                thoại</label>
                                                            <input value="{{$order->phone}}" class="form-control"
                                                                name="phone" id="phoneFor" type="text">
                                                            <p class="error_msg" id="phone"></p>
                                                        </div>
                                                        <div class="col-7">
                                                            <label class="form-label" for="nameFor">Tên khách
                                                                hàng</label>
                                                            <input value="{{$order->name}}" class="form-control"
                                                                name="name" id="nameFor" type="text">
                                                            <p class="error_msg" id="name"></p>
                                                        </div>
                                                        <div class="col-2">
                                                            <label class="form-label" for="sexFor">Giới tính</label>
                                                            <select placeholder="Nam" theme="google" name="sex"
                                                                id="sexFor" class="form-control">
                                                                <option <?= $order->sex == 0 ? 'checked' : ''; ?> value="0">Nam</option>
                                                                <option <?= $order->sex == 1 ? 'checked' : ''; ?> value="1">Nữ</option>
                                                            </select>
                                                            <p class="error_msg" id="sex"></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-4 position-relative">
                                                            <label class="form-label" for="provinceFor">Tỉnh/Thành
                                                                Phố</label>
                                                            <input value="<?= getProvinceNameHelper($order->province)?>"
                                                                onkeyup="filterFunctionProvince()"
                                                                onclick="myFunctionProvince()" class="form-control"
                                                                name="province" id="provinceFor" type="text"
                                                                data-province-id="{{$order->province}}"
                                                                >
                                                            <p class="error_msg" id="province"></p>
                                                           
                                                            @if(isset($provinces))
                                                            <div id="listProvince"
                                                                class="position-absolute myDropdown dropdown-content">
                                                                @foreach ($provinces as $value)
                                                                <a class="option-product-province"
                                                                    data-province-name="{{$value->ProvinceName}}"
                                                                    data-province-id="{{$value->ProvinceID}}">{{$value->ProvinceName}}</a>

                                                                @endforeach
                                                            </div>

                                                            @endif
                                                        </div>
                                                        <div class="col-4 position-relative">
                                                            <label class="form-label"
                                                                for="districtFor">Quận/Huyện</label>
                                                            <input value="<?= getDistrictNameHelper($order->district, $order->province )?>"
                                                                onkeyup="filterFunctionDistrict()"
                                                                onclick="myFunctionDistrict()" class="form-control"
                                                                name="district" id="districtFor" type="text"
                                                                data-district-id="{{$order->district}}"
                                                                >
                                                            <p class="error_msg" id="district"></p>
                                                            <div id="listDistrict" class="position-absolute dropdown-content">
                                                            @if(isset($listDistrict))
                                                                @foreach ($listDistrict as $value)
                                                                    <a class="option-product-district"
                                                                        onclick="districtClick('{{$value->DistrictName}}',{{$value->DistrictID}})"
                                                                        data-district-name="{{$value->DistrictName}}"
                                                                        data-district-id="{{$value->DistrictID}}">{{$value->DistrictName}}</a>

                                                                @endforeach
                                                            @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-4 position-relative">
                                                            <label class="form-label" for="wardFor">Xã/Phường</label>
                                                            <input value="<?= getWardNameHelper($order->ward, $order->district)?>"
                                                                onkeyup="filterFunctionWard()"
                                                                onclick="myFunctionWard()" class="form-control"
                                                                name="ward" id="wardFor" type="text"
                                                                data-ward-id="{{$order->ward}}"
                                                                >
                                                            <p class="error_msg" id="ward"></p>
                                                            <div id="listWard"
                                                                class="position-absolute dropdown-content">
                                                            @if(isset($listWard))
                                                                @foreach ($listWard as $value)
                                                                    <a class="option-product-ward"
                                                                        onclick="wardClick('{{$value->WardName}}',{{$value->WardCode}})"
                                                                        data-district-name="{{$value->WardName}}"
                                                                        data-district-id="{{$value->WardCode}}">{{$value->WardName}}</a>

                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        </div>
                                                        <div class="col-8">
                                                            <label class="form-label" for="addressFor">Địa
                                                                chỉ/đường</label>
                                                            <input value="{{$order->address}}" class="form-control"
                                                                name="address" id="addressFor" type="text">
                                                            <p class="error_msg" id="address"></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="form-label" for="priceFor">Tổng tiền</label>
                                                            <input {{ $order->is_price_sale ? '' : 'disabled' }} value="{{number_format($order->total)}} đ" value="" data-type="currency"
                                                                class="form-control" name="price"
                                                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" id="priceFor"
                                                                type="text"
                                                                data-product-price={{$order->total}}>
                                                            <label class="form-label" for="priceSaleFor">
                                                                <input {{ $order->is_price_sale ? 'checked' : '' }} name="priceSale" type="checkbox" id="priceSaleFor"> Giá khuyến mãi
                                                                
                                                            </label>
                                                            <p class="error_msg" id="price"></p>
                                                        </div>
                                            <?php $checkAll = isFullAccess(Auth::user()->role);?>
                                            @if ($checkAll)
                                                <div class="col-4">
                                                    <label class="form-label" for="assignSaleFor">Chọn Sale</label>
                                                    <select class="form-control" name="assign-sale" id="">

                                                    @if (isset($listSale))
                                                    @foreach ($listSale as $item)
                                                        <option <?php echo ($item->id == $order->assign_user) ? 'selected' : '';?> value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                    @endif

                                                    </select>
                                                    <p class="error_msg" id="price"></p>
                                                </div>
                                            @else 
                                            <div class="col-6 hidden">
                                                <label class="form-label" for="assignSaleFor">Chọn Sale</label>
                                                <select class="form-control" name="assign-sale" id="">
                                                    <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                                                </select>
                                                <p class="error_msg" id="price"></p>
                                            </div>
                                            @endif
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="row product-list-order">
                                                        <div class="position-relative col-8 d-flex align-items-center">
                                                            <input class="hidden" name="products[]">
                                                            <button type="button" onclick="myFunction()"
                                                                class=" btn btn-outline-secondary">Sản phẩm</button>
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

                                                        <div id="sum-qty" class=" col-4">
                                                            <label class="form-label" for="qtyFor">Tổng số lượng</label>
                                                            <input value="{{$order->qty}}" name="sum-qty" class="form-control" disabled
                                                                type="number">
                                                            <p class="error_msg" id="qty"></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="list-product-choose">

                                                                <?php 
                                                                    foreach (json_decode($order->id_product) as $item) {
                                                                        $product = getProductByIdHelper($item->id);
                                                                        if ($product) {
                                                                ?>

                                                                <div class="row product mb-0">
                                                                    <div class="col-6 name">{{$product->name}}</div>
                                                                    <div id="product-{{$product->id}}" class="text-right col-4 number product-4">
                                                                        <button onclick="minus({{$product->id}}, {{$product->price}})" type="button" class=" minus">-</button>
                                                                        <input value="{{$item->val}}" class="qty-input" data-product_id="{{$product->id}}" disabled="" type="text" value="1">
                                                                        <button onclick="plus({{$product->id}}, {{$product->price}})" type="button" class="plus">+</button>
                                                                    </div>
                                                                    <button onclick="deleteProduct({{$product->id}}, {{$product->price}})" type="button" class="col-2 del">X</button>
                                                                </div>
                                                                <?php
                                                                           
                                                                        }
                                                                       
                                                                    }
                                                                ?>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row products">
                                            </div>
                                            <div class="loader hidden">
                                                <img src="{{asset('public/images/loader.gif')}}" alt="">
                                            </div>
                                            <button id="submit" class="btn btn-primary">Cập nhật </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card-header"><strong>Thêm đơn hàng mới</span></div>
            <div class="card-body card-orders">
                <div class="example">
                    <div class="body flex-grow-1">
                        <div class="tab-content rounded-bottom">
                            <form>
                                {{ csrf_field() }}
                                <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label class="form-label" for="phoneFor">Số điện thoại</label>
                                                    <input placeholder="0973409613" class="form-control" name="phone"
                                                        id="phoneFor" type="text">
                                                    <p class="error_msg" id="phone"></p>
                                                </div>
                                                <div class="col-7">
                                                    <label class="form-label" for="nameFor">Tên khách hàng</label>
                                                    <input placeholder="Đạt Admin" class="form-control" name="name"
                                                        id="nameFor" type="text">
                                                    <p class="error_msg" id="name"></p>
                                                </div>
                                                <div class="col-2">
                                                    <label class="form-label" for="sexFor">Giới tính</label>
                                                    <select placeholder="Nam" theme="google" name="sex" id="sexFor"
                                                        class="form-control">
                                                        <option value="0">Nam</option>
                                                        <option value="1">Nữ</option>
                                                    </select>
                                                    <p class="error_msg" id="sex"></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 position-relative">
                                                    <label class="form-label" for="provinceFor">Tỉnh/Thành Phố</label>
                                                    <input placeholder="--chọn tỉnh/thành phố--"
                                                        onkeyup="filterFunctionProvince()"
                                                        onclick="myFunctionProvince()" class="form-control"
                                                        name="province" id="provinceFor" type="text">
                                                    <p class="error_msg" id="province"></p>

                                                    @if(isset($provinces))

                                                    <div id="listProvince"
                                                        class="position-absolute myDropdown dropdown-content">

                                                        @foreach ($provinces as $value)
                                                        <a class="option-product-province"
                                                            data-province-name="{{$value->ProvinceName}}"
                                                            data-province-id="{{$value->ProvinceID}}">{{$value->ProvinceName}}</a>

                                                        @endforeach
                                                    </div>

                                                    @endif
                                                </div>
                                                <div class="col-4 position-relative">
                                                    <label class="form-label" for="districtFor">Quận/Huyện</label>
                                                    <input placeholder="--chọn quận/huyện--"
                                                        onkeyup="filterFunctionDistrict()"
                                                        onclick="myFunctionDistrict()" class="form-control"
                                                        name="district" id="districtFor" type="text">
                                                    <p class="error_msg" id="district"></p>
                                                    <div id="listDistrict" class="position-absolute dropdown-content">
                                                    </div>
                                                </div>
                                                <div class="col-4 position-relative">
                                                    <label class="form-label" for="wardFor">Xã/Phường</label>
                                                    <input placeholder="--chọn xã/phường--"
                                                        onkeyup="filterFunctionWard()" onclick="myFunctionWard()"
                                                        class="form-control" name="ward" id="wardFor" type="text">
                                                    <p class="error_msg" id="ward"></p>
                                                    <div id="listWard" class="position-absolute dropdown-content"></div>
                                                </div>
                                                <div class="col-8">
                                                    <label class="form-label" for="addressFor">Địa chỉ/đường</label>
                                                    <input placeholder="180 cao lỗ" class="form-control" name="address"
                                                        id="addressFor" type="text">
                                                    <p class="error_msg" id="address"></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label" for="priceFor">Tổng tiền</label>
                                                    <input disabled placeholder="199.000 đ" value="" data-type="currency"
                                                        class="form-control" name="price"
                                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" id="priceFor"
                                                        type="text"
                                                        data-product-price=0>
                                                    <label class="form-label" for="priceSaleFor">
                                                        <input name="priceSale" type="checkbox" id="priceSaleFor"> Giá khuyến mãi
                                                        
                                                    </label>
                                                    <p class="error_msg" id="price"></p>

                                                    
                                                   
                                                </div>

                                            <?php $checkAll = isFullAccess(Auth::user()->role);?>
                                            @if ($checkAll)
                                                <div class="col-6">
                                                    <label class="form-label" for="assignSaleFor">Chọn Sale</label>
                                                    <select class="form-control" name="assign-sale" id="">

                                                    @if (isset($listSale))
                                                    @foreach ($listSale as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                    @endif

                                                    </select>
                                                    <p class="error_msg" id="price"></p>
                                                </div>
                                            @else 
                                            <div class="col-4 hidden">
                                                <label class="form-label" for="assignSaleFor">Chọn Sale</label>
                                                <select class="form-control" name="assign-sale" id="">
                                                    <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                                                </select>
                                                <p class="error_msg" id="price"></p>
                                            </div>
                                            @endif

                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="row product-list-order">
                                                <div class="position-relative col-8 d-flex align-items-center">
                                                    <input class="hidden" name="products[]">
                                                    <button type="button" onclick="myFunction()" class=" btn btn-outline-secondary">Sản phẩm</button>
                                                    @if(isset($listProduct))

                                                    <div id="myDropdown" class="position-absolute dropdown-content">
                                                        <input type="text" placeholder="Search.." id="myInput"
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

                                                <div id="sum-qty" class=" col-4 ">
                                                    <label class="form-label" for="qtyFor">Tổng số lượng</label>
                                                    <input value=0 name="sum-qty" class="form-control" disabled
                                                        type="number">
                                                    <p class="error_msg" id="qty"></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="list-product-choose"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row products">
                                    </div>
                                    <div class="loader hidden">
                                        <img src="{{asset('public/images/loader.gif')}}" alt="">
                                    </div>
                                    <button id="submit" class="btn btn-primary">Tạo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
        var _token = $("input[name='_token']").val();
        var phone = $("input[name='phone']").val();
        var name = $("input[name='name']").val();
        var sex = $("select[name='sex']").val();
        var province = $("input[name='province']").attr('data-province-id');
        var district = $("input[name='district']").attr('data-district-id');
        var ward = $("input[name='ward']").attr('data-ward-id');
        var address = $("input[name='address']").val();
        // var products = $("input[name='products[]']").val();
        var qty = $("input[name='sum-qty']").val();
        var assignSale = $("select[name='assign-sale']").val();
     
        var id = $("input[name='id']").val();

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

        if (district === undefined || district === '' || ward === '' || ward === undefined 
            || province === '' || province === undefined || address === ''  || address === undefined) {
            $('#address').text('Vui lòng chọn đúng địa chỉ - xã/phường - quận/huyện - tỉnh/thành phố');
            $('.loader').hide();
        } else {
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
                    isPriceSale
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
        }



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