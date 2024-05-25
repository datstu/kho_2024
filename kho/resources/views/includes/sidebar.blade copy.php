
<ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
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

    <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-voice-over-record')}}"></use>
        </svg>TeleSale</a>
        <ul class="nav-group-items">
            <li class="nav-item"><a class="nav-link" href="{{route('sale-index')}}"><span class="nav-icon"></span>Tác nghiệp Sale</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('call-index')}}"><span class="nav-icon"></span>QL Call</a></li>
        </ul>
    </li>
    
    @if ($checkAll)

    <li class="nav-item"><a class="nav-link" href="{{route('manage-user')}}">
            <svg class="nav-icon">
                <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-chart-pie')}}"></use>
            </svg> Thành viên</a>
    </li>
    <li class="nav-item"><a class="nav-link" href="{{route('manage-user')}}">
        <svg class="nav-icon">
            <use xlink:href="{{asset('public/vendors/@coreui/icons/svg/free.svg#cil-chart-pie')}}"></use>
        </svg> Cài đặt</a>
    </li>
    @endif
</ul>
<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>