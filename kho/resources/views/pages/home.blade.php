@extends('layouts.default')
@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
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
    width: 13%;
  }
</style>
<div class="body flex-grow-1 px-3">
  <div class="container-lg">
    <div class="row mb-2">
      <div class="col">
        <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">        
          <div class="dropdown dropdown-filter mb-3" >
            <button id="filter-type-button" class="filter-type-button btn" type="button" data-toggle="dropdown">
                Bộ lọc 
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a id="type-day">Lọc theo ngày</a></li>
                <li><a id="type-period">Khoảng thời gian</a></li>
            </ul>
            <input class="hidden btn btn-outline-secondary" value="{{date('Y-m-d', time())}}" type="date" id="dateTotal" name="dateTotal">
            <input id="daterange" class="hidden btn btn-outline-secondary" type="text" name="daterange" value="01/01/2024 - 15/04/2024" />

            <div id="filter-order" class="mt-3 hidden btn-group btn-group-toggle filter-order" data-coreui-toggle="buttons">
            
              <label class="btn btn-outline-secondary"> Ngày
                <input class="btn-check" id="total" type="radio" name="filterTotal" value="day" autocomplete="off">
              </label>
            
              <label class="btn btn-outline-secondary active"> Tháng
                <input class="btn-check" id="option2" type="radio" name="filterTotal" value="month" autocomplete="off" checked="">
              </label>
              
              <label class="btn btn-outline-secondary"> Năm
                <input class="btn-check" id="option3" type="radio" name="filterTotal" value="year" autocomplete="off">
              </label>
            </div>
        </div>
          
          <span class="loader hidden">
            <img src="{{asset('public/images/loader-home.gif')}}" alt="">
          </span>
        </div>
      </div>
    </div>
    <div class="row total-sales">
      <div class="col-sm-6 col-lg-3 filter">
        <div class="card mb-4 text-white bg-primary">
          <div class="card-body pb-0 d-flex justify-content-between align-items-start">
            {{ csrf_field() }}
            <div>
              <div class="fs-4 fw-semibold"><span id="totalSum">{{$item['totalSum']}}</span>
                <span class="percentTotalDay fs-6 fw-normal">{{$item['percentTotal']}}</span></div>
              <div class="name-total">Doanh thu</div>
            </div>
          </div>
          <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
            <canvas class="chart" id="card-chart1" height="70"></canvas>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-info">
          <div class="card-body pb-0 d-flex justify-content-between align-items-start">
            <div>
              <div class="fs-4 fw-semibold "><span class="countOrders">{{$item['countOrders']}}</span> 
                <span class="percentCountDay fs-6 fw-normal ">{{$item['percentCount']}}</span></div>
              <div>Số đơn</div>
            </div>
            <div class="dropdown">
              <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg class="icon">
                  <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-options')}}"></use>
                </svg>
              </button>
              <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
            </div>
          </div>
          <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
            <canvas class="chart" id="card-chart2" height="70"></canvas>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-warning">
          <div class="card-body pb-0 d-flex justify-content-between align-items-start">
            <div>
              <div class="fs-4 fw-semibold">2.49% <span class="fs-6 fw-normal">(84.7%
                  <svg class="icon">
                    <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-arrow-top"></use>
                  </svg>)</span></div>
              <div>Tỉ lệ chốt</div>
            </div>
            <div class="dropdown">
              <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg class="icon">
                  <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-options"></use>
                </svg>
              </button>
              <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
            </div>
          </div>
          <div class="c-chart-wrapper mt-3" style="height:70px;">
            <canvas class="chart" id="card-chart3" height="70"></canvas>
          </div>
        </div>
      </div>
      <!-- /.col-->
      <div class="col-sm-6 col-lg-3">
        <div class="card mb-4 text-white bg-danger">
          <div class="card-body pb-0 d-flex justify-content-between align-items-start">
            <div>
              <div class="fs-4 fw-semibold"><span class="avgOrders">{{$item['avgOrders']}}</span>
                <span class="fs-6 fw-normal percentAvg">{{$item['percentAvg']}}</span></div>
              <div>Trung bình đơn</div>
            </div>
            <div class="dropdown">
              <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg class="icon">
                  <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-options"></use>
                </svg>
              </button>
              <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
            </div>
          </div>
          <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
            <canvas class="chart" id="card-chart4" height="70"></canvas>
          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
    <!-- /.row-->
  
    <!-- /.card.mb-4-->
 
    <!-- /.row-->

    <!-- /.row-->
  </div>
</div>
</div>



<script type="text/javascript" src="{{asset('public/js/dateRangePicker/daterangepicker.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('public/js/dateRangePicker/dateRangePicker-vi.js')}}"></script> --}}
<script>
  $(document).ready(function() {
    //  $.daterangepicker.setDefaults( $.daterangepicker.regional[ "" ] );
    // $('input[name="daterange"]').daterangepicker({
    //   // timePicker: true,
    //   // startDate: moment().startDate.format('DD/MM/YYYY'),
    //   // endDate: moment().startOf('hour').add(32, 'hour'),
    //   locale: {
    //     format: 'DD/MM/YYYY'
    //   }
    // });
    $('input[name="daterange"]').daterangepicker({
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

    $('input[name="daterange"]').change(function () {
      let value = $(this).val();
      console.log(value);
      let arr = value.split("-");
      // console.log(arr);
      var _token  = $("input[name='_token']").val();
      $.ajax({
            url: "{{ route('filter-total-sales') }}",
            type: 'GET',
            data: {
              _token: _token,
              type: 'daterange',
              date: arr
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
    // $(".filter-type-button").click(function () {
    //   $(this).parent().toggleClass('open');
    // });

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

  });
</script>

@stop