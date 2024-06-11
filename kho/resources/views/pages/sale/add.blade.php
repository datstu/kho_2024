<!DOCTYPE html>
<html lang="en">
<head>
    <link href="{{ asset('public/css/pages/notify.css'); }}" rel="stylesheet">
    @include('includes.head')
    <link href="{{ asset('public/css/pages/styleOrders.css'); }}" rel="stylesheet">

<style>
    .call label {
        display: flex;
        justify-content: space-between;
    }
    #laravel-notify .notify {
        z-index: 2;
    }

</style>

</head>
<body>
    @include('notify::components.notify')
    <div class="body flex-grow-1 px-3 mt-2">
        <div class="row">

            <?php foreach ($errors->getMessages() as $kE => $error) {
                
                if ($kE == 'id_order') {
                    $kE = 'Mã đơn hàng';
                }
                foreach ($error as $k => $val) {
            ?>
            <div id="notifi-box" style="display: flex; justify-content: space-between;" class="alert alert-danger print-error-msg">
                <span>{{$kE . ' ' . $val}}</span>
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> 
            </div>
            <?php
                    }
                }
            ?>
            
    
            <div class="col-lg-12">
                <div class="card">

                @if(isset($saleCare))
                    {{-- <div class="card-header"><span><strong>Cập nhật CSKH</strong></span></div> --}}
                    <div class="card-body card-orders">
                        <div class="body flex-grow-1">
                            <div class="tab-content rounded-bottom">
                                <form method="post" action="{{route('sale-care-save')}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$saleCare->id}}">
                                    <div class="row" id="content-add">
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="phoneFor">Số điện thoại</label>
                                            <input value="{{$saleCare->phone}}" class="form-control" name="phone" id="phoneFor" type="text">
                                            <p class="error_msg" id="phone"></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="nameFor">Tên khách hàng</label>
                                            <input value="{{$saleCare->full_name}}" class="form-control" name="name" id="nameFor" type="text">
                                            <p class="error_msg" id="name"></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="sexFor">Giới tính</label>
                                            <select name="sex"
                                                id="sexFor" class="form-control">
                                                <option <?= $saleCare->sex == 0 ? 'selected' : ''; ?> value="0">Nam</option>
                                                <option <?= $saleCare->sex == 1 ? 'selected' : ''; ?> value="1">Nữ</option>
                                            </select>
                                            <p class="error_msg" id="sex"></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="addressFor">Địa chỉ/đường</label>
                                            <input value="{{$saleCare->address}}" class="form-control"
                                                name="address" id="addressFor" type="text">
                                            <p class="error_msg" id="address"></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <label for="type_tree" class="form-label">Cây trồng:</label>
                                            <textarea name="type_tree" class="form-control" id="type_tree" rows="3">{{$saleCare->type_tree}}</textarea>
                                            <p></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <label for="product-request" class="form-label">Nhu cầu dòng sản phẩm:</label>
                                            <textarea name="product_request" class="form-control" id="product-request" rows="3">{{$saleCare->product_request}}</textarea>
                                            <p></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <label for="reason_not_buy" class="form-label">Lý do không mua hàng:</label>
                                            <textarea name="reason_not_buy" class="form-control" id="reason_not_buy" rows="3">{{$saleCare->reason_not_buy}}</textarea>
                                            <p></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <label for="note_info_customer" class="form-label">Ghi chú thông tin khách hàng:</label>
                                            <textarea name="note_info_customer" class="form-control" id="note_info_customer" rows="3">{{$saleCare->note_info_customer}}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-4">
                                            <label for="id_order" class="form-label">Mã đơn:</label>
                                            <input class="form-control" name="id_order"
                                                id="id_order" type="text" value="{{$saleCare->id_order}}">
                                            <p></p>
                                        </div>

                                        <?php $checkAll = isFullAccess(Auth::user()->role);?>
                                        @if ($checkAll)

                                        <div class="col-4">
                                            <label class="form-label" >Chọn Sale</label>
                                            <select class="form-control" name="assign_sale">

                                            @if (isset($listSale))
                                            @foreach ($listSale as $item)
                                                <option <?php echo ($item->id == $saleCare->assign_user) ? 'selected' : '';?> value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                            @endif

                                            </select>
                                            <p class="error_msg" id="price"></p>
                                        </div>

                                        @else 

                                        <div class="col-6 hidden">
                                            <label class="form-label" >Chọn Sale</label>
                                            <select class="form-control" name="assign">
                                                <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                                            </select>
                                            <p class="error_msg" id="price"></p>
                                        </div>

                                        @endif
                                    </div>
                                    <div class="loader hidden">
                                        <img src="{{asset('public/images/loader.gif')}}" alt="">
                                    </div>
                                    {{-- <button id="add" type="button" class="btn btn-danger text-white">Thêm lần gọi</button> --}}
                                    <button id="submit" class="btn btn-primary">Cập nhật </button>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
         
                @else
                    <div class="card-body card-orders">
                        <div class="body flex-grow-1">
                            <div class="tab-content rounded-bottom">
                                <form method="post" action="{{route('sale-care-save')}}">
                                    {{ csrf_field() }}
                                    <div class="row" id="content-add">
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="phoneFor">Số điện thoại<span class="required-input">(*)</span></label>
                                            <input required placeholder="0973409613" class="form-control" name="phone"
                                                id="phoneFor" type="text">
                                            <p class="error_msg" id="phone"></p>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <label class="form-label" for="nameFor">Tên khách hàng<span class="required-input">(*)</span></label>
                                            <input required placeholder="Họ và tên" class="form-control" name="name"
                                                id="nameFor" type="text">
                                            <p class="error_msg" id="name"></p>
                                        </div>
                                        <div class="col-sm-6 col-lg-2">
                                            <label class="form-label" for="sexFor">Giới tính<span class="required-input">(*)</span></label>
                                            <select theme="google" name="sex" id="sexFor"
                                                class="form-control">
                                                <option value="0">Nam</option>
                                                <option value="1">Nữ</option>
                                            </select>
                                            <p class="error_msg" id="sex"></p>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <label class="form-label" for="addressFor">Địa chỉ/đường<span class="required-input">(*)</span></label>
                                            <input required placeholder="180 cao lỗ" class="form-control" name="address"
                                                id="addressFor" type="text">
                                            <p class="error_msg" id="address"></p>
                                        </div>
                                        
                                        {{-- <div class="col-sm-12 col-lg-4 call">
                                            <label for="call1" class="form-label ">Gọi lần 1:
                                                <span class="delete">xoá</span>
                                            </label>
                                            <textarea data-id-call=1 name="call[]" class="form-control" id="call1" rows="3"></textarea>
                                            <p></p>
                                        </div> --}}
                                    </div>
                                    <div class="row">
                                        <input type="text" name="text" value="Sale tự tạo" class="hidden">
                                        <input type="text" name="page_name" value="Sale tự tạo" class="hidden">
                                        <div class="col-sm-6 col-lg-8">
                                            <label class="form-label" for="messagesFor">Tin nhắn</label><br>
                                            <textarea name="messages" id="messagesFor" cols="80" rows="5"></textarea>
                                            <p class="error_msg" id="address"></p>
                                        </div>
                                        
                                        <?php $checkAll = isFullAccess(Auth::user()->role);?>
                                        @if ($checkAll)
                                        <div class="col-lg-3">
                                            <label class="form-label">Chọn Sale:</label>
                                            <select class="form-control" name="assgin" >

                                            @if (isset($listSale))
                                            @foreach ($listSale as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                            @endif

                                            </select>
                                            <p class="error_msg" id="price"></p>
                                        </div>
                                        @else 
                                        <div class="col-lg-3 hidden">
                                            <label class="form-label">Chọn Sale:</label>
                                            <select class="form-control" name="assgin">
                                                <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                                            </select>
                                            <p class="error_msg" id="price"></p>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="loader hidden text-center">
                                        <img src="{{asset('public/images/loader.gif')}}" alt="">
                                    </div>
                                    {{-- <button id="add" type="button" class="btn btn-danger text-white">Thêm lần gọi</button> --}}
                                    <button id="submit" class="btn btn-primary">Tạo</button>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                </div>
            </div>
        </div>
    </div>
<script>

    // A $( document ).ready() block.
$( document ).ready(function() {
    if ($('.print-error-msg').length > 0) {
        setTimeout(function() { 
            $('.print-error-msg').hide();
        }, 3000);
    }

    $( "#add" ).on( "click", function() {
        $('.delete').remove();
        length  = $("textarea[name='call[]']").length;
        number  = length + 1;
        str     = '<div class="col-sm-12 col-lg-4 call">'
            + '<label for="call' + number + '" class="form-label">Gọi lần ' + number + ':'
            + '<span class="delete" onclick="deleteCall($(this))">xoá</span>'
            + '</label>'
            + '<textarea data-id-call='+ number + ' + name="call[]" class="form-control" id="call' + number + '" rows="3"></textarea>'
            + '<p></p>'
            + '</div>';
        $("#content-add").append(str);
    });

    $(".delete").on( "click", function() {
        el = $(this).parent().parent();
        parent      = el;
        id          = parent[0].children[1].id;
        numberCall  = $("#" + id).attr("data-id-call");
        numberCall  -= 1;
        callPre     = $('#call' + numberCall);

        if (callPre.length > 0) {
            label   = callPre.parent()[0].children[0];
            str     = '<span class="delete" onclick="deleteCall($(this))">xoá</span>';
            $(label).append(str);
        }
        $(this).parent().parent().remove();
    });

    jQuery.fn.deleteCall = function() {
        $(this).parent().parent().remove();
    }

    $('.print-error-msg').on( "click", function() {
        $(this).hide();
    });

    $('#submit').on( "click", function() {
        $phone = $("input[name='phone']").val();
        $name = $("input[name='name']").val();
        $address = $("input[name='address']").val();
        if ( $phone != '' && $name != '' && $address != '') {
            $('.loader').show();
            $('.body form').css("opacity", '0.5');
        }
    });

    
});
function deleteCall(val) {
    parent      = val.parent().parent();
    id          = parent[0].children[1].id;
    numberCall  = $("#" + id).attr("data-id-call");
    numberCall  -= 1;
    callPre     = $('#call' + numberCall);

    if (callPre.length > 0) {
        label   = callPre.parent()[0].children[0];
        str     = '<span class="delete" onclick="deleteCall($(this))">xoá</span>';
        $(label).append(str);
    }
   
    val.parent().parent().remove();
}
    
</script>
    @include('includes.foot')
    <script type="text/javascript" src="{{ asset('public/js/notify.js'); }}"></script>
</body>
</html>