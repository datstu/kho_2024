
<ul class="sidebar-nav " data-coreui="navigation" data-simplebar="">
    <li class="nav-item"><a class="nav-link" href="{{route('home')}}">
            <svg class="nav-icon">
                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-speedometer')}}"></use>
            </svg> Dashboard</a></li>

    <li class="nav-title">Development</li>

 <?php $checkAll = isFullAccess(Auth::user()->role);?>

    @if ($checkAll)

    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-puzzle')}}"></use>
            </svg> Robot</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="robot.phtml"><span class="nav-icon"></span> Dạy Robot</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span class="nav-icon"></span> Cấu hình
                    FB</a></li>
        </ul>
    </li>

    @endif

    @if ($checkAll)
    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
            <svg class="nav-icon">
                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-grid')}}"></use>
            </svg>Kho</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="{{route('order')}}"><span class="nav-icon"></span> Đơn
                    hàng</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('product')}}">
                <span class="nav-icon"></span> Sản phẩm</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('category')}}"><span class="nav-icon"></span> Danh
                    mục</a></li>
        </ul>
    </li>
    @endif
    
    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-voice-over-record')}}"></use>
        </svg>TeleSale</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="{{route('sale-index')}}"><span class="nav-icon"></span>Tác nghiệp Sale</a></li>
        </ul>
    </li>

    @if ($checkAll)
    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-voice-over-record')}}"></use>
        </svg>Digital Marketing</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="{{route('sale-index')}}"><span class="nav-icon"></span>Ladipage</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('sale-index')}}"><span class="nav-icon"></span>Fanpge</a></li>
        </ul>
    </li>
    @endif
    
    @if ($checkAll)
    <li class="nav-item"><a class="nav-link" href="{{route('manage-group')}}">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-group')}}"></use>
        </svg>QL Nhóm</a>
    </li>

    <li class="nav-item"><a class="nav-link" href="{{route('manage-user')}}">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-chart-pie')}}"></use>
        </svg> Thành viên</a>
    </li>

    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-settings')}}"></use>
        </svg>Cài đặt</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="{{route('setting-general')}}"><span class="nav-icon"></span>Chung</a></li>
            <li class="nav-group"><a class="nav-link nav-group-toggle">QL TN Sale</a>
                <ul class="nav-group-items">
                    <li class="nav-item"><a class="nav-link" href="{{route('category-call')}}"><span class="nav-icon"></span>Loại TN</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('call-index')}}"><span class="nav-icon"></span>Thiết lập TN Sale</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="{{route('manage-src')}}" class="nav-link">QL Nguồn data</a></li>
        </ul>
    </li>
    @endif
</ul>
<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>