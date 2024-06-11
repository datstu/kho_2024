<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\SaleCare;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $toMonth      = date("Y-m-d", time());
        $item = $this->filterByDate('day', $toMonth);

        $category   = Category::where('status', 1)->get();
        $sales   = User::where('status', 1)->where('is_sale', 1)->orWhere('is_cskh', 1)->get();

        return view('pages.home')->with('item', $item)->with('category', $category)->with('sales', $sales);
    }
    
      /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterTotal(Request $req)
    {
        if ($req->type == 'day') {
            $today      = date('Y-m-d', time());
            $ordersSum  = Orders::where('created_at', '>=', $today)->get()->sum('total');

            $yesterday  = date('Y-m-d',strtotime("-1 days"));
            $ordersYesterdaySum = Orders::where('created_at', '>=', $yesterday)
                ->where('created_at', '<', $today)
                ->get()->sum('total');
            
            $totalDay           = $ordersSum + $ordersYesterdaySum;
            $percentTotalDay    = round(($ordersSum - $ordersYesterdaySum) * 100 / $totalDay, 2);

            $countOrders    = Orders::where('created_at', '>=', $today)->get()->count();
            $countOrdersYes =  Orders::where('created_at', '>=', $yesterday)
                ->where('created_at', '<', $today)
                ->get()->count();
            $countOrdersDay     = $countOrders + $countOrdersYes;
            $percentCountDay    = round(($countOrders - $countOrdersYes) * 100 / $countOrdersDay, 2);

            // $sumTotal = Orders::get()->sum('total');
            $avgOrders = $ordersSum / $countOrders;
            $avgOrdersYes = $ordersYesterdaySum / $countOrdersYes;
            $totalAvgDay           = $ordersSum + $ordersYesterdaySum;
            $percentAvg    = round(($avgOrders - $avgOrdersYes) * 100 / $totalAvgDay, 2);
                // dd($percentAvg);
            return response()->json([
                'totalSum'          => number_format($ordersSum) . ' đ',
                'today'             => date('d-m-Y', time()),
                'percentTotalDay'   => '(' . $percentTotalDay .'%)',
                'countOrders'       => $countOrders,
                'percentCountDay'   => '(' . $percentCountDay .'%)',
                'avgOrders'         => number_format($avgOrders) . ' đ',
                'percentAvg'        => '(' . $percentAvg .'%)',
            ]);
        }
    }

    public function filterByDate($type, $date)
    {
        $result = [];
        switch ($type) {
            case "day":
                $countOrders = $ordersSum = $countSaleCare = $rateSuccess = $avgOrders = 0;
                $ordersCtl = new OrdersController();
                $dataFilter['daterange'] = [$date, $date];
                // $dataFilter['daterange'] = ['2024-05-25', '2024-05-25'];
                // dd($dataFilter['daterange']);
                $listOrder      = $ordersCtl->getListOrderByPermisson(Auth::user(),$dataFilter);
                $countOrders    = $listOrder->count();
                $ordersSum      = $listOrder->sum('total');
                
                if ($countOrders > 0) {
                    $avgOrders = $ordersSum / $countOrders;
                }
               

                $ordersCtl = new SaleController();
                $saleCare          = $ordersCtl->getListSalesByPermisson(Auth::user(), $dataFilter)
                    ->where('old_customer', 0);
                $countSaleCare = $saleCare->count();
                // dd($countSaleCare);
                /** tỷ lệ chốt = số đơn/số data */
                if ($countSaleCare == 0) {
                    $rateSuccess = $countOrders * 100;
                } else {
                    $rateSuccess = $countOrders / $countSaleCare * 100;
                }

                $rateSuccess = round($rateSuccess, 2);
                /** old code */
                // $percentAvg         = $avgOrders = $percentCountDay = $percentTotalDay = 0;
                // $ordersSum          = Orders::whereDate('created_at', '=', $date)
                //     // ->where('status', 3)
                //     ->get()->sum('total');
                // $yesterday          = date('Y-m-d',strtotime("$date -1 days"));
                // $ordersYesterdaySum = Orders::whereDate('created_at', '=', $yesterday)
                // // ->where('status', 3)
                //     ->get()->sum('total');
                
                // $totalDay           = $ordersSum + $ordersYesterdaySum;

                // $countOrders        = Orders::whereDate('created_at', '=', $date)
                //     // ->where('status', 3)
                //     ->get()->count();
                // $countOrdersYes     =  Orders::whereDate('created_at', '=', $yesterday)
                //     // ->where('status', 3)
                //     ->get()->count();
                // $countOrdersDay     = $countOrders + $countOrdersYes;

                // if ($ordersSum) {
                //     $percentTotalDay    = round(($ordersSum - $ordersYesterdaySum) * 100 / $totalDay, 2);
                //     $avgOrders = $ordersSum / $countOrders;
                // }
                
                // if ($countOrdersDay > 0) {
                //     $percentCountDay    = round(($countOrders - $countOrdersYes) * 100 / $countOrdersDay, 2);
                // }
 
                // if ($ordersYesterdaySum > 0) {
                //     $avgOrdersYes   = $ordersYesterdaySum / $countOrdersYes;
                //     $totalAvgDay    = $ordersSum + $ordersYesterdaySum;
                //     $percentAvg     = round(($avgOrders - $avgOrdersYes) * 100 / $totalAvgDay, 2);
                // }

              
                // $countSaleCare = SaleCare::whereDate('created_at', '>=', $date)
                //     ->whereDate('created_at', '<=', $yesterday)->count();
                // $countSaleCare = SaleCare::->whereDate('created_at', '>', $yesterday)     
                // ->whereDate('created_at', '<=', $date)->count();

                // dd($yesterday);
                
                $result = [
                    'totalSum'      => number_format($ordersSum) . 'đ',
                    // 'percentTotal'  => '(' . (($percentTotalDay > 0) ? '+' : '' ) . $percentTotalDay  .'%)',
                    'countOrders'   => $countOrders,
                    // 'percentCount'  => '(' . (($percentCountDay > 0) ? '+' : '' ) . $percentCountDay .'%)',
                    'avgOrders'     => number_format($avgOrders) . 'đ',
                    // 'percentAvg'    => '(' . (($percentAvg > 0) ? '+' : '' ) . $percentAvg  .'%)',
                    'rateSuccess'   =>  $rateSuccess . '%',
                    'countSaleCare' =>  $countSaleCare,
                    ];
                break;
            case "month":
                //lấy tháng trong chuỗi '2024/03/24' => 03
                $month      = date('m', strtotime($date));
                $ordersMonthSum  = Orders::whereMonth('created_at', '=', $month)
                    // ->where('status', 3)
                    ->get()->sum('total');
                
                //lấy tháng trước của tháng được chọn => 02
                $lastMonth          = date('m',strtotime("$date -1 month"));
                $ordersLastMonthSum = Orders::whereMonth('created_at', '=', $lastMonth)
                    // ->where('status', 3)
                    ->get()->sum('total');
                
                /* tính phần trăm tăng giảm của tháng được chọn so với tháng trước dựa trên 'total'
                    round() : chỉ lấy và làm tròn 2 chữ số thập phân
                */
                $totalMonth         = $ordersMonthSum + $ordersLastMonthSum;
                $percentTotalMonth  = $percentCountMonth = 0;
                if ($ordersMonthSum > 0) {
                    $percentTotalMonth    = round(($ordersMonthSum - $ordersLastMonthSum) * 100 / $totalMonth, 2);
                }
                
                /* tính phần trăm tăng giảm của tháng được chọn so với tháng trước dựa trên số lượng
                    round() : chỉ lấy và làm tròn 2 chữ số thập phân
                */
                $countOrders            = Orders::whereMonth('created_at', '=', $month)
                    // ->where('status', 3)
                    ->get()->count();
                $countOrdersLast        =  Orders::whereMonth('created_at', '=', $lastMonth)
                    // ->where('status', 3)
                    ->get()->count();
                $countOrdersMonth       = $countOrders + $countOrdersLast;
                if ($countOrdersMonth > 0) {
                    $percentCountMonth      = round(($countOrders - $countOrdersLast) * 100 / $countOrdersMonth, 2);
                }
                
                // trung bình đơn = tổng tiền / số đơn
                $avgOrders = $avgOrdersLastMonth = $percentAvg = 0;
                if ($ordersMonthSum > 0) {
                    $avgOrders = $ordersMonthSum / $countOrders;
                }

                if ($countOrdersLast > 0) {
                    $avgOrdersLastMonth = $ordersLastMonthSum / $countOrdersLast;
                }

                if ($avgOrders > 0) {
                    $totalAvgMonthLastMonth = $avgOrders + $avgOrdersLastMonth;
                    $percentAvg    = round(($avgOrders - $avgOrdersLastMonth) * 100 / $totalAvgMonthLastMonth, 2);    
                }
               
                $result = [
                        'totalSum'      => number_format($ordersMonthSum) . 'đ',
                        'percentTotal'  => '(' . (($percentTotalMonth > 0) ? '+' : '-' ) . $percentTotalMonth  .'%)',
                        'countOrders'   => $countOrders,
                        'percentCount'  => '(' . (($percentCountMonth > 0) ? '+' : '-' ) . $percentCountMonth .'%)',
                        'avgOrders'     => number_format($avgOrders) . 'đ',
                        'percentAvg'    => '(' . (($percentAvg > 0) ? '+' : '-' ) . $percentAvg  .'%)',
                    ];
              break;

            case "year":
                    //lấy năm trong chuỗi '2024/03/24' => 2024
                    $year      = date('Y', strtotime($date));
                    $ordersYearSum  = Orders::whereYear('created_at', '=', $year)
                        // ->where('status', 3)
                        ->get()->sum('total');
                   
                    //lấy năm trước của date được chọn => 2023
                    $lastYear          = date('Y',strtotime("$year -1 year"));
                    $ordersLastYearSum = Orders::whereYear('created_at', '=', $lastYear)
                        // ->where('status', 3)
                        ->get()->sum('total');
                    
                    /* tính phần trăm tăng giảm của năm được chọn so với năm trước dựa trên 'total' */
                    $totalYear        = $ordersYearSum + $ordersLastYearSum;
                    $percentTotalYear  = $percentCountYear = 0;
                    if ($ordersYearSum > 0) {
                        $percentTotalYear    = round(($ordersYearSum - $ordersLastYearSum) * 100 / $totalYear, 2);
                    }
                
                    /* tính phần trăm tăng giảm của năm được chọn so với năm trước dựa trên số lượng
                        round() : chỉ lấy và làm tròn 2 chữ số thập phân
                    */
                    $countOrders            = Orders::whereYear('created_at', '=', $year)
                        // ->where('status', 3)
                        ->get()->count();
                    $countOrdersLast        =  Orders::whereYear('created_at', '=', $lastYear)
                        // ->where('status', 3)
                        ->get()->count();
                    $countOrdersYear       = $countOrders + $countOrdersLast;
                    if ($countOrdersYear > 0) {
                        $percentCountYear      = round(($countOrders - $countOrdersLast) * 100 / $countOrdersYear, 2);
                    }
        
                    // trung bình đơn = tổng tiền / số đơn
                    $avgOrders = $avgOrdersLastYear = $percentAvg = 0;
                    if ($ordersYearSum > 0) {
                        $avgOrders = $ordersYearSum / $countOrders;
                    }

                    if ($countOrdersLast > 0) {
                        $avgOrdersLastYear = $ordersLastYearSum / $countOrdersLast;
                    }

                    if ($avgOrders > 0) {
                        $totalAvgYear = $avgOrders + $avgOrdersLastYear;
                        $percentAvg    = round(($avgOrders - $avgOrdersLastYear) * 100 / $totalAvgYear, 2);    
                    }
                
                    $result = [
                            'totalSum'      => number_format($ordersYearSum) . 'đ',
                            'percentTotal'  => '(' . (($percentTotalYear > 0) ? '+' : '-' ) . $percentTotalYear  .'%)',
                            'countOrders'   => $countOrders,
                            'percentCount'  => '(' . (($percentCountYear > 0) ? '+' : '-' ) . $percentCountYear .'%)',
                            'avgOrders'     => number_format($avgOrders) . 'đ',
                            'percentAvg'    => '(' . (($percentAvg > 0) ? '+' : '-' ) . $percentAvg  .'%)',
                        ];
                break;

            case "daterange":
                $startString    = str_replace('/', '-', $date[0]);
                $startDate      = date('Y-m-d', strtotime($startString));
                $endString      = str_replace('/', '-', $date[1]);
                $endDate        = date('Y-m-d', strtotime($endString));
                $queryOrder     = Orders::whereDate('created_at', '<=', $endDate)
                    ->whereDate('created_at', '>=', $startDate);
                    // ->where('status', 3);
                $totalSum       = $queryOrder->get()->sum('total');
                
                $countOrders    = $queryOrder->get()->count();

                // trung bình đơn = tổng tiền / số đơn
                $avgOrders = 0;
                if ($totalSum > 0) {
                    $avgOrders = $totalSum / $countOrders;
                }
             
                $result = [
                        'totalSum'      => number_format($totalSum) . 'đ',
                        'percentTotal'  => '',
                        'countOrders'   => $countOrders,
                        'percentCount'  => '',
                        'avgOrders'     => number_format($avgOrders) . 'đ',
                        'percentAvg'    => '',
                    ];
                break;
            default: break;    
        }
        
        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function filterTotalSales(Request $req) {
        // dd($this->filterByDate($req->type, $req->date));
        return response()->json($this->filterByDate($req->type, $req->date));
    }

    public function filterDashboard(Request $req) {
        $rateSuccess = $countSaleCare = 0;
        $ordersController = new OrdersController();

        $time = $dataFilter['daterange']    = $req->date;

        // $time       = $req->date;
        $timeBegin  = str_replace('/', '-', $time[0]);
        $timeEnd    = str_replace('/', '-', $time[1]);

        // $dateBegin  = date('Y-m-d', strtotime("$timeBegin"));
        // $dateEnd    = date('Y-m-d', strtotime("$timeEnd"));
        // $dataFilter['daterange']['dateBegin']   = $dateBegin;
        // $dataFilter['daterange']['dateEnd']     = $dateEnd;

        // $list->whereDate('created_at', '>=', $dateBegin)
        //     ->whereDate('created_at', '<=', $dateEnd);
        if ($req->status != 999) {
            $dataFilter['status'] = $req->status;
        }

        $category = $req->category;
        if ($category != 999) {
            $dataFilter['category'] = $category;
        }

        // dd($dataFilter);
        $product = $req->product;
        if ($req->product && $product != 999) {
            $dataFilter['product'] = $product;
        }

        // $countSaleCare = SaleCare::whereDate('created_at', '>=', $dateBegin)
        //     ->whereDate('created_at', '<=', $dateEnd)
        //     ->where('old_customer', 0)
        //     ->count();
        if ($req->sale && $req->sale != 999) {
            $dataFilter['sale'] = $req->sale;
            // $countSaleCare = SaleCare::whereDate('created_at', '>=', $dateBegin)
            // ->whereDate('created_at', '<=', $dateEnd)->where('assign_user', $req->sale)
            // ->where('old_customer', 0)->count();
        }
 
        $data = $ordersController->getListOrderByPermisson(Auth::user(), $dataFilter);
        $countOrders = $data->count();
        // $list       = $data->paginate(50);
        $sumProduct = $data->sum('qty');
        // dd($sumProduct);

        $totalSum  = $data->sum('total');
        $avgOrders = 0;
        if ($totalSum > 0) {
            $avgOrders = $totalSum / $countOrders;
        }
        
        /** tỷ lệ chốt: số đơn/ số data */
        $newFilter['daterange'] =  $req->date;
        $newFilter['sale'] = $req->sale;
        $countOrdersRate = $ordersController->getListOrderByPermisson(Auth::user(), $newFilter)->count();
       
       
        $saleCtl = new SaleController();
        $saleCare  = $saleCtl->getListSalesByPermisson(Auth::user(), $dataFilter)
            ->where('old_customer', 0);
            // dd($saleCare);
            // ->where(function ($query) {
            //     $query->where('old_customer', 0)
            //     ->orWhereNull('old_customer');
            // });
            // ->whereIn('old_customer', [0, NULL]);
            // ->orWhereNull('old_customer');
        $countSaleCare = $saleCare->count();

        /** tỷ lệ chốt = số đơn/số data */
        if ($countSaleCare == 0) {
            $rateSuccess = $countOrders * 100;
        } else {
            $rateSuccess = $countOrders / $countSaleCare * 100;
        }

        // dd($countOrdersRate);
        if ($countSaleCare == 0) {
            $rateSuccess = $countOrdersRate * 100;
        } else {
            $rateSuccess = $countOrdersRate / $countSaleCare * 100;
        }
       
        $rateSuccess = round($rateSuccess, 2);
    //    dd($rateSuccess);
        $result = [
            'totalSum'      => number_format($totalSum) . 'đ',
            'percentTotal'  => '',
            'countOrders'   => $countOrders,
            'percentCount'  => '',
            'avgOrders'     => number_format($avgOrders) . 'đ',
            'percentAvg'    => '',
            'sumProduct'    => '(' . $sumProduct . ' sản phẩm)',
            'rateSuccess'   =>  $rateSuccess . '%',
            'countSaleCare' =>  $countSaleCare
        ];
        return $result;
    }
}
