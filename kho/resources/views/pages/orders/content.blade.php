<style>
  .example-custom {
    font-size: 13px;
  }
  /* .header.header-sticky {
    display: none;
  } */

  .green span {
    width: 75px;
    display: inline-block; 
    color: #fff;
    background: #0f0;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #0f0;
    font-weight: 700;
  }

  .red span {
    width: 75px;
    display: inline-block; 
    color: #ff0000;
    background: #fff;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #ff0000;
    font-weight: 700;
  }

  .orange span {
    width: 75px;
    display: inline-block;
    color: #fff;
    background: #ffbe08;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #fff;
    font-weight: 700;
  }
  #myModal .modal-dialog {
    /* margin-top: 5px;
    width: 1280px; */
    /* margin: 10px; */
    height: 90%;
    /* background: #0f0; */
  }
  #myModal .modal-dialog iframe {
    /* 100% = dialog height, 120px = header + footer */
    height: 100%;
    overflow-y: scroll;
  }

  #myModal .modal-dialog .modal-content {
    height: 100%;
    /* overflow: scroll; */
  }
  .filter-order .daterange {
    min-width: 230px;
  }

  .add-order {
    position: fixed;
    right: 10px;
    bottom: 10px;
  }

  input#daterange {
    color: #000;
  }
</style>

<?php 
$listStatus = Helper::getListStatus();
$styleStatus = [
  0 => 'red',
  1 => 'white',
  2 => 'orange',
  3 => 'green',
];

?>

<script type="text/javascript" src="{{asset('public/js/moment.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/daterangepicker.css')}}" /> 

<div class="tab-content rounded-bottom">
<div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1001">

  <form action="{{route('order')}}" class="mb-1">
    <div class="row mb-1 filter-order">
   
      <div class="col-xs-12 col-sm-6 col-md-2 form-group daterange mb-1">
        <input id="daterange" class="btn btn-outline-secondary" type="text" name="daterange" />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="status" id="status-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Trạng Thái (Tất cả)--</option>
          <option value="1">Chưa giao vận</option>
          <option value="2">Đang giao</option>
          <option value="3">Hoàn tất</option>
          <option value="0">Huỷ</option>
        </select>
      </div>
      
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="category" id="category-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Danh mục (Tất cả)--</option>
          @if (isset($category))
            @foreach($category as $cate)
            <option value="{{$cate->id}}">{{$cate->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
    
    </div>
    <button type="submit" class="btn btn-outline-primary">Lọc</button>
    <a  class="btn btn-outline-danger" href="{{route('order')}}"><strong>X</strong></a>
  </form>

    <div class="row ">
      <div class="col-12">
        
        @if (isset($list))
        <hr>
        <button type="button" class="btn">Tổng đơn: {{$totalOrder}}</button>
        <button type="button" class="btn">Tổng sản phẩm: {{$sumProduct}}</button>
        @endif
      
      </div>
      <div class="col col-4">
        
        <a class="add-order btn btn-primary" href="{{route('add-orders')}}" role="button">+ Thêm đơn</a>
        {{-- <a href="{{route('add-orders')}}" class="btn btn-primary" data-toggle="modal" data-target="#myModal" role="button">+ Thêm đơn</a>    --}}
        <!-- Modal -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content ">
              <div class="modal-header">
                <h5 class="modal-title">Thêm đơn hàng mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
             
              <iframe src="{{route('add-orders')}}" frameborder="0"></iframe>

            </div>
          </div>
        </div>
      </div>
      <div class="col-8 ">
        <form class ="row tool-bar" action="{{route('search-order')}}" method="get">
          <div class="col-3">
            <input class="form-control" value="{{ isset($search) ? $search : null}}" name="search" placeholder="Tìm đơn hàng..." type="text">
          </div>
          <div class="col-3 " style="padding-left:0;">
            <button type="submit" class="btn btn-primary"><svg class="icon me-2">
              <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-search')}}"></use>
            </svg>Tìm</button>
        </form>
          </div>
      </div>
    </div>
    <div class="example-custom example mt-0">
      <div class="tab-content rounded-bottom">
        <div style="overflow-x: auto;" class="tab-pane p-0 pt-1 active preview" role="tabpanel" id="preview-1002">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                
                <th scope="col">Sđt</th>
                <th class="mobile-col-tbl" scope="col" >Tên</th>
                <!-- <th scope="col">Địa chỉ</th> -->
                <th scope="col">Số lượng</th>
                <th scope="col">Tổng tiền</th>
                <th scope="col">Giới tính</th>
                <th class="mobile-col-tbl" scope="col">Ngày lên đơn</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Mã vận đơn</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>

            @foreach ($list as $item)
            
            <?php 
            $shippingOrder    = $item->shippingOrder()->get()->first();
            // dd($item->id);
            $orderCode        = $shippingOrder->order_code ?? '';
            $shippingOrderId  = $shippingOrder->id ?? '';
            ?>
              <tr>
                
                <th onclick="window.location='{{route('view-order', $item->id)}}';" style='cursor: pointer;'>{{ $item->id }}</th>
                <td onclick="window.location='{{route('view-order', $item->id)}}';" style='cursor: pointer;'>  {{ $item->phone }}</td>
                <td onclick="window.location='{{route('view-order', $item->id)}}';" style='cursor: pointer;' class="mobile-col-tbl">  {{ $item->name }} </td>
                <td class="text-center">  {{ $item->qty }} </td>
                <td >  {{ number_format($item->total) }}đ</td>
                <td >  {{ getSexHelper($item->sex) }} </td>
                <td class="mobile-col-tbl">  {{ date_format($item->created_at,"d-m-Y ")}}</td>
                <td  class="text-center {{$styleStatus[$item->status]}}"><span>{{$listStatus[$item->status]}}</span> </td>
                <td>

                  @if ($shippingOrderId)
                  <a  title="sửa" class="" href="{{route('detai-shipping-order',['id'=>$shippingOrderId])}}" role="button">{{$orderCode}}</a>
                  @endif
                
                </td>
                <td>
                <a  title="sửa" class="" href="{{route('update-order',['id'=>$item->id])}}" role="button">
                  
                    <svg class="icon me-2">
                      <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-color-border')}}"></use>
                    </svg>
                </a>
                </td>
                
                <td >
                  <?php $checkAll = isFullAccess(Auth::user()->role);?>
                  @if ($checkAll)
                  <a title="xoá" onclick="return confirm('Bạn muốn xóa đơn này?')" href="{{route('delete-order',['id'=>$item->id])}}" role="button">
                    <svg class="icon me-2">
                      <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-backspace')}}"></use>
                    </svg>
                  </a>
                  @endif
                </td>
                
              </tr>
              @endforeach
              
            </tbody>
          </table>
          {{-- {{$list->links('pagination::bootstrap-5')}} --}}
          {{ $list->appends(request()->input())->links('pagination::bootstrap-5') }}
         
        </div>
      </div>
    </div>
</div>
  
<script>
  // console.log(decodeURI(window.location.href))
  $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
  }

  let time = $.urlParam('daterange') 
  if (time) {
    time = decodeURIComponent(time)
    time = time.replace('+-+', ' - ') //loại bỏ khoảng trắng
    $('input[name="daterange"]').val(time)
  }

  let status = $.urlParam('status') 
  if (status) {
    $('#status-filter option[value=' + status +']').attr('selected','selected');
  }

  let category = $.urlParam('category') 
  if (category) {
    $('#category-filter option[value=' + category +']').attr('selected','selected');
  }

  let product = $.urlParam('product') 
  console.log(product)
  if (product) {
    var _token      = $("input[name='_token']").val();
      $.ajax({
            url: "{{ route('get-products-by-category-id') }}",
            type: 'GET',
            data: {
                _token: _token,
                categoryId: category
            },
            success: function(data) {
             
              let str = '';
              str += '<div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">'
                + '<select name="product" id="product-filter" class="form-select" aria-label="Default select example">'
                + '<option value="999">--Sản phẩm (Tất cả)--</option>';
                data.forEach(item => {
                  // console.log(item['id'])
                  selected = item['id'] == product ? 'selected' : '';
                  str += '<option ' +  selected +' value="' + item['id'] + '">' + item['name'] + '</option>';
                  });
              str  += '</select>'
                + '</div>';

                $(str).appendTo(".filter-order");
            }
        });
    $('#product-filter option[value=' + product +']').attr('selected','selected');
  }

</script>
<script type="text/javascript" src="{{asset('public/js/dateRangePicker/daterangepicker.min.js')}}"></script>
<script>
$(document).ready(function() {
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

    $("#category-filter").change(function() {
      var selectedVal = $(this).find(':selected').val();
      var selectedText = $(this).find(':selected').text();
      
      if (selectedVal == 9) {
        var _token      = $("input[name='_token']").val();
        $.ajax({
              url: "{{ route('get-products-by-category-id') }}",
              type: 'GET',
              data: {
                  _token: _token,
                  categoryId: selectedVal
              },
              success: function(data) {
              
                let str = '';
                str += '<div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">'
                  + '<select name="product" id="product-filter" class="form-select" aria-label="Default select example">'
                  + '<option value="999">--Sản phẩm (Tất cả)--</option>';
                  data.forEach(item => {
                    // console.log(item['id'])
                    str += '<option value="' + item['id'] + '">' + item['name'] + '</option>';
                    });
                str  += '</select>'
                  + '</div>';

                  $(str).appendTo(".filter-order");
              }
          });
      } else {
        if ($('#product-filter').length > 0) {
          $('#product-filter').parent().remove();
        }
       
      }
  });

});


</script>
