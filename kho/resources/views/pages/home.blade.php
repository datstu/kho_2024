@extends('layouts.default')
@section('content')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('public/js/moment.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/daterangepicker.css')}}" /> 

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
    /* width: 13%;zxc */
  }
  #daterange {
    color: #000;
  }
  
  
  .filter-order .daterange {
    min-width: 230px;
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
    <div class="row total-sales">
      <div class="col-sm-6 col-lg-3 filter">
        <div class="card mb-4 text-white bg-primary">
          <div class="card-body pb-0 d-flex justify-content-between align-items-start">
            {{ csrf_field() }}
            <div>
              <div class="fs-4 fw-semibold"><span id="totalSum">{{$item['totalSum']}}</span></div>
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
              <div class="fs-4 fw-semibold "><span class="countOrders">{{$item['countOrders']}}</span></div>
              <div>Số đơn <span class="sumProduct"> <?php (isset($sumProduct) && $sumProduct > 0) ?: '(30 sản phẩm)'; ?></span></div>
             
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
              <div class="rateSuccess fs-4 fw-semibold">{{$item['rateSuccess']}} </div>
                <div>Tỉ lệ chốt (Data nhận: <span class="countSaleCare">{{$item['countSaleCare']}}</span>)</div>

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
              <div class="fs-4 fw-semibold"><span class="avgOrders">{{$item['avgOrders']}}</span></div>
              <div>Trung bình đơn</div>
            </div>
            <div class="dropdown">
              <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg class="icon">
                  {{-- <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-options"></use> --}}
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
      // alert( "Handler for `click` called." );
      // let value = $(this).val();
      // // console.log(value);
      // let arr = value.split("-");
      // // console.log(arr);
      // var _token  = $("input[name='_token']").val();
      // $.ajax({
      //       url: "{{ route('filter-total-sales') }}",
      //       type: 'GET',
      //       data: {
      //         _token: _token,
      //         type: 'daterange',
      //         date: arr
      //       },
      //       success: function(data) {
      //         console.log(data);
      //         if (!$.isEmptyObject(data.totalSum)) {
      //           $("#totalSum").text(data.totalSum);
      //           $(".percentTotalDay").text(data.percentTotal);
      //           $(".countOrders").text(data.countOrders);
      //           $(".percentCountDay").text(data.percentCount);
      //           $(".avgOrders").text(data.avgOrders);
      //           $(".percentAvg").text(data.percentAvg);
      //         }
      //         $('.loader').hide();
      //       }
      //   });
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
              console.log(data);
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
    } );
    
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
@stop