<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Helpers\Helper;
use App\Models\CallResult;
use App\Models\SaleCare;
use App\Models\SrcPage;


class MarketingController extends Controller
{
    public function getDataMkt($req)
    {
        $list = SrcPage::orderBy('id', 'desc');

        if ($req->mkt_user && $req->mkt_user != -1) {
            $list = $list->where('user_digital', $req->mkt_user);
        }

        if ($req->src && $req->src != -1) {
            $list = $list->where('id', $req->src);
        }

        // dd($list->get());
        /** lấy data report(contact) từ list nguồn */
        $listFiltrSrc = $this->getListMktReportByListSrc($list, $req);
        $listFiltrSrc = $this->transferKey($listFiltrSrc);

        $rs = $this->getListMktReportOrder($req, $listFiltrSrc);
        if ($rs) {
            $rs = $this->cleanDataMktReport($rs);
        }

        return $rs;
    }

    public function marketingSearch($req)
    {
        // dd($req->all());
        $rs = $this->getDataMkt($req);
        
        $listMktUser = Helper::getListMktUser(Auth::user());
        $listGroup = Helper::getListGroup();
        $listSrc = SrcPage::orderBy('id', 'desc')->get();
        if (!isFullAccess(Auth::user()->role)) {
            $listSrc = $listSrc->where('user_digital', $req->mkt_user);
        }

        return view('pages.marketing.index')->with('list', $rs)
            ->with('listMktUser', $listMktUser)
            ->with('listSrc', $listSrc)
            ->with('listGroup', $listGroup);
    }

    public function transferKey($data)
    {
        /* 
        [ 4 => [
                "contact" => 3
                "name" => "A Plus - Dinh Dưỡng Đậm Đặc Siêu Kích Hoạt 0986.987.791"
                "type" => "pc"
                "id" => 15
                ]
        ]
        =>   [ 15 => [
                "contact" => 3
                "name" => "A Plus - Dinh Dưỡng Đậm Đặc Siêu Kích Hoạt 0986.987.791"
                "type" => "pc"
                "id" => 15
                ]
        ]
        */

        // dd($data);
        $newData = [];
        foreach ($data as $key => $item) {
            $newData[$item['id']] = $item;
        }
        // dd($newData);
        return $newData;
    }

    public function cleanDataMktReport($data)
    {
        foreach ($data as $key => $item) {
            // if (isset($item['order']) && $item['order'] == 0 && $item['contact'] == 0) {
            //     unset($data[$key]);
            // } else if ($item['contact'] == 0) {
            // // if ($item['contact'] == 0) {
            //     unset($data[$key]);
            // }
            if ((isset($item['order']) && $item['order'] == 0 && $item['contact'] == 0)
                || ($item['contact'] == 0 && !isset($item['order']))){
                unset($data[$key]);
            }
        }

        return $data;
    }
    public function marketingSrcSearch(Request $req)
    {

        $list = SrcPage::orderBy('id', 'desc');

        if ($req->search) {
            $list = $list->where('name', 'like', '%' . $req->search . '%');
        }
        
        if (($req->mkt_user && $req->mkt_user != -1)) {
            $list = $list->where('user_digital', $req->mkt_user);
        } else if (!isFullAccess(Auth::user()->role)) {
            $list = $list->where('user_digital', Auth::user()->id);
        }
        
        if ($req->group) {
            $list = $list->where('id_group', $req->group);
        }

        $listMktUser = Helper::getListMktUser(Auth::user());   
        $listGroup = Helper::getListGroup();
        $list = $list->paginate(30);

        return view('pages.marketing.src.index')->with('list', $list)
            ->with('listMktUser', $listMktUser)
            ->with('listGroup', $listGroup);
    }

    public function getListMktReport()
    {
        $sumContact = $sumOrder = $sumRateSuccess = $sumProduct = $sumTotal = $sumAvg = 0;
        $list = SrcPage::orderBy('id', 'desc');
        $data = [];

        $toMonth = date("Y/m/d", time());
        // dd("$toMonth - $toMonth");
        $reqData = [ 'daterange' => "$toMonth - $toMonth"];
        $req = new \Illuminate\Http\Request();
        $req->replace($reqData);
       
        foreach ($list->get() as $item) {
            $dataReport = $this->getDataReportBySrcId($item, $req);
            $data[]= $dataReport;
        }

        if ($sumContact > 0) {
            $newRate = $sumOrder / $sumContact * 100;
            $sumRateSuccess = round($newRate, 2);
        }

        if ($sumOrder > 0) {
            $sumAvg = $sumTotal / $sumOrder;
        }

        $data['sum'] = [
            'contact' => $sumContact,
            'order' => $sumOrder,
            'rate' => $sumRateSuccess,
            'product' => $sumProduct,
            'total' => $sumTotal,
            'avg' => $sumAvg
        ];

        return $data;
    }

    public function getListMktReportOrder($req, $listSrc)
    {
        if (!$listSrc) {
            return;
        }

        // dd($listSrc);
        $ordersController = new OrdersController();

        $userAdmin = User::find(1);

        $time = $req->daterange;
        $arrTime    = explode("-",$time); 
        $dataFilter['daterange'] = $arrTime;
        // dd($time);
        // $dataFilter['daterange'] = [$time[0], $time[1]];

        $status = $req->status;
        if (($status || $status == 0) && $status != 999) {
            $dataFilter['status'] = $status;
        }

        $category = $req->category;
        if ($category && $category != 999) {
            $dataFilter['category'] = $category;
        }

        $product = $req->product;
        if ($req->product && $product != 999) {
            $dataFilter['product'] = $product;
        }

        $group = $req->group;
        if ($req->group && $group != 999) {
            $dataFilter['group'] = $group;
        }

        $type_customer = $req->type_customer;
        if (isset($type_customer) && $type_customer != 999 && $type_customer != -1) {
            $dataFilter['type_customer'] = $type_customer;
        }

        $listOrders = $ordersController->getListOrderByPermisson($userAdmin, $dataFilter);
        $listOrderKeySrc = [];
       
        foreach ($listOrders->get() as $order) {
            // dd($order->saleCare)
            // if ($order->id != 3231) {
            //     continue;
            // }

            if ($req->type_customer && $req->type_customer != -1 
                && $order->saleCare->old_customer != $req->type_customer) {
                continue;
            }

            if (!empty($order->saleCare)) {
                $srcPage = $this->getSrcPageFromSaleCare($order->saleCare);
                if ($srcPage) {
                    $listOrderKeySrc[$srcPage->id][] = [
                        'total' => $order->total,
                        'qty' => $order->qty,
                        'id' => $order->id,
                    ];
                }
            }
            
        }

        /** lọc loại khách hàng */
        if ($req->type_customer && $req->type_customer != -1) {
            //khách mới
            if ($req->type_customer == 0) {
               
            }
        }

        /* gộp 2 mảng: 1 mảng src chỉ có số contact trong thời gian chỉ định và
        1 mảng có data order thuộc src
        */
        $result = [];
        foreach ($listSrc as $k => $src) {
            $countOrder = $total = $qty = $avg = $rate = 0;
            // dd($src);
            // if ($src['id'] != 32) {
            //     continue;
            // }
            // echo $k . '<br>';
            if (array_key_exists($k, $listOrderKeySrc)) {
                // echo "<pre>";
                // print_r($listOrderKeySrc[$k]);
                // echo "</pre>";

                // echo $src['name'];

                
                foreach ($listOrderKeySrc[$k] as $item) {
                    $total += $item['total'];
                    $qty += $item['qty'];
                }

                $countOrder = count($listOrderKeySrc[$k]);
                if ($countOrder > 0) {
                    $avg =  $total / $countOrder;
                }

                if ($src['contact'] > 0) {
                    $rate =  round($countOrder / $src['contact'] * 100, 2);
                } else {
                    $rate =  round($countOrder * 100, 2);
                } 
            }

            $result[] = [
                'order' => $countOrder,
                'total' => $total,
                'product' => $qty,
                'avg' => $avg,
                'contact' => $src['contact'],
                'name' => $src['name'],
                'type' => $src['type'],
                'rate' => $rate,
            ];
        }

        // dd($result);
        // die();
        
        return $result;
    }

    public function getSrcPageFromSaleCare($saleCare)
    {
        // $src = SrcPage::orderBy('id', 'desc');
        if ($saleCare && empty($saleCare->id_src) || !$saleCare->id_src) {

            /** đơn hàng đc tạo từ data cskh ko có  page_id/page_name/page_link
             * => lấy data TN ban đầu
             */
            if (!$saleCare->page_id && !$saleCare->page_name && !$saleCare->page_link) {
                $saleCare = SaleCare::orderBy('id', 'asc')->where('phone', $saleCare->phone)->first();
            }

            if ($saleCare->page_id && $saleCare->page_id != 'tricho' && $saleCare->page_id != 'ladi') {
                $pageId = $saleCare->page_id;
                $src = SrcPage::where('id_page', $pageId);
            } else if ($saleCare->page_link) {
                $link = $saleCare->page_link;
                $src = SrcPage::where('link',  $link);
            } else if ($saleCare->page_name == 'hotline') {
                $src = SrcPage::where('page_name', $saleCare->page_name);
            } else {
                $src = SrcPage::where('id_page', 'tricho');
            }

            if ($first = $src->first()) {
                return $first;
            }
        }
    }
    public function getListMktReportByListSrc($list, $req)
    {
        $data = [];
        foreach ($list->get() as $item) {
            // echo $item->id . "<br>";
            // if ($item->id != 32) {
            //     continue;
            // }
            $dataReport = $this->getDataReportBySrcId($item, $req);
            $data[]= $dataReport;
        }

        return $data;
    }

    public function getListSaleCareBySrcId($item, $req)
    {
        $list   = SaleCare::orderBy('id', 'desc');
        if (isset($req['daterange']) || !empty($req->daterange)) {
            $dateRange = (isset($req['daterange'])) ? $req['daterange'] : $req->daterange;

            $time = $dateRange;
            if (!is_array($dateRange)) {
                $time = explode("-",$dateRange); 
            }

            // dd($time);
            // $toMonth    = date("Y-m-d", time());
            // $toMonth    = '2024-09-14';
            // $time = ['2024-09-01', '2024-09-30'];

            $timeBegin  = str_replace('/', '-', $time[0]);
            $timeEnd    = str_replace('/', '-', $time[1]);
            $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
            $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

            $list = $list->whereDate('created_at', '>=', $dateBegin)
                ->whereDate('created_at', '<=', $dateEnd);
        }

        // dd($list->get());
        if (isset($req->type_customer) && (int)$req->type_customer != -1) {
            $list = $list->where('old_customer', $req->type_customer);
        }

        foreach ($list->get() as $sale) {
            if (!$sale->page_id && !$sale->page_name && !$sale->page_link) {
                $scNoSrc[] = $sale;
            }
        }

        $srcType = [
            'filterByIdSrc' => $item->id,
            'getAll'  => $item->id
        ];

        $list = $list->where(function($query) use ($srcType) {
            foreach ($srcType as $k => $term) {
                if ($k == 'filterByIdSrc') {
                    $query->orWhere('src_id', $term);
                } else {
                    $src = SrcPage::find($term);
                    if (!$src) {
                        return ;
                    }

                    if ($src->type == 'pc') {
                        $query->orWhere('page_id', $src->id_page);
                    } else if ($src->type == 'ladi') {
                        $query->orWhere('page_link', $src->link);
                    } else if ($src->type == 'hotline') {
                        $query->orWhere('page_id', $src->id_page);
                    } else if  ($src->type == 'old') {
                        $query->orWhere('page_name', $src->name);
                    } else {
                        $query->orWhere('page_id', 'tricho');
                    }
                }
            }
        });

        // if ($item->type == 'pc') {
        //     $list = $list->where('page_id', $item->id_page);
        // } else if ($item->type == 'ladi') {
        //     $list = $list->where('page_link', $item->link);
        // } else if ($item->type == 'hotline') {
        //     // $saleCare = $saleCare->where('page_name', $item->type);
        //     $list = $list->where('page_id', 'like', '%' . $item->id_page .'%');
        // } else if  ($item->type == 'old') {
        //     $list = $list->where('page_name', $item->name);
        // } else {
        //     $list = $list->where('page_id', 'tricho');
        // }

        return $list;
    }

    public function getDataReportBySrcId($item, $req)
    {
        $countSaleCare = 0;

        $saleCare = $this->getListSaleCareBySrcId($item, $req);
        
        /*
        $saleCare   = SaleCare::orderBy('id', 'desc');

        if ($req->daterange) {
            $time    = explode("-",$req->daterange); 

            // $toMonth    = date("Y-m-d", time());
            // $toMonth    = '2024-09-14';
            // $time = ['2024-09-01', '2024-09-30'];

            $timeBegin  = str_replace('/', '-', $time[0]);
            $timeEnd    = str_replace('/', '-', $time[1]);
            $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
            $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

            $saleCare = $saleCare->whereDate('created_at', '>=', $dateBegin)
                ->whereDate('created_at', '<=', $dateEnd);
        }
        
        if (isset($req->type_customer) && (int)$req->type_customer != -1) {
            $saleCare = $saleCare->where('old_customer', $req->type_customer);
        }
        // dd($saleCare->get());
        $scNoSrc = [];

        foreach ($saleCare->get() as $sale) {
            if (!$sale->page_id && !$sale->page_name && !$sale->page_link) {
                $scNoSrc[] = $sale;
            }
        }
        // $src = ['389136690940452', '378087158713964', '381180601741468', 'Hotline - Tricho', 'Khách Cũ Tricho'];
        if ($item->type == 'pc') {
            $saleCare = $saleCare->where('page_id', $item->id_page);
        } else if ($item->type == 'ladi') {
            $saleCare = $saleCare->where('page_link', $item->link);
        } else if ($item->type == 'hotline') {
            // $saleCare = $saleCare->where('page_name', $item->type);
            $saleCare = $saleCare->where('page_id', 'like', '%' . $item->id_page .'%');
        } else if  ($item->type == 'old') {
            $saleCare = $saleCare->where('page_name', $item->name);
        } else {
            $saleCare = $saleCare->where('page_id', 'tricho');
        }
        */

        $countSaleCare   = $saleCare->count();

        $result = [
            'contact' => $countSaleCare,
            'name' => $item->name,
            'type' => $item->type,
            'id' => $item->id,
        ];

        return $result;
    }

    public function marketingSrcUpdate($id)
    {
        $dataSrc = SrcPage::find($id);
        if ($dataSrc) {
            return view('pages.marketing.src.add')->with('dataSrc', $dataSrc);
        }
    }

    public function marketingSrcSave(Request $req)
    {
        $validator      = Validator::make($req->all(), [
            'name'  => 'required',
            'id_group'   => 'required',
            'type'  => 'required',
            'user_digital'  => "required|not_in:-1",
        ],[
            'name.required' => 'Nhập tên nguồn',
            'idGroup.required' => 'Chọn nhóm',
            'type.required' => 'Chọn loại kết nối',
            'userDigital.required' => 'Chọn người quảng cáo',
        ]);

        // dd($req->all());
        if ($validator->passes()) {
            
            if (isset($req->id)) {
                $srcPage = SrcPage::find($req->id);
                $text = 'Cập nhật nguồn marketing thành công.';
            } else {
                $srcPage = new SrcPage();
                $text = 'Tạo nguồn marketing thành công.';
            }

            $srcPage->type = $req->type;
            $srcPage->name = $req->name;
            $srcPage->user_digital = $req->user_digital;
            $srcPage->link = $req->link;
            $srcPage->id_page = $req->id_page;
            $srcPage->id_group = $req->id_group;
            $srcPage->token = $req->token;
            
            $srcPage->save();
            notify()->success($text, 'Thành công!');
        } else {
            // dd($validator->errors()->first());
            notify()->error('Lỗi khi lưu nguồn marketing', 'Thất bại!');
        }

        return back();
    }
    public function marketingSrcAdd()
    {
        return view('pages.marketing.src.add');
        // ->with('listSale', $listSale)->with('listSrc', $listSrc);
    }

    public function srcPage()
    {
        $list = SrcPage::orderBy('id', 'desc')->paginate(30);
        $checkAll = isFullAccess(Auth::user()->role);
        if (!$checkAll) {
            $list = $list->where('user_digital', Auth::user()->id);
        }
        $listMktUser = Helper::getListMktUser(Auth::user());
        
        $listGroup = Helper::getListGroup();
        return view('pages.marketing.src.index')->with('list', $list)
            ->with('listMktUser', $listMktUser)
            ->with('listGroup', $listGroup);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $r)
    {
        $checkAll = isFullAccess(Auth::user()->role);
        if (count($r->all())) {
            if (!$checkAll) {
                $r['mkt_user'] = Auth::user()->id;
            }
            return $this->marketingSearch($r);
        } 

        $today = date("Y/m/d") . '-' . date("Y/m/d");
        // $today = date() . '-' . date();
        // dd($today);
        // $today = '2024/11/21-2024/11/21';
        $params = [
            'daterange' => $today,
        ];

        
        if (!$checkAll) {
            $params['mkt_user'] = Auth::user()->id;
        }

        $request = new \Illuminate\Http\Request();
        $request->replace($params);
       

        return $this->marketingSearch($request);

        // $data = $this->getListMktReport();
        // dd($data);
        // $data = $this->cleanDataMktReport($data);

        // $listMktUser = Helper::getListMktUser();
        // $listGroup = Helper::getListGroup();
        // return view('pages.marketing.index')->with('list', $data)
        //     ->with('listMktUser', $listMktUser)
        //     ->with('listGroup', $listGroup);
    }
}
