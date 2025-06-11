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

  @media only screen and (max-width: 600px) {
    .px-3 {
      padding: 0 !important;
    }

    .dropdown.dropdown-filter {
      white-space: nowrap;
    }
  }

  #daterange {
    width: 100%;
  }
</style>

<?php $checkAll = isFullAccess(Auth::user()->role);
  $isLeadSale = Helper::isLeadSale(Auth::user()->role);
  $enableSale = ($checkAll || $isLeadSale || Auth::user()->is_CSKH || Auth::user()->is_sale);
  $enableDigital = ($checkAll || Auth::user()->is_digital);
  $isCskhDt = Helper::isCskhDt(Auth::user());
  $isDigital = Auth::user()->is_digital;
?>

<div class="body flex-grow-1 px-3">
  <div class="container-lg">
    <div class="row mb-1 filter-order">
      <div class="col-xs-12 col-sm-6 col-md-4 form-group daterange mb-1">
        <input id="daterange" class=" btn btn-outline-secondary" type="text" name="daterange"/>
      </div>

      @if ($checkAll)
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="groupUser" id="group-filter" class="form-select">
          <option value="999">--Nhóm sale--</option>  
            @if (isset($groupUser))
                @foreach($groupUser as $group)
                <option value="{{$group->id}}">{{$group->name}}</option>
                @endforeach
            @endif
        </select>
      </div>
      @endif

      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="group" id="group-filter" class="form-select">
          <option  value="999">--Nhóm hàng--</option>  
            @if (isset($groups))
                @foreach($groups as $group)
                <option value="{{$group->id}}">{{$group->name}}</option>
                @endforeach
            @endif
        </select>
      </div>

      <?php $isDigital = Auth::user()->is_digital;?>
      @if ($isDigital)
      <div class="src-filter col-2 form-group mb-1">
        <select name="src" id="src-filter" class="form-select">
          <option value="999">--Chọn nguồn--</option>
            <?php $pagePanCake = Helper::getConfigPanCake()->page_id;   
              $ladiPages = [
                [
                    'name' => 'Tiễn - Ladipage mua4-tang2 ',
                    'id' => 'mua4-tang2',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4-tang2'
                ],
                [
                    'name' => 'Tiễn - Dùng Là X3 Năng Suất',
                    'id' => '335902056281917',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4-tang2'
                ],
                [
                    'name' => 'Tiễn - 1Xô pha 10.000 lít nước',
                    'id' => '389136690940452',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4-tang2'
                ],
              ];?>
              @foreach ($ladiPages as $page) 
                <option value="{{$page['id']}}">{{($page['name']) ? : $page['name']}}</option>
              @endforeach

          </select>
        </div>
        @endif
      @if ($checkAll)
      <div class="src-filter col-xs-12 col-sm-6 col-md-2 form-group mb-1">
        <select name="src" id="src-filter" class="form-select" aria-label="Default select example">
          <option value="999">--Chọn nguồn--</option>
            <?php $pagePanCake = Helper::getConfigPanCake()->page_id;
                if ($pagePanCake) {
                    $pages = json_decode($pagePanCake);
                    foreach ($pages as $page) {
            ?>
                        <option value="{{$page->id}}">{{($page->name) ? : $page->name}}</option>
            <?php   }
                }   

                $ladiPages = [
                  [
                    'name' => '1 Lít Pha 1000 Lít Nước - 0986987791',
                    'id' => '378087158713964',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  [
                    'name' => '1 Xô Pha 10.000 Lít Nước',
                    'id' => '381180601741468',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  [
                    'name' => 'Khách Cũ Tricho',
                    'id' => 'Khách Cũ Tricho',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  [
                    'name' => 'Hotline - Tricho',
                    'id' => 'Hotline - Tricho',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  [
                    'name' => 'Hotline OG',
                    'id' => 'Hotline OG',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  [
                    'name' => '1Xô pha 10.000 lít nước',
                    'id' => '389136690940452',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
                  
                  [
                    'name' => 'Ladipage ruoc-dong',
                    'id' => 'ruoc-dong',
                    // 'src' => 'https://www.nongnghiepsachvn.net/mua4tang2'
                  ],
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
      <div class="col-xs-12 col-sm-6 col-md-2 form-group mb-1">
          <select name="mkt" id="mkt-filter" class="form-select" aria-label="Default select example">
              <option value="999">--Chọn Marketing--</option>
              <option value="1">a.Nguyên</option>
              <option value="2">a.Tiễn</option>
          </select>
      </div>
      
      @endif
      
      @if ($checkAll || $isLeadSale)
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
        
      </div>
    </div>
    <div class="row">
      <div class="box-body" style="padding-top: 0px;">
        <div style="clear: both;"></div>
        <?php if (($isCskhDt && !$isDigital) || $checkAll) {?>      
        <div style="clear: both; margin-bottom: 15px;"></div>
        <div class="dragscroll1 tableFixHead table_cskh_DT table_sale">
          <span class="loader hidden">
            <img src="{{asset('public/images/rocket.svg')}}" alt="">
          </span>
          <table class="table table-bordered table-multi-select" id="tableReportSale">
            <thead>
              <tr style="cursor: grab;" class="drags-area">
                <th rowspan="2" class="text-center" style="width: 35px;">STT</th>
                <th rowspan="2" class="text-center" style="width: 10%">SALE</th>
                <th colspan="2" class="text-center">Contact</th>
                <th rowspan="2" class="text-center">Đơn chốt</th>
                <th rowspan="2" class="text-center no-wrap">Tỉ lệ chốt (%)</th>
                <th rowspan="2" class="text-center">Số sản phẩm</th>
                <th rowspan="2" class="text-center">Doanh số</th>
                <th rowspan="2" class="text-center">Giá trị đơn</th>
              </tr>
              <tr>
                <th class="text-center">Tất cả</th>
                <th class="text-center">Theo ngày</th>
              </tr>
                
              <tr class="rowSum drags-area" id="tr-sum-cskh-DT" style="cursor: grab;">
                <td colspan="2" class="text-center" style="font-weight: bold">Tổng:</td>
                <td></td>
              </tr>
            </thead>
            
            <tbody id="body-cskh-DT">
              <tr>
                <td></td>
                {{-- <td class="text-center" style="font-weight: bold">Tổng:</td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php $i = 1; ?>
              @foreach ($dataSaleCSKH as $sale)
              <tr>
                <td class="text-center">{{$i}}</td>
                <td>{{$sale->real_name}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php $i++; ?>
              @endforeach
            </tbody>
          </table>
        </div>
        <?php } ?>
        <div style="height: 15px; clear: both;"></div>
        <span class="loader hidden">
          <img src="{{asset('public/images/rocket.svg')}}" alt="">
        </span>
        <div style="height: 5px;"></div>
      </div>
    </div>

    <div class="row">
      <div class="box-body" style="padding-top: 0px;">
        <div style="clear: both;"></div>
        <?php if ((!$isCskhDt && !$isDigital) || $checkAll) { ?>
        <div style="clear: both; margin-bottom: 15px;"></div>
        <div class="dragscroll1 tableFixHead table_sale">
          <span class="loader hidden">
            <img src="{{asset('public/images/rocket.svg')}}" alt="">
          </span>
          <table class="table table-bordered table-multi-select" id="tableReportSale">
            <thead>
              <tr style="cursor: grab;" class="drags-area">
                <th class="text-center" style="width: 35px;"></th>
                <th class="text-center" style="width: 10%"></th>
                <th class="text-center" rowspan="1" colspan="6">KHÁCH HÀNG MỚI</th>
                <th class="text-center" rowspan="1" colspan="6">KHÁCH HÀNG CŨ</th>
                <th class="text-center" rowspan="1" colspan="3" style="width: 10%;">DOANH SỐ TỔNG</th>
              </tr>
              <tr style="cursor: grab;" class="drags-area t28">
                <th class="text-center" style="width: 35px;">STT</th>
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

                <th class="text-center" style="width: 5%;">Tỉ lệ chốt</th>
                <th class="text-center" style="width: 5%;">Doanh số</th>
                <th class="text-center" style="width: 5%;">Giá trị TB đơn</th>
              </tr>
                
              <tr class="rowsum drags-area t72" id="tr-sum-sale" style="cursor: grab;">
              </tr>
            </thead>
            
            <tbody id="body-sale">
              <tr>
                <td></td>
                <td class="text-center" style="font-weight: bold">Tổng:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php $i = 1; ?>
              @foreach ($dataSale as $sales)
                  <tr>
                  <td class="text-center">{{$i}}</td>
                  <td>{{ $sales->real_name }}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <?php $i++; ?>
                  
              @endforeach
            </tbody>
          </table>
        </div>
        <?php } ?>
        <div style="height: 15px; clear: both;"></div>
        <span class="loader hidden">
          <img src="{{asset('public/images/rocket.svg')}}" alt="">
        </span>
        <div style="height: 5px;"></div>
      </div>
    </div>
  
    <div class="row">
      <div class="box-body" style="padding-top: 0px;">
        <div style="clear: both;"></div>
        <?php if ($isDigital || $checkAll) { ?> 
        <div class="dragscroll1 tableFixHead table_digital">
          <span class="loader hidden">
            <img src="{{asset('public/images/rocket.svg')}}" alt="">
          </span>
          <table class="table table-bordered table-multi-select" id="tableReportMarketing">
            <thead>
              <tr style="cursor: grab;" class="drags-area">
                <th class="text-center" style="width: 35px;"></th>
                <th class="text-center no-wrap" style="min-width: 10%"></th>
                <th class="text-center" rowspan="1" colspan="6">KHÁCH HÀNG MỚI</th>
                <th class="text-center" rowspan="1" colspan="3">KHÁCH HÀNG CŨ</th>
                <th class="text-center" rowspan="1" colspan="3">DOANH SỐ TỔNG</th>
              </tr>
              <tr style="cursor: grab;" class="drags-area t28">
                  <th class="text-center" style="width: 35px;">STT</th>
                  <th class="text-center">MARKETING</th>
                  <th class="text-center">Contact</th>
                  <th class="text-center">Đơn chốt</th>
                  <th class="text-center">Tỉ lệ chốt đơn (%)</th>
                  <th class="text-center">Số sản phẩm</th>
                  <th class="text-center">Doanh số</th>
                  <th class="text-center">Giá trị đơn</th>

                  <th class="text-center">Đơn chốt</th>
                  <th class="text-center">Doanh số</th>
                  <th class="text-center">Giá trị đơn</th>

                  <th class="text-center">Tỉ lệ chốt đơn</th>
                  <th class="text-center">Doanh số</th>
                  <th class="text-center">Giá trị đơn</th>
                  
              </tr>
              <tr class="rowsum drags-area t72" id="tr-sum-digital" style="cursor: grab;">
              </tr> 
            </thead>
            
            <tbody id="body-digital">
              <tr>
                <td></td>
                <td class="text-center" style="font-weight: bold">Tổng:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
               <?php $i = 1; ?>
              @foreach ($dataDigital as $digital)
               <tr>
                  <td class="text-center">{{$i}}</td>
                  <td>{{ $digital->real_name }}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <?php $i++; ?>
              @endforeach
            </tbody>
          </table>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

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
      var group     = $("select[name='group']").val();
      var groupUser = $("select[name='groupUser']").val();
      
      data = {
        _token: _token,
        type: 'daterange',
        date: arr,
        status,
        category,
        product,
        sale,
        mkt,
        src,
        group,
        groupUser
      };
      ajaxGetListCskhDt(data);
      ajaxGetListSale(data);
      ajaxGetListDigital(data);
    });
    
    $("input[name='dateTotal']").change(function () {

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

let mkt = $.urlParam('mkt') 
if (mkt) {
   $('#mkt-filter option[value=' + mkt +']').attr('selected','selected');
}

let src = $.urlParam('src') 
if (src) {
   $('#src-filter option[value=' + src +']').attr('selected','selected');
}
</script>
<script>
  function number_format_js(number) {
      number = number.toLocaleString('vi-VN');
      return number.replace(/,/g, '.').replace(/\./g, ',');
  }

  function ajaxGetListDigital(dataInput)
  {
    if ($('.table_digital').length > 0) {
      $('.table_digital .loader').show();
      $('.table_digital .table-multi-select').css("opacity", "0.5");
      $('.table_digital .table-multi-select').css("position", "relative");
        $.ajax({
          url: "{{ route('filter-total-digital') }}",
          type: 'GET',
          data: dataInput,
          success: function(data) {
            var data = data.data_digital;
            if (data.data.length > 0) {
              /* lọc data digital*/
              var str = '';
              var newCusomerTrSum = data.trSum.sum_new_customer;
              var oldCusomerTrSum = data.trSum.sum_old_customer;
              var summaryCusomerTrSum = data.trSum.summary;
              var maxAvcElem = data.data[0].summary_total.avg;

              /** lấy ra trung bình đơn lớn nhất của trong list sale**/
              data.data.forEach((element, k) => {
                  if (element.summary_total.avg > maxAvcElem) {
                      maxAvcElem = element.summary_total.avg;
                  }
              });

              data.data.forEach((element, k) => {
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
                          
                str += '</div><span class="progress-text">' + element.old_customer.product + '</span></div></td>'
                  + '<td class="tdProgress tdSoChotDon"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentOrderOld + '%"></div>'
                  + '</div><span class="progress-text">' + element.old_customer.order + '</span></div></td>'
                  + '<td class="tdProgress tdDoanhSo"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalOld + '%"></div>'
                  + '</div><span class="progress-text">' + number_format_js(element.old_customer.total) + '</span></div></td>'
                  
                  + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgOld + '%"></div>'
                  + '</div><span class="progress-text">' + number_format_js(element.old_customer.avg) + '</span></div></td>';

                str += '<td class="tdProgress tdTyLeChotDon"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' +  element.summary_total.rate + '%"></div>'
                  + '</div><span class="progress-text">' +  element.summary_total.rate + '%</span></div></td>'
                  + '<td class="tdProgress tdDoanhSoTong"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalSum + '%"></div>'
                  + '</div><span class="progress-text">' + number_format_js(element.summary_total.total) + '</span></div></td>'
                  + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                  + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgSum + '%"></div>'
                  + '</div><span class="progress-text">' + number_format_js(element.summary_total.avg) + '</span></div></td></tr>';           
              });

              $("#body-digital").html(str);

              var strTdSum = '';
              strTdSum += '<td colspan="2" class="text-center font-weight-bold">Tổng: </td>'
                + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.contact + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.order + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.rate + '%</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + newCusomerTrSum.product + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + number_format_js(newCusomerTrSum.total) + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + number_format_js(newCusomerTrSum.avg) + '</span></td>';
                        
              strTdSum += '<td class="text-center font-weight-bold"><span>' + oldCusomerTrSum.order + '</span></td>'
                +'<td class="text-center font-weight-bold"><span>' + number_format_js(oldCusomerTrSum.total) + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + number_format_js(oldCusomerTrSum.avg) + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + (summaryCusomerTrSum.rate) + '%</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.total) + '</span></td>'
                + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.avg) + '</span></td>';

              $("#tr-sum-digital").html(strTdSum);
            }

            $('.table_digital .loader').hide();
            $('.table_digital .table-multi-select').css("opacity", "1");
            $('.table_digital .table-multi-select').css("position", "relative");
          }
      });
    }
  }

  function ajaxGetListCskhDt(dataInput)
  {
    if ($('.table_cskh_DT').length > 0) {
      $('.table_cskh_DT .loader').show();
      $('.table_cskh_DT .table-multi-select').css("opacity", "0.5");
      $('.table_cskh_DT .table-multi-select').css("position", "relative");
      $.ajax({
        url: "{{ route('filter-total-cskh-dt') }}",
        type: 'GET',
        data: dataInput,
        success: function(data) {
          if (data.data.length > 0) {
            var str = '';
            var maxAvgElem =  data.data[0]['old_customer']['avg'];
              /** lấy ra trung bình đơn lớn nhất của trong list sale**/
            data.data.forEach((element, k) => {
              if (element.old_customer.avg > maxAvgElem) {
                  maxAvgElem = element.old_customer.avg;
              }
            });

            trSum = data.trSum;
            data.data.forEach((element, k) => {
              perCentContactOld = (trSum.contact != 0) ? (element.old_customer.contact / trSum.contact * 100) : 0;
              perCentOrderOld =  (trSum.order != 0) ? (element.old_customer.order / trSum.order * 100) : 0;
              perCentProductOld = (trSum.product != 0) ? (element.old_customer.product / trSum.product * 100) : 0;
              perCentTotalOld = (trSum.total != 0) ? (element.old_customer.total / trSum.total * 100) : 0;
              perCentAvgOld = (trSum.avg != 0) ? (element.old_customer.avg / trSum.avg * 100) : 0;
              perCentContactOldByTime = (trSum.contactByTime != 0) ? (element.old_customer.contactByTime / trSum.contactByTime * 100) : 0;

              str += '<tr>'
                + '<td class="text-center">' + (k+1) + '</td>'
                + '<td>' + element.name + '</td>'
                +'<td class="tdProgress tdSoContact"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentContactOld + '%"></div>'
                + '</div><span class="progress-text">' + element.old_customer.contact + '</span></div></td>'
                +'<td class="tdProgress tdSoContact"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentContactOldByTime + '%"></div>'
                + '</div><span class="progress-text">' + element.old_customer.contactByTime + '</span></div></td>'
                + '<td class="tdProgress tdSoChotDon"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentOrderOld + '%"></div>'
                + '</div><span class="progress-text">' + element.old_customer.order + '</span></div></td>'
                + '<td class="tdProgress tdTyLeChotDon"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + element.old_customer.rate + '%"></div>'
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
                  
            });

            $("#body-cskh-DT").html(str);

            var strTdSum = '';
            strTdSum += '<td colspan="2" class="text-center font-weight-bold">Tổng: </td>'
              +'<td class="text-center font-weight-bold"><span>' + trSum.contact + '</span></td>'
              +'<td class="text-center font-weight-bold"><span>' + trSum.contactByTime + '</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + trSum.order + '</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + trSum.rate + '%</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + trSum.product + '</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + number_format_js(trSum.total) + '</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + number_format_js(trSum.avg) + '</span></td>';
                    

              $("#tr-sum-cskh-DT").html(strTdSum);
          }

          $('.table_cskh_DT .loader').hide();
          $('.table_cskh_DT .table-multi-select').css("opacity", "1");
          $('.table_cskh_DT .table-multi-select').css("position", "relative");
        }
      });
    }
  }

  function ajaxGetListSale(dataInput)
  { 
    if ($('.table_sale').length == 0) {
      return;
    }
    console.log($('.table_sale').length);
    $('.table_sale .loader').show();
    $('.table_sale .table-multi-select').css("opacity", "0.5");
    $('.table_sale .table-multi-select').css("position", "relative");
    $.ajax({
      url: "{{ route('filter-total-sales') }}",
      type: 'GET',
      data: dataInput,
        success: function(data) {
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
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + element.old_customer.rate + '%"></div>'
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

              str += '<td class="tdProgress tdTyLeChotDon"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + element.summary_total.rate + '%"></div>'
                + '</div><span class="progress-text">' + element.summary_total.rate + '%</span></div></td>'
                + '<td class="tdProgress tdDoanhSoTong"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentTotalSum + '%"></div>'
                + '</div><span class="progress-text">' + number_format_js(element.summary_total.total) + '</span></div></td>'

                + '<td class="tdProgress tdGiaTriDon"><div class="box-progress"><div class="progress">'
                + '<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: ' + perCentAvgSum + '%"></div>'
                + '</div><span class="progress-text">' + number_format_js(element.summary_total.avg) + '</span></div></td></tr>';
          
            });
              
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
              + '<td class="text-center font-weight-bold"><span>' + summaryCusomerTrSum.rate + '%</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.total) + '</span></td>'
              + '<td class="text-center font-weight-bold"><span>' + number_format_js(summaryCusomerTrSum.avg) + '</span></td>';

            $("#tr-sum-sale").html(strTdSum);
          }

          $('.table_sale .loader').hide();
          $('.table_sale .table-multi-select').css("opacity", "1");
          $('.table_sale .table-multi-select').css("position", "relative");
        }
      });
  }

  setTimeout(function () {
    loadDataReportHome();
  }, 500);
  // loadDataReportHome();
  
  function loadDataReportHome()
  {
    let value =  $("input[name='daterange']").val();
    let arr = value.split("-");
    var _token    = $("input[name='_token']").val();
    var status    = $("select[name='status']").val();
    var category  = $("select[name='category']").val();
    var product   = $("select[name='product']").val();
    var sale      = $("select[name='sale']").val();
    var mkt       = $("select[name='mkt']").val();
    var src       = $("select[name='src']").val();
    var group     = $("select[name='group']").val();
    var groupUser = $("select[name='groupUser']").val();
    data = {
      _token: _token,
      type: 'daterange',
      date: arr,
      status,
      category,
      product,
      sale,
      mkt,
      src,
      group,
      groupUser
    };

    ajaxGetListSale(data);
    ajaxGetListCskhDt(data);
    ajaxGetListDigital(data);
    
  }
</script>

@stop