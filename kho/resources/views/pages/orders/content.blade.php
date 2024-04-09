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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<div class="tab-content rounded-bottom">
<div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1001">

    <div class="row ">
      <div class="col col-4">
        {{-- <a class="btn btn-primary" href="{{route('add-orders')}}" role="button">+ Thêm đơn</a> --}}
        <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" role="button">+ Thêm đơn</a>
        {{-- <form id="myform" class="form-wizard">
          <h2 class="form-wizard-heading">BootStap Wizard Form</h2>
          <input type="text" value=""/>
          <input type="submit"/>
        </form> --}}
      
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
              {{-- <div class="modal-body">
                <h5>Popover in a modal</h5>
                <p>This <a href="#" role="button" class="btn btn-secondary popover-test" title="Popover title" data-bs-content="Popover body content is set in this attribute.">button</a> triggers a popover on click.</p>
                <hr>
                <h5>Tooltips in a modal</h5>
                <p><a href="#" class="tooltip-test" title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="Tooltip">that link</a> have tooltips on hover.</p>
              </div> --}}
              <iframe src="{{URL::to('them-don-hang')}}" frameborder="0"></iframe>
              {{-- <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div> --}}
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
          {!! $list->links() !!}
        </div>
      </div>
    </div>
</div>
  
