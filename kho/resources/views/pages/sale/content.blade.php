<style>
    .modal-backdrop.in {
        opacity: -0.5;
    }
    a {
        cursor: pointer;
    }
    
    .modal-backdrop.fade.show {
        width: 100%;
        height: 100%;
    }

    #laravel-notify .notify {
        z-index: 1030;
    }
    .modal-backdrop-notify.show {
        opacity: 0;
    }
    #notify-modal .modal-header,#notify-modal .modal-content  {
        background: #4df54dcc;
        border: unset;
        border-radius: unset;
    }
    #notify-modal .modal-dialog {
        margin-right: 10px;
        width: 300px;
    }
    
    .loader img {
        position: fixed;
        right: 39%;
        top: 50%;
        height: 80px;
        border-radius: 50px;
    }
    .form-select {
        font-size: 14px;
    }

    /* .filter-order .daterange {
    min-width: 230px;
  } */
    input#daterange {
        color: #000;
        border: 1px solid var(--cui-form-select-border-color, #b1b7c1);
        border-radius: 0.375rem;
        width: 100%;
    }
    .mof-container, .txt-mof {
        background-color: transparent;
        height: 45px;
    }
        textarea.txt-mof {
        position: absolute;
        top: 0px;
        left: 0px;
        height: 30px;
        overflow-y: hidden;
        transition: ease 0.2s all;
        line-height: 20px;
        font-size: 11px;
        padding-top: 4px;
        border: none;
    }

    .mof-container {
        position: relative;
        height: 30px;
        width: 100%;
        float: left;
        background-color: white;
    }
    .ttgh6, .ttgh7 {
        width: 40px;
        color: #ff0000;
    }

    .fb {
        font-weight: bold;
    }
    tbody tr.error{
        border: 3px solid #ff0000 !important;
    }
    tbody tr.success{
        border: 3px solid #08a322 !important;
    }
    
    th {
        cursor: move;
        border: 1px solid white;
    }

    .header.header-sticky {
        position: unset;
    }

    #sale-filter {
    transition: all 2s ease-out;
    }

</style>        

<?php $listSale = Helper::getListSale(); 
    $checkAll = isFullAccess(Auth::user()->role);
    $isLeadSale = Helper::isLeadSale(Auth::user()->role);      
    $flag = false;

    if (($listSale->count() > 0 &&  $checkAll) || $isLeadSale) {
        $flag = true;
    }
?>
                   

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="{{ asset('public/css/pages/sale.css'); }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
@csrf

<div class="box-body">
    <div class="loader hidden">
        <img src="{{asset('public/images/new-loader.gif')}}" alt="">
    </div>
    <div class="dragscroll1 tableFixHead">

        <!-- Trigger the modal with a button -->
        <a data-toggle="modal" data-target="#myModal" class="tao-don-fixed">
            <i class="fa fa-edit"></i>
            <div class="text">Tạo TN</div>
        </a>
        {{-- <a href="{{route('add-orders')}}" class="btn btn-primary" data-toggle="modal" data-target="#myModal" role="button">+ Thêm đơn</a>   --}}
            <!-- Modal -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo tác nghiệp sale</h5>
                    <button type="button" id="close-main" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <iframe src="{{route('sale-add')}}" frameborder="0"></iframe>

                </div>
            </div>
        </div>
      
        <form action="{{route('sale-index')}}" class="mb-1">
            @csrf
            
        <script type="text/javascript" src="{{asset('public/js/moment.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('public/css/daterangepicker.css')}}" /> 
        <div class="row mb-1 filter-order">
            <div class=" col-4 form-group daterange mb-1">
                <input id="daterange" class="btn btn-outline-secondary" type="text" name="daterange" />
            </div>
            
            <?php $checkAll = isFullAccess(Auth::user()->role);?>
            @if ($checkAll)
            
            <div class="src-filter col-2 form-group mb-1">
                <select name="src" id="src-filter" class="form-select" aria-label="Default select example">       
                    <option value="999">--Chọn nguồn--</option>
            <?php $pagePanCake = Helper::getConfigPanCake()->page_id;
                if ($pagePanCake) {
                    $pages = json_decode($pagePanCake);
                    // dd($pages);
                    foreach ($pages as $page) {
            ?>
                        <option value="{{$page->id}}">{{($page->name) ? : $page->name}}</option>
            <?php   }
                }   

                $ladiPages = [
                    [
                        'name' => 'Ladipage mua4tang2',
                        'id' => 'mua4tang2',
                        // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                    ],
                    [
                        'name' => 'Ladipage giamgia45',
                        'id' => 'giamgia45',
                        // 'src' => 'https://www.nongnghiepsachvn.net/giamgia45'
                    ],
                    [
                        'name' => 'Tiễn - Ladipage mua4-tang2 ',
                        'id' => 'mua4-tang2',
                        // 'src' => 'https://www.nongnghiepsachvn.net/mua4-tang2'
                    ],

                ];
                foreach ($ladiPages as $page) {
            ?>
                    <option value="{{$page['id']}}">{{($page['name']) ? : $page['name']}}</option>
            <?php   
                }
            ?> 

                </select>
            </div>
            <div class="col-2 form-group mb-1">
                <select name="mkt" id="mkt-filter" class="form-select" aria-label="Default select example">
                    <option value="999">--chọn Marketing--</option>
                    <option value="1">a.Nguyên</option>
                    <option value="2">a.Tiễn</option>
                </select>
            </div>
            @endif

            @if ($checkAll || $isLeadSale)
            <div class="col-2 form-group mb-1">
                <select name="sale" id="sale-filter" class="form-select" aria-label="Default select example">

                @if ($checkAll)<option value="999">--Chọn Sale--</option> @endif
                
                @if (isset($sales))
                    @foreach($sales as $sale)
                    <option value="{{$sale->id}}">{{($sale->real_name) ? : $sale->name}}</option>
                    @endforeach
                @endif
                </select>
            </div>
            @endif

            <div class="col-2 form-group mb-1">
                <select name="type_customer" id="type_customer-filter" class="form-select">
                    <option value="999">--Tất cả khách--</option>
                    <option value="1">Khách cũ</option>
                    <option value="0">Khách mới</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-primary"><svg class="icon me-2">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-filter')}}"></use>
        </svg>Lọc</button>
        <a class="btn btn-outline-danger" href="{{route('sale-index')}}"><strong>X</strong></a>
        </form>
       
       
        <div class="row">
            <div class="mb-1 mt-1">Tổng data: <span>{{$count}}</span></div>
        </div>
        <div class="row ">
            <div class="col-4"></div>
            <div class="col-8 mb-1">
                <form class ="row tool-bar" action="{{route('search-sale-care')}}" method="get">
                    <div class="col-3">
                      <input class="form-control" value="{{ isset($search) ? $search : null}}" name="search" placeholder="Tìm sđt/ tên khách hàng..." type="text">
                    </div>
                    <div class="col-3 " style="padding-left:0;">
                      <button type="submit" class="btn btn-primary"><svg class="icon me-2">
                        <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-search')}}"></use>
                      </svg>Tìm</button>
                </form>
            </div>
            
        </div>
    </div>
        <div id="createOrder" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Thao tác đơn hàng</h5>
                    <button type="button" id="close-main" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <iframe src="{{route('add-orders')}}" frameborder="0"></iframe>

                </div>
            </div>
        </div>
        <table class="table table-bordered table-multi-select table-sale" id="myTable">
            <thead>
                <tr class="drags-area">
                    <th style="top: 0.5px;">
                        <span class="chk-all"><input id="" type="checkbox" name="dnn$ctr1441$Main$SaleTacNghiep$chkItem"><label for="dnn_ctr1441_Main_SaleTacNghiep_chkItem">&nbsp;</label></span>
                    </th>
                    <th draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"><span class="span-col text-center" style="display: inline-block; min-width: 60px; max-width: 80px;">Mã đơn hàng</span></th>

                    <th draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"> <span class="span-col text-center" style="display: inline-block; min-width: 150px; max-width: 150px;">Nguồn data <br>Ngày nhận</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"> <span class="span-col text-center" style="display: inline-block; min-width: 60px; max-width: 100px;">Sale</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area5 hidden-xs" style="top: 0.5px;">
                        <span class="span-col text-center" style="display: inline-block; min-width: 200px; max-width: 200px;">Họ tên<br>
                            <span class="span-col">Số điện thoại</span><br>
                            <span class="span-col">Địa chỉ</span>
                        </span>
                    </th> 
                    <th draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"> <span class="span-col text-center" style="display: inline-block; min-width: 150px; max-width: 150px;">Tin nhắn</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area2" style="top: 0.5px;"> <span style="display: inline-block; min-width: 220px; max-width: 150px;">Tác nghiệp cần</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">Kết quả gọi</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">TN Tiếp</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 120px;">Ghi chú thông tin khách hàng</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">Thông tin đơn</span></th>
                </tr>
            </thead>    
            <tbody>
                {{ csrf_field() }}
                @if (isset($saleCare))
                    @foreach ($saleCare as $item)
                <tr class="contact-row tr_{{$item->id}}">
                    <td class="text-center">
                        <span class="chk-item"><input id="" type="checkbox" name=""><label for="">{{$item->id}}</label></span>
                    </td>
                    <td class="text-center">

                        @if (isset($item->id_order))
                        <a href="{{route('view-order', ['id' => $item->id_order])}}">{{$item->id_order}}</a>
                        @endif
                    
                    </td>
                    <td class="text-center">
                    <a target="blank" {{ ($item->page_link) ? ('href=' . $item->page_link ) : '' }}>{{$item->page_name}}</a>     <br> {{date_format($item->created_at,"H:i d-m-Y ")}}
                    </td>
                    <td class="text-center">

                        @if ($flag)
                        <a title="chỉ định Sale nhận data" data-id="{{$item->id}}" class="update-assign-TN-sale btn-icon aoh">
                            <i class="fa fa-save"></i>
                        </a>
                        <div class="mof-container">
                            <select name="assignTNSale_{{$item->id}}" id="">
                                @foreach ($listSale->get() as $sale)
                                <option <?php echo ($item->user->id == $sale->id) ? 'selected' : '' ?> value="{{$sale->id}}">{{($sale->real_name) ? $sale->real_name : ''}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="clear: both;"></div>
                        @else
                            {{($item->user) ? $item->user->real_name : ''}} 
                        @endif
                    </td>
                    <td class="text-center area5 hidden-xs">
                        <div class="text-right">

                            <?php $checkAll = isFullAccess(Auth::user()->role);?>
                            @if ($checkAll || $isLeadSale)
                            <a title="xoá" class="hidden btn-icon aoh" onclick="return confirm('Bạn muốn xóa data này?')" href="{{route('sale-delete',['id'=>$item->id])}}" role="button">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-trash')}}"></use>
                                </svg>
                            </a>
                            @endif

                            <a data-toggle="modal" data-sale-id="{{$item->id}}" data-target="#createOrder" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a>
                          
                            {{-- @if ($item->id_order)
                              <a data-target="#createOrder" data-toggle="modal" data-id="{{$item->id_order}}" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a>
                            @else
                            <a data-toggle="modal" data-sale-id="{{$item->id}}" data-target="#createOrder" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a>
                            @endif --}}

                            @if ($item->old_customer)
                            <a title="Khách cũ, khách cũ" class="btn-icon">
                                <i class="fa fa-heart" style="color:red;"></i>
                            </a>
                            @endif

                        </div>
                        {{-- <a class="btn-icon aoh"><i class="fa fa-edit"></i> </a> --}}
                        <span class="span-col span-col-width cancel-col">{{$item->full_name}}</span><br>
                        <span class="small-tip"><a href="tel:0987609812">{{$item->phone}}</a>

                            @if ($item->is_duplicate)
                            <a title="Trùng só điện thoại" class="btn-icon">
                                <svg  class="icon me-2" style="color: #ff0000">
                                    <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-copy')}}"></use>
                                </svg>
                            </a>
                            @endif
                            
                        </span><br>
                        <span class="small-tip">{{$item->address}}</span>
                    </td>
                    <td>{{$item->messages}}</td>
                    <td class="area2 hidden-xs">
                        
                        @if (!$item->old_customer)
                        <span class="fb span-col ttgh7" style="cursor: pointer;">Data nóng</span> 
                        @else 
                        <span class="fb span-col" style="cursor: pointer;">CSKH</span> 
                        @endif
                        {{-- <a class="btn-icon aoh hidden" href="/ld/sale/sale-tac-nghiep/id/0" title="Xem bản ghi chốt đơn" target="_blank">
                            <i style="font-size:14px;" class="fa fa-arrow-circle-o-left"></i>
                        </a>  --}}

                        <a title="Lưu ghi chú" data-id="{{$item->id}}" class="update-TN-sale btn-icon aoh">
                            <i class="fa fa-save"></i>
                        </a>
                        <a href="#" title="Tin nhắn nội bộ" class="btn-icon aoh"><i class="fa fa-commenting-o"></i></a>
                        <div class="mof-container">
                            <textarea id="TNSale_{{$item->id}}" rows="2" cols="20" class="form-control txt-mof txt-dotted" data-length="500"
                                 data-content="Tối đa 500 ký tự" data-trigger="focus" data-toggle="popover" data-original-title="" title="">{{$item->TN_can}}</textarea>
                        </div>
                        <div style="clear: both;"></div>
                        <span id="dnn_ctr1441_Main_SaleTacNghiep_rptData__LastMessage_0" class="item-noidung-other"></span>
                    </td>
                    <td class="area2 no-wrap fix_brower_continue_let_off">
                        <div class="select2-container txt-dotted ddlpb chosen dis_val">
                            <a class="select2-choice" tabindex="-1" data-id="{{$item->id}}">  
                                <span class="select2-chosen list-call-{{$item->id}}" > {{$item->result_call ? $item->call->name : '--Chọn--' }}</span>
                                <span class="select2-arrow" role="presentation"><b role="presentation"></b></span>
                            </a>
                            
                            <div id="list-call-{{$item->id}}" class="hidden list-call position-absolute dropdown-content">
                                <input type="text" placeholder="tìm.." id="myInput" onkeyup="filterFunction('list-call-{{$item->id}}')">

                                @if(isset($listCall))
                                @foreach ($listCall as $value)
                                <a class="option-product"
                                    data-call-item-id="{{$item->id}}"
                                    data-call-name="{{$value->result_call}}"
                                    data-call-id="{{$value->id}}">{{$value->result_call}}
                                </a>
                                @endforeach
                                @endif

                            </div>
                        </div>
                    </td>
                    <td class="text-center next-step-{{$item->id}}">{{ ($item->next_step) ? 'Gọi lần ' . $item->next_step : ''}}</td>
                    {{-- <td class="text-center area5 hidden-xs">
                        {{$item->type_tree}}
                    </td> --}}
                    {{-- <td class="area1">
                        {{$item->product_request}}
                    </td> --}}
                    {{-- <td class="area1 hidden-xs">
                        <div class="mof-container">
                            <div class="form-control txt-mof txt-dotted">
                                {{$item->reason_not_buy}}
                            </div>
                        </div>
                    </td> --}}
                    <td class="area2 hidden-xs">{{$item->note_info_customer}}</td>
                    {{-- <td class="text-center"> 
                        <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModal" class="updateModal btn-icon aoh"><i class="fa fa-edit"></i>Cập nhật</a>
                    </td> --}}
                    <td class="text-center"> 

                    <?php 
                    if ($item->id_order) {
                        $order = $item->order;
                        foreach (json_decode($order->id_product) as $product) {
                            $productModel = getProductByIdHelper($product->id);
                            if ($productModel) {
                        ?>
                        
                        {{$productModel->name}} x{{$product->val}} <br>
                        
                        <?php }
                        } ?>

                    <span>Tổng: {{number_format($order->total)}}đ</span> 
                    <?php 
                    } ?>

                    </td>
                </tr>
        
                @endforeach
                
            </tbody>
        </table>
        {{ $saleCare->appends(request()->input())->links('pagination::bootstrap-5') }}
        @endif

    </div>
</div>

{{-- thông báo --}}
<div class="modal fade" id="notify-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title" style="color: seagreen;"><p style="margin:0">Cập nhật kết quả thành công</p></h6>
            <button style="border: none;" type="button" id="close-modal-notify" class="close" data-dismiss="modal" >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      </div>
  </div>
<script>
    $('.orderModal').on('click', function () {
        var myBookId = $(this).data('id');
        var saleId = $(this).data('sale-id');
        if (saleId) {
            var link = "{{URL::to('/them-don-hang/')}}";
            $("#createOrder iframe").attr("src", link + '?saleCareId=' + saleId);
        } else {
            console.log('myBookId')
            
            var link = "{{URL::to('/update-order/')}}";
            $("#createOrder iframe").attr("src", link + '/' + myBookId);
        }
        // var link = "{{URL::to('/update-order')}}";
        // $("#createOrder iframe").attr("src", link + '/' + myBookId);
    });

    $('.updateModal').on('click', function () {
        var myBookId = $(this).data('id');
        var link = "{{URL::to('/cap-nhat-tac-nghiep-sale')}}";
        $("#myModal iframe").attr("src", link + '/' + myBookId);
    });

    $('.select2-choice').on('click', function () {
        var id = $(this).data('id');
        $(this).parent().toggleClass("select-dropdown-show");
        console.log(id);
    });
    
    $(".select2-choice, .list-call").click(function(e){
        e.stopPropagation();
    });
    $(document).click(function(e){
        $(".select-dropdown-show").removeClass('select-dropdown-show');
    });
    
    $("#close-modal-notify").click(function() {
        $('#notify-modal').modal("hide");
    });
    $(".option-product").click(function() {
        $('.body').css("opacity", '0.5');
        $('.loader').show();
        let id      = $(this).data("call-id");
        let name    = $(this).data("call-name");
        var _token  = $("input[name='_token']").val();
        var itemId  = $(this).data("call-item-id");
        console.log(id)
        $('.select2-container').removeClass("select-dropdown-show");

        $.ajax({
            url: "{{ route('sale-save-ajax') }}",
            type: 'POST',
            data: {
                _token: _token,
                itemId,
                id,
                name
            },
            success: function(data) {
                $('.body').css("opacity", '1');
                if (!data.error) {
                    $('#notify-modal').modal('show');
                    if ($('.modal-backdrop-notify').length === 0) {
                        $('.modal-backdrop').addClass('modal-backdrop-notify');
                    }

                    str         = 'span.list-call-' + itemId;
                    strNextStep = 'td.next-step-' + itemId;
                    $(str).text(name);
                    $(strNextStep).text('Gọi lần ' + data.data.next_step);

                    setTimeout(function() { 
                        $('#notify-modal').modal("hide");
                    }, 2000);
                } else {
                    alert('Đã xảy ra lỗi trong quá trình cập nhật TN Sale!');
                }
                $('.loader').hide();
            }
        });
    });
 
</script>

<script type="text/javascript" src="{{asset('public/js/dateRangePicker/daterangepicker.min.js')}}"></script>
<script>
    $('input[name="daterange"]').daterangepicker({
      ranges: {
        'Hôm nay': [moment(), moment()],
        'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 ngày gần đây': [moment().subtract(6, 'days'), moment()],
        '30 ngày gần đây': [moment().subtract(29, 'days'), moment()],
        'Tháng này': [moment().startOf('month'), moment().endOf('month')],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      locale: {
        "format": 'DD/MM/YYYY',
        "applyLabel": "OK",
        "cancelLabel": "Huỷ",
        "fromLabel": "Từ",
        "toLabel": "Đến",
        "daysOfWeek": [
          "CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy" 
        ],
        "monthNames": [
          "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
	        "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12" 
        ],
      }
    });
    $('[data-range-key="Custom Range"]').text('Tuỳ chỉnh');
</script>
<script>
    function filterFunction(id) {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById(id);
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
</script>

<script>
const mrNguyen = [
    {
        id : '332556043267807',
        name_page : 'Rước Đòng Organic Rice - Tăng Đòng Gấp 3 Lần',
    },
    {
        id : '318167024711625',
        name_page : 'Siêu Rước Đòng Organic Rice- Hàm Lượng Cao X3',
    },
    {
        id : '341850232325526',
        name_page : 'Siêu Rước Đòng Organic Rice - Hiệu Quả 100%',
    },
    {
        id : 'mua4tang2',
        name_page : 'Ladipage mua4tang2',
    },
    {
        id : 'giamgia45',
        name_page : 'Ladipage giamgia45',
    }
];
const mrTien = [
    {
        id : 'mua4-tang2',
        name_page : 'Ladipage mua4-tang2',
    }
];
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results) {
        return results[1];
    }
    return 0;
}
let sale = $.urlParam('sale') 
if (sale) {
    $('#sale-filter option[value=' + sale +']').attr('selected','selected');
}

let mkt = $.urlParam('mkt') 
if (mkt) {
    $('#mkt-filter option[value=' + mkt +']').attr('selected','selected');
}

let src = $.urlParam('src') 
if (src) {
    // let str = '<option value="999">--Tất cả Nguồn--</option>';
    // $('.src-filter').show('slow');

    // if (mkt == 1) {
    //     mrNguyen.forEach (function(item) {
    //         // console.log(item);
    //         str += '<option value="' + item.id +'">' + item.name_page +'</option>';
    //     })
    //     $(str).appendTo("#src-filter");
    // } else if (mkt == 2) {
    //     mrTien.forEach (function(item) {
    //         // console.log(item);
    //         str += '<option value="' + item.id +'">' + item.name_page +'</option>';
    //     })
    //     $(str).appendTo("#src-filter");
    // }
    $('#src-filter option[value=' + src +']').attr('selected','selected');
}

let typeCustomer = $.urlParam('type_customer') 
if (typeCustomer) {
    $('#type_customer-filter option[value=' + typeCustomer +']').attr('selected','selected');
}

let time = $.urlParam('daterange') 
if (time) {
    time = decodeURIComponent(time)
    time = time.replace('+-+', ' - ') //loại bỏ khoảng trắng
    $('input[name="daterange"]').val(time)
}
</script>

<script>
    $('.update-assign-TN-sale').click(function(){
        $('.body').css("opacity", '0.5');
        $('.loader').show();
        var id = $(this).data("id");
        var textArea = "select[name='assignTNSale_" + id + "']";
        var assignSale  = $(textArea).val();
        // console.log(assignSale);
        // var textTN   = $(textArea).val();
        var _token   = $("input[name='_token']").val();
        // console.log('koko', id);
        // return;
        $.ajax({
            url: "{{route('update-salecare-assign')}}",
            type: 'POST',
            data: {
                _token: _token,
                id,
                assignSale
            },
            success: function(data) {
                $('.body').css("opacity", '1');
                var tr = '.tr_' + id;
                if (!data.error) {
                    $('#notify-modal').modal('show');
                    if ($('.modal-backdrop-notify').length === 0) {
                        $('.modal-backdrop').addClass('modal-backdrop-notify');
                    }

                    $(tr).addClass('success');
                    setTimeout(function() { 
                        $('#notify-modal').modal("hide");
                        $(tr).removeClass('success');
                    }, 2000);
                } else {
                    alert('Đã xảy ra lỗi trong quá trình cập nhật TN Sale!');
                    $(tr).addClass('error');
                    setTimeout(function() { 
                        $(tr).removeClass('error');
                    }, 3000);
                }
                $('.loader').hide();
            }
        });
    });
    $('.update-TN-sale').click(function(){
        $('.body').css("opacity", '0.5');
        $('.loader').show();
        var id = $(this).data("id");
        var textArea = '#TNSale_' + id;
        var textTN   = $(textArea).val();
        var _token   = $("input[name='_token']").val();
        // console.log('koko', id);
        // return;
        $.ajax({
            url: "{{route('update-salecare-TNcan')}}",
            type: 'POST',
            data: {
                _token: _token,
                id,
                textTN
            },
            success: function(data) {
                $('.body').css("opacity", '1');
                var tr = '.tr_' + id;
                if (!data.error) {
                    $('#notify-modal').modal('show');
                    if ($('.modal-backdrop-notify').length === 0) {
                        $('.modal-backdrop').addClass('modal-backdrop-notify');
                    }

                    $(tr).addClass('success');
                    setTimeout(function() { 
                        $('#notify-modal').modal("hide");
                        $(tr).removeClass('success');
                    }, 2000);
                } else {
                    alert('Đã xảy ra lỗi trong quá trình cập nhật TN Sale!');
                    $(tr).addClass('error');
                    setTimeout(function() { 
                        $(tr).removeClass('error');
                    }, 3000);
                }
                $('.loader').hide();
            }
        });
    });
</script>

<script type="text/javascript">
    function setZoom() {
      if (window.matchMedia('(min-width: 1180px) and (max-width: 2000px)').matches) {
        document.body.style.zoom = "90%";
      } else {
        document.body.style.zoom = "100%";
      }
    }
   
    // Call the function to set the zoom on page load
    // setZoom();
   
    // Handle the window resize event
    window.addEventListener('resize', setZoom);
</script>

<script type="text/javascript">
    var dragCol = null;
    function handleDragStart(e) {
        dragCol = this;
        e.dataTransfer.efferAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHtml);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation;
        }

        if (dragCol !== this) {
            var sourceIndex = Array.from(dragCol.parentNode.children).indexOf(dragCol);
            var targetIndex = Array.from(this.parentNode.children).indexOf(this);

            var table = document.getElementById('myTable');
            var rows = table.rows;
            for ( var i = 0; i < rows.length; i++) {
                var sourceCell = rows[i].cells[sourceIndex];
                var targetCell = rows[i].cells[targetIndex];

                var tempHTML = sourceCell.innerHTML;
                sourceCell.innerHTML = targetCell.innerHTML;
                targetCell.innerHTML = tempHTML;
            }
        }
        return false;
    }

    var cols = document.querySelectorAll('th');
    [].forEach.call(cols, function(col) {
        col.addEventListener('dragstart', handleDragStart, false);
        col.addEventListener('dragover', handleDragOver, false);
        col.addEventListener('drop', handleDrop, false);
    });

</script>

<script>  
    // $("#mkt-filter").change(function() {
    //     var selectedVal = $(this).find(':selected').val();
    //     var selectedText = $(this).find(':selected').text();
        
    //     let str = '<option value="999">--Tất cả Nguồn--</option>';
    //     $('.src-filter').show('slow');

    //     if ($('#src-filter').children().length > 0) {
    //         $('#src-filter').children().remove();
    //     }

    //     if (selectedVal == 1) {
        
    //         mrNguyen.forEach (function(item) {
    //             console.log(item);
    //             str += '<option value="' + item.id +'">' + item.name_page +'</option>';
    //         })
    //         $(str).appendTo("#src-filter");
    //     } else if (selectedVal == 2) {

    //         mrTien.forEach (function(item) {
    //             console.log(item);
    //             str += '<option value="' + item.id +'">' + item.name_page +'</option>';
    //         });
    //         $(str).appendTo("#src-filter");
    //     } else {
    //         $('.src-filter').hide('slow');
    //         $('#src-filter').children().remove();
    //     }
    // });
</script>