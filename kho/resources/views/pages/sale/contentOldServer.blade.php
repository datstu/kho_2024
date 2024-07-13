<style>
    .m-header .text {
    padding: 0 var(--cui-card-cap-padding-x);
    color: #000;
    text-shadow: none !important;
    font-size: 16px;
    font-weight: bold;
    height: 100%;
    line-height: 30px;
    display: inline-block;
}
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
        text-align: left;
        color: #000;
        border: 1px solid var(--cui-form-select-border-color, #b1b7c1);
        border-radius: 0.375rem;
        width: 100%;
    }
    .mof-container, .txt-mof {
        background-color: transparent;
        height: 45px;
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

    $ladiPages = [
        [
            'name' => 'Ladipage ruoc-dong',
            'id' => 'ruoc-dong',
            // 'src' => 'https://www.phanbonlua.xyz/ruoc-dong'
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
?>
                   

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="{{ asset('public/css/pages/sale.css'); }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<style>.btn-sm {
    padding-top: 7px;
    padding-bottom: 4px;
    font-size: 11px;
    padding-right: 12px;
    font-weight: bold;
    height: 30px;
}
.select2-container {
    width: 100% !important;
}
body {
    font-family: Arial, Helvetica, sans-serif
}
.maintain-filter-main:hover {
    opacity: 0.2;
    border: 1px solid #ff0000;
}
textarea.txt-mof {
    position: absolute;
    top: 0px;
    /* left: 0px; */
    /* height: 30px; */
    overflow-y: hidden;
    transition: ease 0.2s all;
    line-height: 20px;
    font-size: 11px;
    padding-top: 4px;
    background: none;
    border: none;
    }
</style>
@csrf
{{-- update filter --}}

<div class="maintain-filter-main" title="Sắp rã đông">
    <div id="dnn_ctr1441_Main_SaleTacNghiep_up1">
            
        <span id="dnn_ctr1441_Main_SaleTacNghiep_lblDownLoad" class="hidden"></span>
        <input name="dnn$ctr1441$Main$SaleTacNghiep$_UserId" type="text" value="-1" id="dnn_ctr1441_Main_SaleTacNghiep__UserId" class="sale-user-id hidden">
        <input name="dnn$ctr1441$Main$SaleTacNghiep$_HighLightListId" type="text" id="dnn_ctr1441_Main_SaleTacNghiep__HighLightListId" class="list-id-hl hidden">
        <input name="dnn$ctr1441$Main$SaleTacNghiep$_StickyId" type="text" id="dnn_ctr1441_Main_SaleTacNghiep__StickyId" class="sticky-id hidden">
        <input name="dnn$ctr1441$Main$SaleTacNghiep$_OneItemId" type="text" id="dnn_ctr1441_Main_SaleTacNghiep__OneItemId" class="item-one-id hidden">
        <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_btnReloadOneItem" class="btn-item-one-reload hidden" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$btnReloadOneItem','')">
            <i class="fa fa-refresh"></i>
        </a>
        <div id="dnn_ctr1441_Main_SaleTacNghiep_pnSearch">

            <div class="m-header-wrap">
                <div class="m-header" style="top: 150px;">
                    <div class="row">
                        <div id="dnn_ctr1441_Main_SaleTacNghiep_divTitle" class="col-sm-2 form-group">
                            <span id="dnn_ctr1441_Main_SaleTacNghiep_lblModuleTitle" class="text">Sale tác nghiệp</span>
                        </div>
                        <div class="col-sm-1 form-group text-right">
                            
                        </div>
                        <div id="dnn_ctr1441_Main_SaleTacNghiep_divLeaderSale" class="col-sm-2 form-group">
                            
                            </div>
                        <div id="dnn_ctr1441_Main_SaleTacNghiep_divNhom" class="col-sm-2 form-group">
                            <select id="" tabindex="-1">
                                <option selected="selected" value="-1">--Chọn nhóm--</option>
                            </select>
                        </div>
                        <div class="col-sm-2 form-group">
                            <select tabindex="-1" title="" id="listSale">
                                {{-- <option selected="selected" value="-1" >--Tất cả sale--</option> --}}
                                @if ($checkAll)<option   value="999">--Tất cả Sale--</option> @endif
            
                                @if (isset($sales))
                                    @foreach($sales as $sale)
                                    <option value="{{$sale->id}}">{{($sale->real_name) ? : $sale->name}} ({{$sale->name}})</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                        <div id="dnn_ctr1441_Main_SaleTacNghiep_divSearch" class="col-sm-3 form-group">
                            <div style="width: calc(100% - 145px); float: left;">
                                <input name="dnn$ctr1441$Main$SaleTacNghiep$txtTuKhoa" type="text" id="dnn_ctr1441_Main_SaleTacNghiep_txtTuKhoa" class="form-control" placeholder="Họ tên, số điện thoại">
                            </div>
                            <div style="width: 125px; float: right;">
                                <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_btnSearch" class="btn btn-sm btn-primary" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$btnSearch','')">
                                    <i class="fa fa-search"></i>Tìm kiếm
                                </a>
                                
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>

    </div>
    <div id="dnn_ctr1441_Main_SaleTacNghiep_UpdatePanel1">
        <div class="box-body " style="padding-bottom: 0px;">
            <div class="row">
                <div class="col-sm-4 form-group daterange">
                    <input id="daterange" class="btn" type="text" name="daterange" />
                </div>
                
                <div class="col-sm-2 form-group">
                    <select style="display: none;" tabindex="-1" title="" id="typeData">
                        <option selected="selected" value="-1">--Kiểu ngày--</option>
                        <option value="SaleNgayNhanData">Ngày sale nhận data</option>
                        <option value="NgayTao">Ngày data về hệ thống</option>
                        <option value="SaleTacNghiepNgayCapNhat">Ngày sale tác nghiệp</option>
                        <option value="DonHangNgayChot">Ngày sale chốt đơn</option>
                        <option value="NgayDangDon">Ngày đăng đơn</option>
                        <option value="NgayChoXuat">Ngày sale tác nghiệp tiếp</option>
                        <option value="NgayCapNhatTrangThaiGiaoHang">Ngày cập nhật trạng thái giao hàng</option>
                        <option value="NgayGiaoHang">Ngày muốn nhận hàng</option>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                    <select style="display: none;" id="careOrder" tabindex="-1">
                        <option selected="selected" value="-1">--Care đơn--</option>
                        <option value="0">Chờ care đơn</option>
                        <option value="1">Giao ngay</option>
                        <option value="2">Chờ giao</option>
                        <option value="3">Hoãn giao hàng</option>
                        <option value="4">Sale vừa cứu đơn</option>
                        <option value="5">Khách hàng khiếu nại</option>
                        <option value="6">Hoàn tất xử lý khiếu nại</option>
                    </select>
                </div>


                @if ($checkAll)
                <div class="col-xs-12 col-sm-6 col-md-2 hidden-xs form-group">
                    <select style="display: none;" name="src" id="srcData" class="form-select" aria-label="Default select example">       
                        <option value="999">--Chọn nguồn dữ liệu--</option>
                    <?php $pagePanCake = Helper::getConfigPanCake()->page_id;
                    if ($pagePanCake) {
                        $pages = json_decode($pagePanCake);
                        // dd($pages);
                        foreach ($pages as $page) {
                    ?>
                        <option value="{{$page->id}}">{{($page->name) ? : $page->name}}</option>
                    <?php   }
                    }   
    
                    foreach ($ladiPages as $page) {
                    ?>
                        <option value="{{$page['id']}}">{{($page['name']) ? : $page['name']}}</option>
                    <?php   
                        }
                    ?> 
    
                    </select>
                </div>
                @endif
                <div class="col-xs-12 col-sm-6 col-md-2 hidden-xs form-group">
                    <input id="dnn_ctr1441_Main_SaleTacNghiep_chkHideNoCount" type="checkbox" name="dnn$ctr1441$Main$SaleTacNghiep$chkHideNoCount"><label for="dnn_ctr1441_Main_SaleTacNghiep_chkHideNoCount">Ẩn tác nghiệp không số</label>
                </div>
            </div>
            <div class="row">
                
                <div class="col-xs-12 col-sm-6 col-md-2 hidden-xs form-group">
                    <select id="productFilter" tabindex="-1" style="display: none;">
                        <option selected="selected" value="-1">--Chọn sản phẩm--</option>
                        <option value="0">Chờ care đơn</option>
                        <option value="1">Giao ngay</option>
                        <option value="2">Chờ giao</option>
                        <option value="3">Hoãn giao hàng</option>
                        <option value="4">Sale vừa cứu đơn</option>
                        <option value="5">Khách hàng khiếu nại</option>
                        <option value="6">Hoàn tất xử lý khiếu nại</option>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                    <select id="statusTN" class="chosen chosen-x" tabindex="-1" title="" style="display: none;">
                        <option selected="selected" value="-1">--Chọn trạng thái tác nghiệp--</option>
                        <option value="0">Chưa tác nghiệp</option>
                        <option value="1">Đã tác nghiệp</option>

                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                    <select id="resultTN" class="id-ket-qua-tac-nghiep chosen chosen-x" tabindex="-1" title="" style="display: none;">
                        <option selected="selected" value="-1">--Chọn kết quả tác nghiệp--</option>
                        <option value="96908">Chốt đơn</option>
                        <option value="96909">Không nghe máy</option>
                        <option value="96910">Máy bận </option>
                        <option value="96911">Gọi lại sau</option>
                        <option value="96912">Trùng số</option>
                        <option value="96913">Sai số/ Nhầm số</option>
                        <option value="96914">Thuê bao</option>
                        <option value="96915">Suy nghĩ thêm</option>
                        <option value="96916">Không có nhu cầu</option>
                        <option value="96917">Khách hàng hẹn giao</option>
                        <option value="96918">Đã nhận đơn</option>
                        <option value="96919">Hiệu quả tốt</option>
                        <option value="96920">Hiệu quả kém</option>
                        <option value="96921">Chưa mua tiếp</option>
                        <option value="96922">CSKH sau 7 ngày</option>
                        <option value="96923">CSKH sau 15 ngày</option>
                        <option value="96924">CSKH sau 21 ngày</option>
                        <option value="97072">CSKH sau 21 ngày</option>
                        <option value="97073">CSKH sau 30 ngày</option>
                        <option value="97114">Tham khảo</option>
                        <option value="97240">Chưa Sử Dụng</option>
                        <option value="98477">Tương Tác Zalo</option>
                        <option value="100384">Mới Dùng Sản Phẩm</option>
                        <option value="100405">Hẹn Ngày Chốt Đơn</option>
                    </select>
                </div>
                
                        <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                        <select id="statusDeal" class="chosen chosen-x" tabindex="-1" title="" style="display: none;">
                            <option selected="selected" value="-1">--Trạng thái chốt đơn--</option>
                            <option value="1">Đã chốt đơn</option>
                            <option value="0">Chưa chốt đơn</option>
                        </select>
                    </div>
                
                <div class="col-xs-12 col-sm-6 col-md-2 form-group">
                    <select id="statusOrderShip" class="chosen chosen-x" tabindex="-1" title="" style="display: none;">
                        <option selected="selected" value="-1">--Chọn trạng thái giao hàng--</option>
                        <option value="50">Bồi hoàn</option>
                        <option value="41">Đã hoàn</option>
                        <option value="40">Đang hoàn</option>
                        <option value="35">Giao hàng một phần</option>
                        <option value="34">Yêu cầu giao lại</option>
                        <option value="33">Không giao được</option>
                        <option value="32">Đã thanh toán</option>
                        <option value="31">Đã giao hàng</option>
                        <option value="30">Đang giao hàng</option>
                        <option value="23">Đang lấy hàng</option>
                        <option value="22">Không lấy được hàng</option>
                        <option value="21">Đã lấy hàng</option>
                        <option value="20">Đã đăng</option>
                        <option value="5">Hủy đăng đơn</option>
                        <option value="4">Hủy vận đơn</option>
                        <option value="3">Hoãn giao hàng</option>
                        <option value="2">Giao ngay</option>
                        <option value="1">Chờ vận đơn</option>

                    </select>
                </div>
                <div class="col-xs-12 form-group">
                    
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl00_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89531" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl00$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Data nóng</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_0">(28/2,300)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl01_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89532" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl01$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 2</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_1">(122/1,281)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl02_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89533" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl02$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 3</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_2">(156/1,248)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl03_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89534" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl03$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 4</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_3">(178/1,252)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl04_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89535" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl04$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 5</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_4">(166/1,258)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl05_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89536" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl05$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 6</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_5">(173/993)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl06_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep91233" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl06$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 7</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_6">(210/998)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl07_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep91234" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl07$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Gọi lần 8</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_7">(1,012/2,982)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl08_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89537" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl08$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc khách hàng cũ</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_8">(492/1,830)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl09_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89538" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl09$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc lần 1</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_9">(1,225/2,827)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl10_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89539" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl10$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc lần 2</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_10">(1,133/1,866)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl11_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89703" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl11$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc lần 3</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_11">(200/307)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl12_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep92855" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl12$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc lần 4</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_12">(40/102)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl13_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep92856" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl13$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Chăm sóc lần 5</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_13">(93/271)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl14_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89656" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl14$btnChonTacNghiep','')">
                                <span class="flag level-3"></span>
                                <span class="text">Khách hàng hẹn giao</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_14">(2/18)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl15_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep92990" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl15$btnChonTacNghiep','')">
                                <span class="flag level-1"></span>
                                <span class="text">Hẹn Ngày Chốt Đơn</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_15">(3/3)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl16_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89540" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl16$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">Bỏ qua</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_16">(326/327)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl17_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89698" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl17$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">CSKH sau 30 ngày</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_17">(6/48)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl18_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89699" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl18$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">CSKH sau 45 ngày</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_18">(3/35)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl19_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89700 selected" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl19$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">CSKH sau 60 ngày</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_19">(8/35)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl20_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89701" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl20$btnChonTacNghiep','')">
                                <span class="flag level-3"></span>
                                <span class="text">CSKH sau 75 ngày</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_20">(9/18)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl21_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89702" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl21$btnChonTacNghiep','')">
                                <span class="flag level-4"></span>
                                <span class="text">CSKH sau 90 ngày</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_21">(107/221)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl22_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89743" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl22$btnChonTacNghiep','')">
                                <span class="flag level-1"></span>
                                <span class="text">Tham khảo</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_22"></span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl23_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep89865" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl23$btnChonTacNghiep','')">
                                <span class="flag level-1"></span>
                                <span class="text">Chưa Sử Dụng</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_23"></span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl24_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep-1" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl24$btnChonTacNghiep','')">
                                <span class="flag level-"></span>
                                <span class="text">Chưa có TN</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_24"></span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                            <a onclick="showLoader();" id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep_ctl25_btnChonTacNghiep" title="Chưa tác nghiệp / Tổng contact" class="dm-tac-nghiep dm-tac-nghiep0" href="javascript:__doPostBack('dnn$ctr1441$Main$SaleTacNghiep$rptTacNghiep$ctl25$btnChonTacNghiep','')">
                                <span class="flag level-"></span>
                                <span class="text">Tất cả</span>
                                <span class="count">
                                    <span id="dnn_ctr1441_Main_SaleTacNghiep_rptTacNghiep__CountText_25">(5,692/20,220)</span>
                                </span>
                                <span class="live-stream"></span>
                                <span style="clear: both;"></span>
                            </a>
                        
                    
                </div>
                <div style="clear: both; border-bottom: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>
</div>
{{-- end update filter --}}

    <div class="box-body">
        <div class="loader hidden">
            <img src="{{asset('public/images/new-loader.gif')}}" alt="">
        </div>


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
                <input id="daterange" class="btn" type="text" name="daterange" />
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
            <div class="mb-1 mt-1" style="padding: 0 15px">Tổng data: <span>{{$count}}</span></div>
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
    <div class="dragscroll1 tableFixHead">
        <table class=" table table-bordered table-multi-select table-sale" id="myTable">
            <thead class="">
                <tr class="drags-area">
                    <th style="top: 0.5px;">
                        <span class="chk-all"><input id="" type="checkbox" name="dnn$ctr1441$Main$SaleTacNghiep$chkItem"><label for="dnn_ctr1441_Main_SaleTacNghiep_chkItem">&nbsp;</label></span>
                    </th>
                    <th draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"><span class="span-col text-center" style="display: inline-block; min-width: 60px; max-width: 80px;">Mã đơn</span></th>

                    <th class=" text-center" draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"> 
                        <span class="span-col" style="display: inline-block; width: 150px; max-width: 150px;">Nguồn data <br>Ngày nhận</span></th>
                    <th class=" text-center" draggable="true" ondragstart="handleDragStart(event)" style="top: 0.5px;"> <span class="span-col text-center" style="display: inline-block; min-width: 60px; max-width: 100px;">Sale</span></th>
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center no-wrap area5 hidden-xs" style="top: 0.5px;">
                        <span class="span-col text-center" style="display: inline-block; min-width: 200px; max-width: 200px;">Họ tên<br>
                            <span class="span-col">Số điện thoại</span><br>
                            <span class="span-col">Địa chỉ</span>
                        </span>
                    </th> 
                    <th draggable="true" ondragstart="handleDragStart(event)" class="text-center" style="top: 0.5px;"> <span class="span-col " style="display: inline-block; min-width: 150px; max-width: 150px;">Tin nhắn</span></th>
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
                    <td class="text-center id-order-new" >

                        @if (isset($item->id_order_new))
                        <a target="_blank" href="{{route('view-order', ['id' => $item->id_order_new])}}">{{$item->id_order_new}}</a>
                        @endif
                    
                    </td>
                    <td class="text-center">
                    <a target="_blank" {{ ($item->page_link) ? ('href=' . $item->page_link ) : '' }}>{{$item->page_name}}</a>     <br> {{date_format($item->created_at,"H:i d-m-Y ")}}
                    </td>
                    <td class="text-center">

                        @if ($flag)
                        <a title="chỉ định Sale nhận data" data-id="{{$item->id}}" class="update-assign-TN-sale btn-icon aoh">
                            <i class="fa fa-save"></i>
                        </a>
                        <div class="mof-container">
                            <select name="assignTNSale_{{$item->id}}" id="">
                                @if (!$item->user)
                                <option value="0">None </option>
                                    @endif
                                @foreach ($listSale->get() as $sale)
                                    
                                    
                                <option <?php echo ($item->user && $item->user->id == $sale->id) ? 'selected' : '' ?> value="{{$sale->id}}">{{($sale->real_name) ? $sale->real_name : ''}} </option>
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
                            <a title="Xoá TN này" class="hidden btn-icon aoh" onclick="return confirm('Bạn muốn xóa data này?')" href="{{route('sale-delete',['id'=>$item->id])}}" role="button">
                                <svg class="icon me-2">
                                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-trash')}}"></use>
                                </svg>
                            </a>
                            @endif

                            {{-- <a title="Chốt đơn cho TN này" data-toggle="modal" data-sale-id="{{$item->id}}" data-target="#createOrder" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a> --}}
                            
                            @if ($item->id_order_new)
                                <a data-target="#createOrder" data-toggle="modal" title="Sửa đơn" data-tnsale-id="{{$item->id}}" data-id_order_new="{{$item->id_order_new}}" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a>
                            @else
                                <a data-target="#createOrder" data-toggle="modal" title="Chốt đơn" data-tnsale-id="{{$item->id}}" data-address="{{$item->address}}" data-name="{{$item->full_name}}" data-phone="{{$item->phone}}" class="hidden orderModal btn-icon aoh"><i class="fa fa-edit"></i></a>
                            @endif

                            @if ($item->old_customer == 1)
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
                        @elseif ($item->old_customer == 1)
                        <span class="fb span-col" style="cursor: pointer;">CSKH</span> 
                        @elseif ($item->old_customer == 2)
                        <span class="fb span-col" style="cursor: pointer;">Hotline</span> 
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

                    <span>Tổng: {{number_format($order->total)}}đ</span> <br>
                    <a target="_blank" href="{{route('view-order', $item->id_order)}}">Xem đơn</a>
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
        var idOrderNew = $(this).data('id_order_new');
        var TNSaleId = $(this).data('tnsale-id');
        console.log(TNSaleId);
        if (idOrderNew) {
            var link = "{{URL::to('/update-order/')}}";
            $("#createOrder iframe").attr("src", link + '/' + idOrderNew);
        } else {
            var phone = $(this).data('phone');
            var name = $(this).data('name');
            var address = $(this).data('address');

            var param = 'saleCareId=' + TNSaleId + '&phone=' + phone + '&name=' + name + '&address=' + address ;
            var link = "{{URL::to('/them-don-hang/')}}";
            $("#createOrder iframe").attr("src", link + '?' + param);

            //cập nhật TN Sale
            (function( $ ){
            $.fn.getIdOrderNew = function() {
                console.log('aaaa')
                setTimeout(function() {
                    var _token  = $("input[name='_token']").val();
                    $.ajax({
                        url: "{{ route('get-salecare-idorder-new') }}",
                        type: 'POST',
                        data: {
                            _token: _token,
                            TNSaleId,
                        },
                        success: function(data) {
                            if (data.id_order_new) {
                                if ($('.tr_' + TNSaleId + ' .id-order-new a').length == 0) {
                                    var td = $('.tr_' + TNSaleId + ' .id-order-new');
                                    td.wrapInner('<a href="' + data.link + '">' + data.id_order_new + '</a>');

                                    var aCreate = $('.tr_' + TNSaleId + ' td div a.orderModal');
                                    aCreate.data('id_order_new',  data.id_order_new);
                                    aCreate.attr('title', 'Sửa đơn');
                                }
                            
                            } 
                        }
                    });
           
                }, 3000);
            }; 
            })( jQuery );

            $('#createOrder').on('click', function () {
                $.fn.getIdOrderNew();
            });
           

            $('#close-main').on('click', function () {
                $.fn.getIdOrderNew();
            });
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
    // var dragCol = null;
    // function handleDragStart(e) {
    //     dragCol = this;
    //     e.dataTransfer.efferAllowed = 'move';
    //     e.dataTransfer.setData('text/html', this.outerHtml);
    // }

    // function handleDragOver(e) {
    //     if (e.preventDefault) {
    //         e.preventDefault();
    //     }
    //     e.dataTransfer.dropEffect = 'move';
    //     return false;
    // }

    // function handleDrop(e) {
    //     if (e.stopPropagation) {
    //         e.stopPropagation;
    //     }

    //     if (dragCol !== this) {
    //         var sourceIndex = Array.from(dragCol.parentNode.children).indexOf(dragCol);
    //         var targetIndex = Array.from(this.parentNode.children).indexOf(this);

    //         var table = document.getElementById('myTable');
    //         var rows = table.rows;
    //         for ( var i = 0; i < rows.length; i++) {
    //             var sourceCell = rows[i].cells[sourceIndex];
    //             var targetCell = rows[i].cells[targetIndex];

    //             var tempHTML = sourceCell.innerHTML;
    //             sourceCell.innerHTML = targetCell.innerHTML;
    //             targetCell.innerHTML = tempHTML;
    //         }
    //     }
    //     return false;
    // }

    // var cols = document.querySelectorAll('th');
    // [].forEach.call(cols, function(col) {
    //     col.addEventListener('dragstart', handleDragStart, false);
    //     col.addEventListener('dragover', handleDragOver, false);
    //     col.addEventListener('drop', handleDrop, false);
    // });

</script>


{{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
<script>
    $(function() {
        $('#listSale').select2();
        $('#typeData').select2();
        $('#careOrder').select2();
        $('#srcData').select2();
        $('#productFilter').select2();
        $('#statusTN').select2();
        $('#resultTN').select2();
        $('#statusOrderShip').select2();
        $('#statusDeal').select2();
    });
</script>
