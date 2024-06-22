@extends('layouts.default')
@section('content')

<link rel="stylesheet" type="text/css" href="{{asset('public/css/dashboard.css')}}" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('public/js/moment.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/daterangepicker.css')}}" /> 

<style>
  .header.header-sticky {
    position: unset;
  }
    .fs-5 {
        font-size: 2.0736rem !important;
    }

    .weekly-sales span {
        color: #00894f;
        background-color: #d9f8eb;
    }

    .total-order svg {
        height: 1em;
    }

    .name-total {
      cursor: pointer;
    }
    .filter-button svg{
      transform: rotate(90deg)
    }

    .total-sales .card-body {
      padding: 10px;
    }
    
    .filter-type-button {
      border: 1px solid #9da5b1;
      border-radius: 0.375rem;
    }

    .filter-type-button:hover {
      border: 1px solid #9da5b1;
      background: #fff;
    }

    .open .dropdown-menu {
      display: block;
    }

    .dropdown-menu>li>a {
      display: block;
      padding: 3px 20px;
      clear: both;
      font-weight: 400;
      line-height: 1.42857143;
      color: #333;
      white-space: nowrap;
    }
  .dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover {
      color: #262626;
      text-decoration: none;
      background-color: #f5f5f5;
  }
  .caret {
      display: inline-block;
      width: 0;
      height: 0;
      margin-left: 2px;
      vertical-align: middle;
      border-top: 4px dashed;
      border-top: 4px solid\9;
      border-right: 4px solid transparent;
      border-left: 4px solid transparent;
  }

  #dateTotal {
    /* width: 13%;zxc */
  }
  #daterange {
    color: #000;
  }
  
  
  .filter-order .daterange {
    /* min-width: 230px; */
  }

  .loader img {
    position: relative;
    top: unset;
    right: unset;
  }

  @media only screen and (max-width: 600px) {
    .px-3 {
      padding: 0 !important;
    }

    .dropdown.dropdown-filter {
      white-space: nowrap;
    }
  }
</style>

<?php $checkAll = isFullAccess(Auth::user()->role);?>
<?php $totalSum = $avgSum = $newContact = $newOrder = $newRate = $newProduct = $newTotal = $oldAvg = $oldTotal = $oldProduct = $oldRate = $newAvg = $oldContact = $oldOrder= 0;
  $sumNewCustomer = $sumOldCustomer = [
    'contact' => 0,
    'order' => 0,
    'rate' => 0,
    'product' => 0,
    'total' => 0,
    'avg' => 0,
  ];

  if (isset($dataSale)) {
    foreach ($dataSale as $data) {
      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // die();
      if (isset($data['new_customer'])) {
        $newContact += $data['new_customer']['contact'];
        $newOrder += $data['new_customer']['order'];
        $newProduct += $data['new_customer']['product'];
        $newTotal += ($data['new_customer']['total']);
      }
      if (isset($data['old_customer'])) {
        $oldContact += $data['old_customer']['contact'];
        $oldOrder += $data['old_customer']['order'];
        $oldRate += $data['old_customer']['rate'];
        $oldProduct += $data['old_customer']['total'];
        $oldTotal += ($data['old_customer']['total']);
      }
    }

    $sumNewCustomer['contact'] = $newContact;
    $sumNewCustomer['order'] = $newOrder;
    if ($newContact > 0) {
      $newRate = $newOrder / $newContact * 100;
      $sumNewCustomer['rate'] = round($newRate, 2);
    }

    $sumNewCustomer['product'] = $newProduct;
    $sumNewCustomer['total'] = round($newTotal, 0);
    $sumNewCustomer['avg'] = round((($newOrder != 0) ? $newTotal/$newOrder : 0), 0);

    $sumOldCustomer['contact'] = $oldContact;
    $sumOldCustomer['order'] = $oldOrder;
    if ($oldContact > 0) {
      $oldRate = $oldOrder / $oldContact * 100;
      $sumOldCustomer['rate'] = round($oldRate, 2);
    }

    $sumOldCustomer['product'] = $oldProduct;
    $sumOldCustomer['total'] = round($oldTotal, 0);
    $sumOldCustomer['avg'] = round((($oldOrder != 0) ? $oldTotal/$oldOrder : 0), 0);

    $totalSum = $oldTotal + $newTotal;
    if ($oldOrder + $newOrder) {
      $avgSum = $totalSum / ($oldOrder + $newOrder);
    }
  }


?>
<div class="body flex-grow-1 px-3">
  <div class="container-lg">
    <div class="row mb-1 filter-order">
      <div class="col-xs-12 col-sm-6 col-md-2 form-group daterange mb-1">
        <input id="daterange" class=" btn btn-outline-secondary" type="text" name="daterange"/>
      </div>
     
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
                    'name' => 'Ladipage ruoc-dong',
                    'id' => 'ruoc-dong',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
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
              <option value="999">--Chọn Marketing--</option>
              <option value="1">a.Nguyên</option>
              <option value="2">a.Tiễn</option>
          </select>
      </div>
      
      @endif
      
      @if ($checkAll)
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="sale" id="sale-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Chọn Sale--</option>
          @if (isset($sales))
            @foreach($sales as $sale)
            <option value="{{$sale->id}}">{{($sale->real_name) ? : $sale->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
      @endif

      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="status" id="status-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Chọn Trạng Thái--</option>
          <option value="1">Chưa giao vận</option>
          <option value="2">Đang giao</option>
          <option value="3">Hoàn tất</option>
          <option value="0">Huỷ</option>
        </select>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="category" id="category-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Chọn mục--</option>
          @if (isset($category))
            @foreach($category as $cate)
            <option value="{{$cate->id}}">{{$cate->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div>
    <div class="row mb-1">
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <button type="button" id="btn-filter"  class="btn btn-outline-primary"><svg class="icon me-2">
          <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-filter')}}"></use>
        </svg>Lọc</button>
        <a  class="btn btn-outline-danger" href="{{route('home')}}"><strong>X</strong></a>
        <span class="loader hidden">
          <img src="https://kho.phanboncanada.online/public/images/loader-home.gif" alt="">
        </span>
      </div>
    </div>

    <div class="row">
      <div class="box-body" style="padding-top: 0px;">
        {{-- <div id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_divGiaoVan" class="row mt15 row-flex">
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_chogiao">CHỜ GIAO</th>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_ChoGiao">73</span></td>
                    </tr>
                </tbody></table>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_ty_le_huy">HỦY VẬN ĐƠN</th>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblHuyVanDon">0/1957</span>
                            (<span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_TyLeHuyDon">0 %</span>)
                        </td>
                    </tr>
                </tbody></table>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_danggiao">ĐANG GIAO</th>
                    </tr>
                    <tr>
                        <td style="width: 30%" class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_DangGiao">33</span></td>
                    </tr>
                </tbody></table>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_dagiao">ĐÃ GIAO</th>
                    </tr>
                    <tr>
                        <td style="width: 30%" class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_DaGiao">1484</span></td>
                    </tr>
                </tbody></table>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_thutien">ĐÃ THANH TOÁN</th>
                    </tr>
                    <tr>
                        <td style="width: 30%" class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_DaThanhToan">6</span></td>
                    </tr>
                </tbody></table>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4">
                <table class="table table-bordered table-multi-select tabledata">
                    <tbody><tr>
                        <th class="text-center header_ty_le_hoan">ĐÃ HOÀN</th>
                    </tr>
                    <tr>
                        <td style="width: 30%" class="text-center">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_DaHoan">9/1957</span>
                            (<span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblGiaoHang_TyLeHoanDon">0.46 %</span>)
                        </td>
                    </tr>
                </tbody></table>
            </div>
        </div> --}}
        <div style="clear: both;"></div>
<?php if ($dataSale) {
    /** lấy ra trung bình đơn lớn nhất của trong list sale**/
    $maxAvgSum = $dataSale[0]['summary_total']['avg'];
    foreach ($dataSale as $data) {
        if ($data['summary_total']['avg'] > $maxAvgSum) {
            $maxAvgSum = $data['summary_total']['avg'];
        }
    } 
?>      
        <div style="clear: both; margin-bottom: 15px;"></div>
        <div class="dragscroll1 tableFixHead table_sale" style="height: 570px;">
          <table class="table table-bordered table-multi-select" id="tableReportSale">
            <thead>
              <tr style="cursor: grab;" class="drags-area">
                  <th class="text-center" style="width: 50px;"></th>
                  <th class="text-center" style="width: 10%"></th>
                  <th class="text-center" rowspan="1" colspan="6">KHÁCH HÀNG MỚI</th>
                  <th class="text-center" rowspan="1" colspan="6">KHÁCH HÀNG CŨ</th>
                  <th class="text-center" rowspan="1" colspan="2" style="width: 10%;">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDoanhSo_Header_Sale">DOANH SỐ TỔNG</span></th>
                  {{-- <th class="text-center" rowspan="1" colspan="5" style="width: 20%;">GIAO HÀNG</th>
                  <th class="text-center" rowspan="2" colspan="1" style="width: 5%">KPI</th>
                  <th class="text-center" rowspan="2" colspan="1" style="width: 5%">Tỉ lệ KPI (%)</th> --}}
              </tr>
              <tr style="cursor: grab;" class="drags-area t28">
                  <th class="text-center" style="width: 50px;">STT</th>
                  <th class="text-center" style="width: 10%">SALE</th>

                  
                  <th class="text-center">Contact</th>
                  <th class="text-center">Đơn chốt</th>
                  <th class="text-center no-wrap">Tỉ lệ chốt (%)</th>
                  <th class="text-center">Số sản phẩm</th>
                  <th class="text-center">Doanh số</th>
                  <th class="text-center">Giá trị đơn</th>

                  
                  <th class="text-center">Contact</th>
                  <th class="text-center">Đơn chốt</th>
                  <th class="text-center no-wrap">Tỉ lệ chốt (%)</th>
                  <th class="text-center">Số sản phẩm</th>
                  <th class="text-center">Doanh số</th>
                  <th class="text-center">Giá trị đơn</th>

                  
                  <th class="text-center" style="width: 5%;">Doanh số</th>
                  <th class="text-center" style="width: 5%;">Giá trị TB đơn</th>

                  
                  {{-- <th class="text-center" style="width: 5%;">Tỉ lệ hủy</th>
                  <th class="text-center" style="width: 4%;">Tỉ lệ hoàn</th>
                  <th class="text-center " style="width: 3%;">Phí COD</th>
                  <th class="text-center " style="width: 3%;">Hỗ trợ COD</th>
                  <th class="text-center " style="width: 3%;">Đặt cọc</th> --}}
              </tr>
                
              <tr class="rowsum drags-area t72" id="tr-sum-sale" style="cursor: grab;">
                  <td colspan="2" class="text-center font-weight-bold">Tổng: </td>

                  {{-- khách mới --}}
                  <td class="text-center font-weight-bold">
                    <span>{{$sumNewCustomer['contact']}}</span></td>
                  <td class="text-center font-weight-bold">
                    <span>{{$sumNewCustomer['order']}}</span></td>
                  <td class="text-center font-weight-bold">
                    <span>{{$sumNewCustomer['rate']}} %</span></td>
                  <td class="text-center font-weight-bold">
                    <span>{{$sumNewCustomer['product']}}</span></td>
                  <td class="text-center font-weight-bold">
                    <span>{{number_format($sumNewCustomer['total'])}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{number_format($sumNewCustomer['avg'])}}</span></td>

                  {{-- khách cũ --}}
                  <td class="text-center font-weight-bold">
                      <span>{{$sumOldCustomer['contact']}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{$sumOldCustomer['order']}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{$sumOldCustomer['rate']}} %</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{$sumOldCustomer['product']}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{number_format($sumOldCustomer['total'])}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{number_format($sumOldCustomer['avg'])}}</span></td>
                  <td class="text-center font-weight-bold">

                      {{-- doanh số tổng --}}
                      <span>{{number_format($totalSum)}}</span></td>
                  <td class="text-center font-weight-bold">
                      <span>{{number_format($avgSum)}}</span></td>

                  
                  {{-- <td class="text-center font-weight-bold >">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongHuy_S">0 %</span></td>
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongHoan_S">0.46 %</span></td>
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblCOD_S">290,846,679</span></td>
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblHoTroCOD_S">291,284,598</span></td>
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDatCoc_S">17,920,000</span></td>
                  
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDoanhSoKPI_S">0</span></td>
                  <td class="text-center font-weight-bold">
                      <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTyLeDSTTKPIDS_S">∞ %</span></td> --}}
              </tr>
            </thead>
            
            <tbody id="body-sale">

            <?php $i = 1; 
            //   dd($dataSale);

                foreach ($dataSale as $data) {
              ?>
                <tr>
                  <td class="text-center">{{$i}}</td>
                  <td>{{$data['name']}}</td>
                  <td class="tdProgress tdSoContact">
                    <div class="box-progress">
                      <div class="progress">

                        <?php $perCentContactNew = ($sumNewCustomer['contact'] != 0) ? ($data['new_customer']['contact'] / $sumNewCustomer['contact'] * 100) : 0;?>

                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentContactNew}}%"></div>
                      </div>
                      <span class="progress-text">{{$data['new_customer']['contact']}}</span>
                    </div>
                  </td>
                  <td class="tdProgress tdSoChotDon">
                    <div class="box-progress">
                      <div class="progress">

                        <?php $perCentOrderNew =  ($sumNewCustomer['order'] != 0) ? ($data['new_customer']['order'] / $sumNewCustomer['order'] * 100) : 0;?>

                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentOrderNew}}%"></div>
                      </div>
                      <span class="progress-text">{{$data['new_customer']['order']}}</span>
                    </div>
                  </td>
                  <td class="tdProgress tdTyLeChotDon">
                    <div class="box-progress">
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$data['new_customer']['rate']}}%"></div>
                      </div>
                      <span class="progress-text">{{$data['new_customer']['rate']}} %</span>
                    </div>
                  </td>
                  <td class="tdProgress tdSoSanPham">
                    <div class="box-progress">
                      <div class="progress">
                       
                        <?php $perCentProductNew = ($sumNewCustomer['product'] != 0) ? ($data['new_customer']['product'] / $sumNewCustomer['product'] * 100) : 0;?>

                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentProductNew}}%"></div>
                      </div>
                      <span class="progress-text">{{$data['new_customer']['product']}}</span>
                    </div>
                  </td>
                  <td class="tdProgress tdDoanhSo">
                    <div class="box-progress">
                      <div class="progress">

                        <?php $perCentTotalNew = ($sumNewCustomer['total'] != 0) ? ($data['new_customer']['total'] / $sumNewCustomer['total'] * 100) : 0;?>

                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentTotalNew}}%"></div>
                      </div>
                      <span class="progress-text">{{number_format($data['new_customer']['total'])}}</span>
                    </div>
                  </td>
                  <td class="tdProgress tdGiaTriDon">
                    <div class="box-progress">
                        <div class="progress">

                          <?php $perCentAvgNew = ($sumNewCustomer['avg'] != 0) ? ($data['new_customer']['avg'] / $sumNewCustomer['avg'] * 100) : 0;?>
                          <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentAvgNew}}%"></div>
                        </div>
                        <span class="progress-text">{{number_format($data['new_customer']['avg'])}}</span>
                    </div>
                  </td>
                  
                  <td class="tdProgress tdSoContact">
                    <div class="box-progress">
                        <div class="progress">

                          <?php $perCentContactOld = ($sumOldCustomer['contact'] != 0) ? ($data['old_customer']['contact'] / $sumOldCustomer['contact'] * 100) : 0;?>
                          <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentContactOld}}%"></div>
                        </div>
                        <span class="progress-text">{{$data['old_customer']['contact']}}</span>
                    </div>
                  </td>
                  <td class="tdProgress tdSoChotDon">
                    <div class="box-progress">
                      <div class="progress">
                        
                        <?php $perCentOrderOld = ($sumOldCustomer['order'] != 0) ? ($data['old_customer']['order'] / $sumOldCustomer['order'] * 100) : 0;?>
                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentOrderOld}}%"></div>
                      </div>
                      <span class="progress-text">{{$data['old_customer']['order']}}</span></div>
                  </td>
                  <td class="tdProgress tdTyLeChotDon">
                      <div class="box-progress">
                          <div class="progress">
                              <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$data['old_customer']['rate']}}%"></div>
                          </div>
                          <span class="progress-text">{{$data['old_customer']['rate']}} %</span>
                      </div>
                  </td>
                  <td class="tdProgress tdSoSanPham">
                      <div class="box-progress">
                          <div class="progress">

                            <?php $perCentProductOld = ($sumOldCustomer['product'] != 0) ? ($data['old_customer']['product'] / $sumOldCustomer['product'] * 100) : 0;?>
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentProductOld}}%"></div>
                          </div>
                          <span class="progress-text">{{$data['old_customer']['product']}}</span>
                      </div>
                  </td>
                  <td class="tdProgress tdDoanhSo">
                      <div class="box-progress">
                          <div class="progress">

                            <?php $perCentTotalOld = ($sumOldCustomer['total'] != 0) ? ($data['old_customer']['total'] / $sumOldCustomer['total'] * 100) : 0;?>
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentTotalOld}}%"></div>
                          </div>
                          <span class="progress-text">{{$data['old_customer']['total']}}</span>
                      </div>
                  </td>
                  <td class="tdProgress tdGiaTriDon">
                      <div class="box-progress">
                          <div class="progress">

                            <?php $perCentAvgOld = ($sumOldCustomer['avg'] != 0) ? ($data['old_customer']['avg'] / $sumOldCustomer['avg'] * 100) : 0;?>
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentAvgOld}}%"></div>
                          </div>
                          <span class="progress-text">{{$data['old_customer']['avg']}}</span>
                      </div>
                  </td>

                  <td class="tdProgress tdDoanhSoTong">
                      <div class="box-progress">
                          <div class="progress">

                            <?php $perCentTotalSum = ($totalSum  != 0) ? ($data['summary_total']['total'] / $totalSum * 100) : 0;?>
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentTotalSum}}%"></div>
                          </div>
                          <span class="progress-text">{{number_format($data['summary_total']['total'])}}</span>
                      </div>
                  </td>

                  <td class="tdProgress tdGiaTriDon">
                      <div class="box-progress">
                          <div class="progress">

                            <?php $perCentAvgSum = ($maxAvgSum  != 0) ? ($data['summary_total']['avg'] / $maxAvgSum * 100) : 0;?>
                            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: {{$perCentAvgSum}}%"></div>
                          </div>
                          <span class="progress-text">{{number_format($data['summary_total']['avg'])}}</span>
                      </div>
                  </td>
                </tr>    
              <?php 
                $i++;
                }
              ?>

            </tbody>
          </table>
        </div>
<?php } ?>
        <div style="height: 15px; clear: both;"></div>

        <!--div class="dragscroll1 tableFixHead table_marketing" style="height: 480px;">
            <table class="table table-bordered table-multi-select" id="tableReportMarketing">
                <thead>
                    <tr style="cursor: grab;" class="drags-area">
                        <th class="text-center" style="width: 35px;"></th>
                        <th class="text-center no-wrap" style="min-width: 10%"></th>
                        <th class="text-center" rowspan="1" colspan="9">KHÁCH HÀNG MỚI</th>
                        
                        <th class="text-center" rowspan="1" colspan="2">KHÁCH HÀNG CŨ</th>
                        
                        <th class="text-center" rowspan="1" colspan="3">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDoanhSo_Header_Marketing">DOANH SỐ TỔNG</span></th>
                        
                        <th class="text-center" rowspan="1" colspan="5">GIAO HÀNG</th>
                        
                        <th class="text-center" rowspan="2" colspan="1" style="width: 5%">KPI</th>
                        
                        <th class="text-center" rowspan="2" colspan="1" style="width: 5%">Tỉ lệ KPI (%)</th>
                        
                    </tr>
                    <tr style="cursor: grab;" class="drags-area t28">
                        
                        <th class="text-center" style="width: 35px;">STT</th>
                        <th class="text-center" style="width: 10%">MARKETING</th>
                        <th class="text-center" style="width: 4%;">Ngân sách</th>
                        <th class="text-center" style="width: 4%;">Contact</th>
                        <th class="text-center" style="width: 4%;">Giá contact</th>
                        <th class="text-center" style="width: 4%;">Đơn chốt</th>
                        <th class="text-center" style="width: 5%;">Tỉ lệ chốt đơn (%)</th>
                        <th class="text-center" style="width: 4%;">Số sản phẩm</th>
                        <th class="text-center" style="width: 5%;">Doanh số</th>
                        <th class="text-center" style="width: 5%;">Giá trị đơn</th>
                        <th class="text-center" style="width: 4%;">Tỉ lệ NS/DS(%)</th>

                        
                        <th class="text-center" style="width: 5%;">Doanh số</th>
                        <th class="text-center" style="width: 5%;">Giá trị đơn</th>

                        
                        <th class="text-center" style="width: 5%;">Doanh số</th>
                        <th class="text-center" style="width: 4%;">Tỉ lệ NS/DS (%)</th>
                        <th class="text-center" style="width: 7%;">Giá trị đơn</th>
                        
                        <th class="text-center" style="width: 4%;">Tỉ lệ hủy</th>
                        <th class="text-center" style="width: 3%;">Tỉ lệ hoàn</th>
                        <th class="text-center " style="width: 3%;">Phí COD</th>
                        <th class="text-center " style="width: 3%;">Hỗ trợ COD</th>
                        <th class="text-center " style="width: 3%;">Đặt cọc</th>
                    </tr>
                    <tr class="rowsum drags-area t72" style="cursor: grab;">
                        <td colspan="2" class="text-center font-weight-bold">Tổng: </td>
                        
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongNganSach_KHM_M">0</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongSoContact_KHM_M">4,627</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongSoGiaContact_KHM_M">0</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongSoSoDonChot_KHM_M">1,793</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongTyLeChotDon_KHM_M">38.75 %</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongSoSanPham_KHM_M">11,818</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongDoanhSo_KHM_M">3,874,057,000</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongGiaTriDon_KHM_M">2,160,656.44</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblNganSachDoanhSo_KHM_M">0 %</span></td>

                        
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongDoanhSo_KHC_M">758,846,000</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongGiaTriDon_KHC_M">3,201,881.86</span></td>

                        
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongDoanhSo_M">4,632,903,000</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblNganSachDoanhSo_M">0 %</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongGiaTriDon_M">2,282,218.23</span></td>

                        
                        <td class="text-center font-weight-bold >">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongHuy_M">0 %</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTongHoan_M">0.46 %</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblCOD_M">290,846,679</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblHoTroCOD_M">291,284,598</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDatCoc_M">17,920,000</span></td>
                        
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblDoanhSoKPI_M">0</span></td>
                        <td class="text-center font-weight-bold">
                            <span id="dnn_ctr1652_Main_DashboardLoader_DashboardCEOV2_lblTyLeDSTTKPIDS_M">∞ %</span></td>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>
                        <td class="text-center">
                            1
                        </td>
                        
                        
                        <td>Di Di (bio.mkt4)
                        </td>
                        
                        <td class="tdProgress tdNganSach">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0</span>
                            </div>
                        </td>
                        <td class="tdProgress tdSoContact">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">1,587</span>
                            </div>
                        </td>
                        <td class="tdProgress tdGiaContact">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0
                            </span></div>
                        </td>
                        <td class="tdProgress tdSoChotDon">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">644
                            </span></div>
                        </td>
                        <td class="tdProgress tdTyLeChotDon">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 40.57971%"></div>
                                </div>
                                <span class="progress-text">40.58 %</span>
                            </div>
                        </td>
                        <td class="tdProgress tdSoSanPham">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 3872%"></div>
                                </div>
                                <span class="progress-text">3,872 </span>
                            </div>
                        </td>
                        <td class="tdProgress tdDoanhSo">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">1,295,442,000</span>
                            </div>
                        </td>
                        <td class="tdProgress tdGiaTriDon">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 55.1413349950932%"></div>
                                </div>
                                <span class="progress-text">2,011,555.9 </span>
                            </div>
                        </td>
                        <td class="tdProgress tdTyLeNganSachDoanhSo">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0 %</span>
                            </div>
                        </td>

                        
                        <td class="tdProgress tdDoanhSo">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 514.207423133985%"></div>
                                </div>
                                <span class="progress-text">312,752,000</span>
                            </div>
                        </td>
                        <td class="tdProgress tdGiaTriDon">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 1.29870129870121%"></div>
                                </div>
                                <span class="progress-text">4,061,714.29</span>
                            </div>
                        </td>

                        
                        <td class="tdProgress tdDoanhSo">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">1,608,194,000 </span>
                            </div>
                        </td>
                        <td class="tdProgress tdTyLeNganSachDoanhSo">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0 %</span>
                            </div>
                        </td>
                        <td class="tdProgress tdGiaTriDon">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 60.9120707121652%"></div>
                                </div>
                                <span class="progress-text">2,230,504.85</span>
                            </div>
                        </td>
                        
                        <td class="tdProgress tdTyLeHuy">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0%</span>
                            </div>
                        </td>
                        <td class="tdProgress tdTyLeHoan">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 12.0887385164505%"></div>
                                </div>
                                <span class="progress-text">0.14%</span>
                            </div>
                        </td>
                        <td class="tdProgress tdCOD ">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">100,754,034</span>
                            </div>
                        </td>
                        <td class="tdProgress tdHoTroCOD ">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">99,632,234</span>
                            </div>
                        </td>
                        <td class="tdProgress tdHoTroCOD ">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                                <span class="progress-text">5,840,000</span>
                            </div>
                        </td>
                        
                        <td class="tdProgress tdDoanhSoKPI">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0</span>
                            </div>
                        </td>
                        <td class="tdProgress tdTyLeDoanhSoKPI">
                            <div class="box-progress">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                </div>
                                <span class="progress-text">0 %</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="height: 5px;"></div-->
    </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="{{asset('public/js/dateRangePicker/daterangepicker.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('public/js/dateRangePicker/dateRangePicker-vi.js')}}"></script> --}}
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

    $('input[name="daterange"]').change(function () {
    });
  
    $("#type-period").click(function () {
      $('#filter-type-button').html('Trong khoản <span class="caret"></span>');
      $('#dateTotal').hide();
      $('#filter-order').hide();
      $('#daterange').show();
      
    });
    $("#type-day").click(function () {
      $('#filter-type-button').html('Theo ngày <span class="caret"></span>');
      $('#dateTotal').show();
      $('#filter-order').show();
      $('#daterange').hide();
    });

    $("input[name='filterTotal']").click(function () { 
      $(".filter-order label").removeClass('active');
      $(this).parent().addClass('active');
      $('.loader').show();
      var _token  = $("input[name='_token']").val();
      let type    =  $(this).val();
      let date    = $("input[name='dateTotal']").val();
      $.ajax({
            url: "{{ route('filter-total-sales') }}",
            type: 'GET',
            data: {
              _token: _token,
              type,
              date
            },
            success: function(data) {
              console.log(data);
              if (!$.isEmptyObject(data.totalSum)) {
                $("#totalSum").text(data.totalSum);
                $(".percentTotalDay").text(data.percentTotal);
                $(".countOrders").text(data.countOrders);
                $(".percentCountDay").text(data.percentCount);
                $(".avgOrders").text(data.avgOrders);
                $(".percentAvg").text(data.percentAvg);
              }
              $('.loader').hide();
            }
        });
    });

    $("#btn-filter").on( "click", function() {
        let value =  $("input[name='daterange']").val();
        let arr = value.split("-");

        var _token    = $("input[name='_token']").val();
        var status    = $("select[name='status']").val();
        var category  = $("select[name='category']").val();
        var product   = $("select[name='product']").val();
        var sale      = $("select[name='sale']").val();
        var mkt       = $("select[name='mkt']").val();
        var src       = $("select[name='src']").val();

        $('.loader').show();
        $.ajax({
            url: "{{ route('filter-total-sales') }}",
            type: 'GET',
            data: {
                _token: _token,
                type: 'daterange',
                date: arr,
                status,
                category,
                product,
                sale,
                mkt,
                src
            },
            success: function(data) {
                // console.log(data)
                if (data.data.length > 0) {
                    var str = '';
                    var newCusomerTrSum = data.trSum.new_customer;
                    var oldCusomerTrSum = data.trSum.old_customer;
                    var summaryCusomerTrSum = data.trSum.sumary_total;
                    console.log(data.data);
                    var maxAvcElem = data.data[0].summary_total.avg;

                     /** lấy ra trung bình đơn lớn nhất của trong list sale**/
                    data.data.forEach((element, k) => {
                        if (element.summary_total.avg > maxAvcElem) {
                            maxAvcElem = element.summary_total.avg;
                        }
                    });
                    data.data.forEach((element, k) => {
                        // console.log(element);
                        // console.log(k);
                        // console.log((newCusomerTrSum.contact != 0));
                        perCentContactNew = (newCusomerTrSum.contact != 0) ? (element.new_customer.contact / newCusomerTrSum.contact * 100) : 0;
                        perCentOrderNew =  (newCusomerTrSum.order != 0) ? (element.new_customer.order / newCusomerTrSum.order * 100) : 0;
                        perCentProductNew = (newCusomerTrSum.product != 0) ? (element.new_customer.product / newCusomerTrSum.product * 100) : 0;
                        perCentTotalNew = (newCusomerTrSum.total != 0) ? (element.new_customer.total / newCusomerTrSum.total * 100) : 0;
                        perCentAvgNew = (newCusomerTrSum.avg != 0) ? (element.new_customer.avg / newCusomerTrSum.avg * 100) : 0;

                        perCentContactOld = (oldCusomerTrSum.contact != 0) ? (element.old_customer.contact / oldCusomerTrSum.contact * 100) : 0;
                        perCentOrderOld =  (oldCusomerTrSum.order != 0) ? (element.old_customer.order / oldCusomerTrSum.order * 100) : 0;
                        perCentProductOld = (oldCusomerTrSum.product != 0) ? (element.old_customer.product / oldCusomerTrSum.product * 100) : 0;
                        perCentTotalOld = (oldCusomerTrSum.total != 0) ? (element.old_customer.total / oldCusomerTrSum.total * 100) : 0;
                        perCentAvgOld = (oldCusomerTrSum.avg != 0) ? (element.old_customer.avg / oldCusomerTrSum.avg * 100) : 0;

                        perCentTotalSum = (summaryCusomerTrSum.total != 0) ? (element.summary_total.total / summaryCusomerTrSum.total * 100) : 0;
                        perCentAvgSum = (maxAvcElem.avg != 0) ? (element.summary_total.avg / maxAvcElem * 100) : 0;
                            
                        str += '<tr>'
                            + '<td class="text-center">' + (k+1) + '</td>'
                            + '<td>' + element.name + '</td>'
                            + '<td class="tdProgress tdSoContact"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentContactNew + '%"></div>'
                            + '</div><span class="progress-text">' +  element.new_customer.contact + '</span></div></td>'
                            + '<td class="tdProgress tdSoChotDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentOrderNew + '%"></div>'
                            + '</div><span class="progress-text">' +  element.new_customer.order + '</span></div></td>'
                            + '<td class="tdProgress tdTyLeChotDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' +  element.new_customer.rate + '%"></div>'
                            + '</div><span class="progress-text">' +  element.new_customer.rate + '%</span></div></td>'
                            + '<td class="tdProgress tdSoSanPham"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentProductNew + '%"></div>'
                            + '</div><span class="progress-text">' +  element.new_customer.product + '</span></div></td>'
                            + '<td class="tdProgress tdDoanhSo"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalNew + '%"></div>'
                            + '</div><span class="progress-text">' +  number_format_js(element.new_customer.total) + '</span></div></td>'
                            + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgNew + '%"></div>'
                            + '</div><span class="progress-text">' + number_format_js(element.new_customer.avg) + '</span></div></td>';

                        
                        str += '<td class="tdProgress tdSoContact"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentContactOld + '%"></div>'
                            + '</div><span class="progress-text">' + element.old_customer.contact + '</span></div></td>'
                            + '<td class="tdProgress tdSoChotDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentOrderOld + '%"></div>'
                            + '</div><span class="progress-text">' + element.old_customer.order + '</span></div></td>'
                            + '<td class="tdProgress tdTyLeChotDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + element.old_customer.order + '%"></div>'
                            + '</div><span class="progress-text">' + element.old_customer.rate + '%</span></div></td>'
                            + '<td class="tdProgress tdSoSanPham"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentProductOld + '%"></div>'
                            +' </div><span class="progress-text">' + element.old_customer.product + '</span></div></td>'
                            + '<td class="tdProgress tdDoanhSo"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalOld + '%"></div>'
                            + '</div><span class="progress-text">' + number_format_js(element.old_customer.total) + '</span></div></td>'
                            + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgOld + '%"></div>'
                            + '</div><span class="progress-text">' + number_format_js(element.old_customer.avg) + '</span></div></td>';

                        str += '<td class="tdProgress tdDoanhSoTong"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalSum + '%"></div>'
                            + '</div><span class="progress-text">' + number_format_js(element.summary_total.total) + '</span></div></td>'
                            + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                            + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgSum + '%"></div>'
                            + '</div><span class="progress-text">' + number_format_js(element.summary_total.avg) + '</span></div></td></tr>';
                        
                    });
                    console.log(maxAvcElem);

                    $("#body-sale").html(str);

                    var strTdSum = '';
                    strTdSum += '<td colspan="2" class="text-center font-weight-bold">Tổng: </td>'
                        + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.contact + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.order + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.rate + '%</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.product + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(newCusomerTrSum.total) + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(newCusomerTrSum.avg) + '</span></td>';

                        
                    strTdSum += '<td class="text-center font-weight-bold"><span>' + oldCusomerTrSum.contact + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + oldCusomerTrSum.order + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + oldCusomerTrSum.rate + '%</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + oldCusomerTrSum.product + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(oldCusomerTrSum.total) + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(oldCusomerTrSum.avg) + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.total) + '</span></td>'
                        + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.avg) + '</span></td>';

                    $("#tr-sum-sale").html(strTdSum);

                }
                if (!$.isEmptyObject(data.totalSum)) {
                    $("#totalSum").text(data.totalSum);
                    $(".percentTotalDay").text(data.percentTotal);
                    $(".countOrders").text(data.countOrders);
                    $(".percentCountDay").text(data.percentCount);
                    $(".avgOrders").text(data.avgOrders);
                    $(".percentAvg").text(data.percentAvg);
                    $(".sumProduct").text(data.sumProduct);
                    $(".rateSuccess").text(data.rateSuccess);
                    $(".countSaleCare").text(data.countSaleCare);
                }
                $('.loader').hide();
            }
        });
    });
    
    $("input[name='dateTotal']").change(function () {
      // console.log($(this).val());
      let type    = $('input[name="filterTotal"]:checked').val();
      let date    = $(this).val();
      var _token  = $("input[name='_token']").val();

      $('.loader').show();
      $.ajax({
            url: "{{ route('filter-total-sales') }}",
            type: 'GET',
            data: {
              _token: _token,
              type,
              date
            },
            success: function(data) {
              console.log(data);
              if (!$.isEmptyObject(data.totalSum)) {
                $("#totalSum").text(data.totalSum);
                $(".percentTotalDay").text(data.percentTotal);
                $(".countOrders").text(data.countOrders);
                $(".percentCountDay").text(data.percentCount);
                $(".avgOrders").text(data.avgOrders);
                $(".percentAvg").text(data.percentAvg);
              }
              $('.loader').hide();
            }
        });
    });

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
              + '<select name="product" id="product-filter" class="form-select">'
              + '<option value="999">--Chọn sản phẩm--</option>';
              data.forEach(item => {
                // console.log(item['id'])
                str += '<option value="' + item['id'] + '">' + item['name'] + '</option>';
                });
            str  += '</select>'
              + '</div>';

              $(str).appendTo(".filter-order");
          }
        });
      } else if ($('#product-filter').length > 0) {
          $('#product-filter').parent().remove();
      }
  });
  });
</script>

<script>
$.urlParam = function(name){
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  if (results) {
    return results[1];
  }
  return 0;
}
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

let mkt = $.urlParam('mkt') 
if (mkt) {
   $('#mkt-filter option[value=' + mkt +']').attr('selected','selected');
}

let src = $.urlParam('src') 
if (src) {
  //  let str = '<option value="999">--Tất cả Nguồn--</option>';
  //  $('.src-filter').show('slow');

  //  if (mkt == 1) {
  //      mrNguyen.forEach (function(item) {
  //          // console.log(item);
  //          str += '<option value="' + item.id +'">' + item.name_page +'</option>';
  //      })
  //      $(str).appendTo("#src-filter");
  //  } else if (mkt == 2) {
  //      mrTien.forEach (function(item) {
  //          // console.log(item);
  //          str += '<option value="' + item.id +'">' + item.name_page +'</option>';
  //      })
  //      $(str).appendTo("#src-filter");
  //  }
   $('#src-filter option[value=' + src +']').attr('selected','selected');
}
  // $("#mkt-filter").change(function() {
  // var selectedVal = $(this).find(':selected').val();
  // var selectedText = $(this).find(':selected').text();
  
  // let str = '<option value="999">--Tất cả Nguồn--</option>';
  // $('.src-filter').show('slow');

  // if ($('#src-filter').children().length > 0) {
  //   $('#src-filter').children().remove();
  // }

  // if (selectedVal == 1) {
  //   mrNguyen.forEach (function(item) {
  //       console.log(item);
  //       str += '<option value="' + item.id +'">' + item.name_page +'</option>';
  //   })
  //   $(str).appendTo("#src-filter");
  // } else if (selectedVal == 2) {
  //   mrTien.forEach (function(item) {
  //       console.log(item);
  //       str += '<option value="' + item.id +'">' + item.name_page +'</option>';
  //   });
  //   $(str).appendTo("#src-filter");
  // } else {
  //   $('.src-filter').hide('slow');
  //   $('#src-filter').children().remove();
  // }
  // });
</script>
<script>
    function number_format_js(number) {
        number = number.toLocaleString('vi-VN');
        return number.replace(/,/g, '.').replace(/\./g, ',');
    }
</script>

@stop