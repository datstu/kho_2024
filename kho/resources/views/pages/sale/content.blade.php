<style>
    .modal-backdrop.in {
        opacity: -0.5;
    }
    a {
        cursor: pointer;
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
    
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="{{ asset('public/css/pages/sale.css'); }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<div class="box-body">
    <div class="dragscroll1 tableFixHead">
        <div>
            <!-- Trigger the modal with a button -->
            <a data-toggle="modal" data-target="#myModal" class="tao-don-fixed">
                <i class="fa fa-edit"></i>
                <div class="text">Tạo mới</div>
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
            <table class="table table-bordered table-multi-select table-sale">
                <thead>
                    <tr class="drags-area">
                        <th style="top: 0.5px;">
                            <span class="chk-all"><input id="dnn_ctr1441_Main_SaleTacNghiep_chkItem" type="checkbox" name="dnn$ctr1441$Main$SaleTacNghiep$chkItem"><label for="dnn_ctr1441_Main_SaleTacNghiep_chkItem">&nbsp;</label></span>
                        </th>
                        <th style="top: 0.5px;"><span class="span-col text-center" style="display: inline-block; min-width: 80px; max-width: 100px;">Mã đơn hàng</span></th>
                        <th style="top: 0.5px;"><span class="span-col text-center" style="display: inline-block; min-width: 80px; max-width: 100px;">Ngày </span></th>
                        <th class="text-center" style="top: 0.5px;">
                        <span class="span-col" style="display: inline-block; width: 50px;">Giới tính</span></th> 
                        <th class="text-center no-wrap area5 hidden-xs" style="top: 0.5px;">
                            <span class="span-col text-center" style="display: inline-block; min-width: 150px; max-width: 200px;">Họ tên<br>
                                <span class="span-col">Số điện thoại</span><br>
                                <span class="span-col">Địa chỉ</span>
                            </span></th> 
                            
                        <th class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">Kết quả gọi</span></th>
                        <th class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">TN Tiếp</span></th>
                        <th class="text-center no-wrap area1  hidden-xs" style="top: 0.5px;"><span style="display: inline-block; width: 120px;" class="span-col">Cây trồng</span></th>
                        <th class="text-center no-wrap area2 hidden-xs" style="top: 0.5px;"><span style="display: inline-block; width: 120px;" class="span-col" style="">Nhu cầu dòng sản phẩm</span></th>
                        <th class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="width: 150px; display: inline-block;">Lý do không mua hàng</span></th>
                        <th class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 120px;">Ghi chú thông tin khách hàng</span></th>
                        <th class="text-center no-wrap area2" style="top: 0.5px;"><span class="span-col" style="display: inline-block; width: 150px;">Thao tác</span></th>
                    </tr>
                </thead>    
                <tbody>
                    {{ csrf_field() }}
                    @if (isset($saleCare))
                        @foreach ($saleCare as $item)
                    <tr class="contact-row">
                        <td class="text-center">
                            <span class="chk-item"><input id="" type="checkbox" name=""><label for="">{{$item->id}}</label></span>
                        </td>
                        <td class="text-center">
                            <a href="{{route('view-order', ['id' => $item->id_order])}}">{{$item->id_order}}</a>
                        </td>
                        <td class="text-center">
                            {{date_format($item->created_at,"d-m-Y ")}}
                        </td>
                        <td class="text-center">Nam</td>
                        <td class="text-center area5 hidden-xs">
                            {{-- <a class="btn-icon aoh"><i class="fa fa-edit"></i> </a> --}}
                            <span class="span-col span-col-width cancel-col">{{$item->full_name}}</span><br>
                            <span class="small-tip"><a href="tel:0987609812">{{$item->phone}}</a></span><br>
                            <span class="small-tip">{{$item->address}}</span>
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
                                        data-call-name="{{$value->name}}"
                                        data-call-id="{{$value->id}}">{{$value->name}}
                                    </a>
                                    @endforeach
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td class="text-center next-step-{{$item->id}}">{{ ($item->next_step) ? 'Gọi lần ' . $item->next_step : ''}}</td>
                        <td class="text-center area5 hidden-xs">
                            {{$item->type_tree}}
                        </td>
                        <td class="area1">
                            {{$item->product_request}}
                        </td>
                        <td class="area1 hidden-xs">
                            <div class="mof-container">
                                <div class="form-control txt-mof txt-dotted">
                                    {{$item->reason_not_buy}}
                                </div>
                            </div>
                        </td>
                        <td class="area2 hidden-xs">{{$item->note_info_customer}}</td>
                        <td class="text-center"> <a data-toggle="modal" data-id="{{$item->id}}" data-target="#myModal" class="updateModal btn-icon aoh"><i class="fa fa-edit"></i>Cập nhật</a></td>
                    </tr>
            
                    @endforeach
                   
                </tbody>
            </table>

            {{$saleCare->links('pagination::bootstrap-5')}}
            @endif
        </div>
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
                    }, 3000);
                } else {
                    alert('Đã xảy ra lỗi trong quá trình cập nhật TN Sale!');
                }
            }
        });
    });
 
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


