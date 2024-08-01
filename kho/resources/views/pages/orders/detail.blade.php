@extends('layouts.default')
@section('content')

<style>
    .green span {
    width: 80px;
    display: inline-block; 
    color: #fff;
    background: #0f0;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #0f0;
    font-weight: 700;
  }

  .red span {
    text-align: center;
    width: 80px;
    display: inline-block; 
    color: #ff0000;
    background: #fff;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #ff0000;
    font-weight: 700;
  }

  .orange span {
    /* width: 80px; */
    display: inline-block;
    color: #fff;
    background: #ffbe08;
    padding: 3px;
    border-radius: 8px;
    border: 1px solid #fff;
    font-weight: 700;
  }

  table.order .first-col {
    width: 15%;
  }

  @media only screen and (max-width: 600px) {
    table.order .first-col {
    width: 50%;
  }
}
</style>
<div class="body flex-grow-1 px-3">
    <div class="row">
        <div id="notifi-box" class="hidden alert alert-success print-error-msg">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"><span><strong>Chi tiết đơn hàng #{{$order->id}} - {{date_format($order->created_at,"d-m-Y ")}}</strong></span>
                </div>
            </div>
            <table class="table order">
                <tbody>
                <tr>
                    <td class="first-col">Mã vận đơn</td>
                    <td>

                        <?php $isMappingShip = Helper::isMappingShippByOrderId($order->id);?>
                        @if (!$isMappingShip)
                        <a href="{{URL::to('tao-van-don/'. $order->id)}}" class="btn btn-warning ms-1">+ Tạo vận đơn</a>
                        @else
                        <a href="{{URL::to('chi-tiet-van-don/'. $isMappingShip->id)}}" class="btn btn-warning ms-1">Xem vận đơn {{$isMappingShip->vendor_ship}} - {{$isMappingShip->order_code}}</a>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td class="first-col">Người tạo</td>
                    <td>{{Helper::getUserByID($order->assign_user)->real_name}}</td>
                </tr>
                <tr>
                    <td class="first-col">Số điện thoại</td>
                    <td>{{$order->phone}}</td>
                </tr>
                <tr>
                    <td class="first-col">Tên khách hàng</td>
                    <td>{{$order->name}}</td>
                </tr>
                <tr>
                    <td class="first-col">Giới tính</td>
                    <td><?= $order->sex == 0 ? 'Nam' : 'Nữ'; ?></td>
                </tr>
                <tr>
                    <td class="first-col">Địa chỉ</td>
                    <td>{{$order->address}}</td>
                </tr>
                <tr>
                    <td class="first-col">Tổng tiền</td>
                    <td>{{number_format($order->total)}}đ</td>
                </tr>

                <tr>

                    <?php $listStatus = Helper::getListStatus(); 
                    // dd($listStatus); 
                    $styleStatus = [
                        0 => 'red',
                        1 => 'white',
                        2 => 'orange',
                        3 => 'green',
                        ];
                    ?>
                    <td class="first-col">Trạng thái</td>
                    <td class="{{$styleStatus[$order->status]}}"><span>{{$listStatus[$order->status]}}</span></td>
                </tr>
                <tr>
                    <td class="first-col">Ghi chú</td>
                    <td>{{$order->note}}</td>
                </tr>
                
                </tbody>
            </table>

            <table class="table order">
                <tbody>
                    <tr><th>Sản phẩm:</th></tr>
                    <?php 
                    foreach (json_decode($order->id_product) as $item) {
                        $product = getProductByIdHelper($item->id);
                        if ($product) {
                    ?>
                   
                <tr>
                    <td class="first-col">{{$product->name}}</td>
                    <td>{{$item->val}}</td>
                </tr>
                <?php }} ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

@stop