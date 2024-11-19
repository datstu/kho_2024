<?php

namespace App\Http\Controllers;

use App\Models\CategoryCall;
use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\SaleCare;
use App\Helpers\Helper;
use App\Models\CallResult;
use App\Models\Group;
use App\Models\Product;
use App\Models\SaleCareHistoryTN;
use App\Models\TypeDate;
use PHPUnit\TextUI\Help;
use Illuminate\Support\Facades\Route;
use App\Models\SrcPage;
use Illuminate\Support\Facades\File as File2;
use Image;
class SaleController extends Controller
{
    public function saveBoxTN(Request $req)
    {
        $input = $req->all();
        $validator      = Validator::make($input, [
            'note'      => 'required',
            ],[
                'name.required' => 'Nhập ghi chú cho tác nghiệp',
            ]
        );

        if ($validator->passes()) {
            $files = $files_remove = [];
            // dd($req->file('filenames'));
            if ($req->id) {
                $his = SaleCareHistoryTN::find($req->id); 
                if (isset($input['images_uploaded'])) {
                    $files_remove = array_diff(json_decode($input['images_uploaded_origin']), $input['images_uploaded']);
                    $files = array_merge($input['images_uploaded'], $files);
                } else if (isset($input['images_uploaded_origin'])) {
                    $files_remove = json_decode($input['images_uploaded_origin']);
                }
            } else {
                $his = new SaleCareHistoryTN();
                $his->sale_id = $req->sale_id;
            }

           
            if($req->hasfile('filenames'))
            {
                foreach($req->file('filenames') as $file)
                {
                    $name = time().rand(1,100).'.'.$file->extension();
                    // $file->move(public_path('files'), $name);  
                    $path = public_path('files') . "/" . $name;
                    Image::make($file->getRealPath())->resize(300, 500)->save($path);
                    $files[] = $name;
                }
            }

            $his->img = json_encode($files);
            $his->note = $req->note;
            if ($his->save()) {
                foreach ($files_remove as $file_name) {
                    File2::delete(public_path("files/" . $file_name));
                }
            }
            // notify()->success('Lưu TN hôm nay thành công', 'Thành công!');
            return redirect()->back();
            // return redirect()->route('sale-view-TN-box', ['id' => $req->sale_id]);
        } else {
            // dd($validator->errors());
            // notify()->error('Đã xảy ra lỗi khi lưu tác nghiệp hôm nay', 'Thất bại!');
            return back()->withErrors($validator->errors());
        }
    }

    public function saleViewSaveTNBox($id)
    {
        $saleCare = SaleCare::find($id);
        $history = SaleCareHistoryTN::where('sale_id', $id)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->first();
        $listHistory = $saleCare->listHistory;
            // notify()->success('Lưu TN', 'Thành công!');
        return view('pages.sale.addBoxTN')->with('history', $history)
            ->with('saleId', $id)->with('saleCare', $saleCare)
            ->with('listHistory', $listHistory);
    }
    public function saleViewListTNBox($id)
    {
        $saleCare = SaleCare::find($id);

        if ($saleCare) {
            $listHistory = $saleCare->listHistory;
            return view('pages.sale.historyBoxTN')->with('saleId', $id)
                ->with('listHistory', $listHistory)->with('saleCare', $saleCare);
        }
        
        return redirect('/');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $r)
    {
        if (count($r->all())) {
            return $this->filterSalesByDate($r);
        }

        $helper     = new Helper();
        $listCall   = $helper->getListCall()->get();
        $sales      = Helper::getListSale()->get();
       
        $saleCare   = $this->getListSalesByPermisson(Auth::user());
        $saleCare   = $saleCare->paginate(50);

        $listSrc    = SrcPage::orderBy('id', 'desc')->get();
        $groups     = Group::orderBy('id', 'desc')->get();
        $callResults = CallResult::orderBy('id', 'desc')->get();
        $typeDate = TypeDate::orderBy('id', 'desc')->get();
        $listMktUser = Helper::getListMktUser();
        $listTypeTN = CategoryCall::orderBy('id', 'asc')->get();
        $listProduct = Product::select('product.*')->orderBy('product.id', 'desc')
            ->join('detail_product_group','detail_product_group.id_product', '=', 'product.id')
            ->where('product.status', 1)->distinct()->get();

        return view('pages.sale.index')->with('listSrc', $listSrc)
            ->with('groups', $groups)
            ->with('callResults', $callResults)
            ->with('typeDate', $typeDate)
            ->with('listMktUser', $listMktUser)
            ->with('listTypeTN', $listTypeTN)
            ->with('listProduct', $listProduct)
            ->with('sales', $sales)->with('saleCare', $saleCare)->with('listCall', $listCall);
    }

    public function add()
    { 
        
        $helper = new Helper();
        $listSale = $helper->getListSale()->get();

        $src = new SrcPage();
        $listSrc = $src::orderBy('id', 'desc')->get();
        // dd($listSrc);
        return view('pages.sale.add')->with('listSale', $listSale)->with('listSrc', $listSrc);
    }

    /**
     * old_customer"
     *  0: khách mới - data nóng
     *  1: khách cũ - cskh
     *  2: sale tự tạo data - hotline
     * 
     */
    public function save(Request $req) 
    {
        $validator      = Validator::make($req->all(), [
            'name'      => 'required',
            'phone'     => 'required',
        ],[
            'name.required' => 'Nhập tên khách hàng',
            'phone.required' => 'Nhập số điện thoại',
        ]);

        if ($validator->passes()) {
            if (isset($req->id)) {
                $saleCare = SaleCare::find($req->id);
                $text = 'Cập nhật tác nghiệp thành công.';
            } else {
                $saleCare = new SaleCare();
                $text = 'Tạo tác nghiệp thành công.';
                $saleCare->type_TN = ($req->type_TN) ? $req->type_TN : 1;
            }

            $saleCare->id_order             = $req->id_order;
            $saleCare->sex                  = ($req->sex) ?: 0;
            $saleCare->full_name            = $req->name;
            $saleCare->phone                = $req->phone;
            $saleCare->address              = $req->address;
            $saleCare->type_tree            = $req->type_tree;
            $saleCare->product_request      = $req->product_request;
            $saleCare->reason_not_buy       = $req->reason_not_buy;
            $saleCare->note_info_customer   = $req->note_info_customer;
            $saleCare->assign_user          = $req->assgin;
            $saleCare->messages             = $req->messages;
            $saleCare->old_customer         = ($req->old_customer) ?: 0;
            $saleCare->m_id                 = $req->m_id;
            $saleCare->is_duplicate         = ($req->is_duplicate) ?: 0;
            $saleCare->is_duplicate         = ($req->is_duplicate) ?: 0;
            $saleCare->group_id             = $req->group_id;
            $saleCare->has_old_order        = ($req->has_old_order) ?: 0;
            
            $srcId = $req->src;
            if ($srcId) {
                $src = Helper::getSrcById($srcId);

                $saleCare->page_name            = $src->name;
                $saleCare->page_id              = $src->id_page;
                $saleCare->page_link            = $src->link;

                //những nguồn chưa chọn nhóm, tất cả đổ tạm thời về vui tricho
                $chatId = env('id_VUI_tricho');
                if ($src->id_page == 'tricho') {
                    $saleCare->group_id            = $src->id_page;
                    $saleCare->save();   
                } 
                
                if ($src->group) {
                    $chatId = $src->group->tele_hot_data;
                    $saleCare->group_id = $src->id_group;
                    $saleCare->save();   
                }
            
            } else {
                $saleCare->page_name            = $req->page_name;
                $saleCare->page_id              = $req->page_id;
                $saleCare->page_link            = $req->page_link;
            }

            $saleCare->save();
            if (!isset($req->id)) {
                $tProduct = Helper::getListProductByOrderId( $saleCare->id_order);
                //gửi thông báo qua telegram
                $telegram = Helper::getConfigTelegram();
                if ($telegram && $telegram->status == 1) {
                    $tokenGroupChat = $telegram->token;

                    $chatId = (!empty($chatId)) ? $chatId : $req->chat_id;

                    if ($req->phone == '0973409613' || $req->phone == '0908361589') {
                        $chatId = '-4286962864'; //auto về nhóm test
                    }

                    $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
                    $client         = new \GuzzleHttp\Client();

                    // $userAssign     = Helper::getUserByID($order->assign_user)->real_name;
                    // $nameUserOrder  = ($order->sex == 0 ? 'anh' : 'chị') ;

                    $notiText       = "Khách hàng: $saleCare->full_name"
                        . "\nSố điện thoại: $saleCare->phone"
                        . "\nNội dung: $saleCare->messages";

                    $name =  $saleCare->user->real_name ?: $saleCare->user->name;
                    if ($saleCare->old_customer == 1) {
                        $notiText .= "Đã nhận được hàng."  . "\nĐơn mua: " . $tProduct; 
                        $notiText .= "\nCSKH nhận data: " . $name;    
                    } else if ($saleCare->old_customer == 0 || $saleCare->old_customer == 2) {

                        $textSrcPage = $req->text;

                        $srcPageId = $req->src;
                        $srcPage = SrcPage::find($srcPageId);
                        if(!$textSrcPage && $srcPage) {
                            $textSrcPage = $srcPage->name;
                        }

                        $notiText .= "\nNguồn data: " . $textSrcPage;
                        $notiText .= "\nSale nhận data: " . $name;
                    }

                    if ($chatId) {
                        $response = $client->request('GET', $endpoint, ['query' => [
                            'chat_id' => $chatId, 
                            'text' => $notiText,
                        ]]);
                    } 
                }
            }
            $routeName = \Request::route();

            // return response()->json(['success'=>$text]);
            // $req->session()->put('success', 'Tạo tác nghiệp sale thành công.');

            if ($routeName && $routeName->getName() == 'sale-care-save') {
                notify()->success($text, 'Thành công!');
            }

        } else {
            notify()->error('Lỗi khi tạo tác nghiệp mới', 'Thất bại!');
            return back()->withErrors($validator->errors());
        }

        return back();
    }

    public function update($id) {
        $saleCare   = SaleCare::find($id);
        $helper     = new Helper();
        $listSale   = $helper->getListSale()->get();

        if($saleCare) {
            return view('pages.sale.add')->with('saleCare', $saleCare)
                ->with('listSale', $listSale);
        } 

        return redirect('/');
    }

    public function saveAjax(Request $req) {
        $saleCare = SaleCare::find($req->itemId);

        if (isset($saleCare->id)) {
            $nextStep = $saleCare->next_step;
            if ($nextStep < 7) {
                if ($nextStep) {
                    $nextStep++;
                } else {
                    $nextStep = 1;
                }
                $saleCare->next_step = $nextStep;
                $saleCare->is_runjob = 0;
            }
            $saleCare->result_call = $req->id;
            $saleCare->save();
            return response()->json(['data' => $saleCare]);
        }

        return response()->json(['error' => true]);
    }

    public function searchInSaleCare($dataFilter)
    {
        $list   = SaleCare::orderBy('id', 'desc')
            ->orWhere('full_name', 'like', '%' . $dataFilter['search'] . '%')
            ->orWhere('phone', 'like', '%' . $dataFilter['search'] . '%');
    
        // dd($list->get());
        $ids = $newList = [];
        foreach ($list->get() as $sc) {
            $ids[] = $sc->id;
            if ($sc->orderNew && $sc->orderNew->phone != $sc->phone) {
                $newList = SaleCare::where('phone', 'like', '%' . $sc->orderNew->phone. '%')
                    ->pluck('id')->toArray();

                foreach ($newList as $item) {
                    // dd($item);
                    if ($item != $sc->id) {
                        $ids[] = $item;
                    }
                }
            }
        }

        $list = SaleCare::whereIn('id', $ids)->orderBy('id', 'desc');

    
        /*if (isset($dataFilter['group'])) {
            if ($dataFilter['group'] == 1) {
                $src = ['389136690940452', '378087158713964', '381180601741468', 'Hotline - Tricho', 'Khách Cũ Tricho'];
                $list = $list->where(function($query) use ($src) {
                    foreach ($src as $term) {
                        if (is_numeric($term)) {
                            $query->orWhere('page_id', 'like', '%' . $term . '%');
                        } else {
                            $query->orWhere('page_link', 'like', '%' . $term . '%');
                        }

                        if (str_contains($term, 'Tricho') || str_contains($term, 'line')) {
                            $query->orWhere('page_name', 'like', '%' . $term . '%');
                        }
                        // $query->orWhere('page_id', 'like', '%' . $term . '%');
                    }
                });
                // dd($list->get());

            } else if ($dataFilter['group'] == 2){
                $src = ['mua4-tang2', '335902056281917', '332556043267807', '318167024711625', '341850232325526', 'ruoc-dong', 'mua4tang2', 'giamgia45'];
                $list = $list->where(function($query) use ($src) {
                    foreach ($src as $term) {
                        if (is_numeric($term)) {
                            $query->orWhere('page_id', 'like', '%' . $term . '%');
                        } else {
                            $query->orWhere('page_link', 'like', '%' . $term . '%');
                        }
                        // $query->orWhere('page_id', 'like', '%' . $term . '%');
                    }
                });

            }
        }
        */

        /** có chọn 1 nguồn */
        if (isset($dataFilter['src'])) {
            /*if (is_numeric($dataFilter['src'])) {
                $list->where('page_id', 'like', '%' . $dataFilter['src'] . '%');
            } else {
                $list->where('page_link', 'like', '%' . $dataFilter['src'] . '%');
            }*/

            $src =SrcPage::find($dataFilter['src']);
            if (!$src) {
                return ;
            }

            if ($src->type == 'pc') {
                $list = $list->where('page_id', $src->id_page);
            } else if ($src->type == 'ladi') {
                $list = $list->where('page_link', $src->link);
            } else if ($src->type == 'hotline') {
                $list = $list->where('page_id', 'like', '%' . $src->id_page .'%');
            } else if  ($src->type == 'old') {
                $list = $list->where('page_name', $src->name);
            } else {
                $list = $list->where('page_id', 'tricho');
            }
        }

        if (isset($dataFilter['type_customer'])) {
            $list->where('old_customer', $dataFilter['type_customer']);   
        }

        $routeName = Route::currentRouteName();
        if (isset($dataFilter['status']) && $routeName != 'filter-total-sales') {
            $list->whereNotNull('id_order_new');
            $newSCare = [];
            foreach ($list->get() as $scare) {
                // dd($scare);
                $order = $scare->orderNew;
                // dd($order->get());
                if ($order && $order->status == $dataFilter['status']) {
                    $newSCare[] = $scare->id;
                }
            }
            // dd($newSCare);
            $list   = SaleCare::orderBy('id', 'desc')->whereIn('id', $newSCare);
        }
        
        if (isset($dataFilter['sale'])) {
            $list = $list->where('assign_user', $dataFilter['sale']);
        }
        return $list;
    }

    public function getListSalesByPermisson($user, $dataFilter = null) 
    {
        $roles  = $user->role;
        $list   = SaleCare::orderBy('id', 'desc');

        if (isset($dataFilter['search'])) {
            return $this->searchInSaleCare($dataFilter);
        } 

        if ($dataFilter) {
            if (isset($dataFilter['typeDate'])) {
              
                /* 
                * 2: ngày sale chốt đơn
                * 1: ngày data về hệ thống
                */
                if ($dataFilter['typeDate'] == 1) {
                    $time       = $dataFilter['daterange'];
                    $timeBegin  = str_replace('/', '-', $time[0]);
                    $timeEnd    = str_replace('/', '-', $time[1]);
                    $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
                    $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

                    $list->whereDate('created_at', '>=', $dateBegin)
                        ->whereDate('created_at', '<=', $dateEnd);
                } else if ($dataFilter['typeDate'] == 2) {

                    $ordersCtl = new OrdersController();
                    $listOrder = $ordersCtl->getListOrderByPermisson(Auth::user(), $dataFilter);

                    $listIdSale = [];
                    foreach ($listOrder->get() as $order) {
                        $listIdSale[] = $order->sale_care;
                    }

                    $list = SaleCare::orderBy('id', 'desc')
                        ->whereIn('id', $listIdSale);
                }
            }

            if (isset($dataFilter['daterange']) && !isset($dataFilter['typeDate'])) {

                $ordersCtl = new OrdersController();
                $tmpDataFilter = $dataFilter;

                $listOrder = $ordersCtl->getListOrderByPermisson(Auth::user(), $tmpDataFilter);

                $listIdSale = [];
                foreach ($listOrder->get() as $order) {
                    $listIdSale[] = $order->sale_care;
                }

                
                $time       = $dataFilter['daterange'];
                $timeBegin  = str_replace('/', '-', $time[0]);
                $timeEnd    = str_replace('/', '-', $time[1]);
                $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
                $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

                $list->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd);

                // id ngày data về hệ thống
                $listIdSale2 = $list->pluck('id')->toArray();
                // gộp mảng và loại bỏ phần tử trùng => sắp xếp
                $listId = array_unique(array_merge($listIdSale, $listIdSale2));
                sort($listId);

                $list = SaleCare::orderBy('id', 'desc')->whereIn('id', $listId);
            }

            /**
             * 1: nhóm Tricho
             * 2: nhóm Lúa
             * */
            /*if (isset($dataFilter['group'])) {
                if ($dataFilter['group'] == 1) {
                    $src = ['389136690940452', '378087158713964', '381180601741468', 'Hotline - Tricho', 'Khách Cũ Tricho'];
                    $list = $list->where(function($query) use ($src) {
                        foreach ($src as $term) {
                            if (is_numeric($term)) {
                                $query->orWhere('page_id', 'like', '%' . $term . '%');
                            } else {
                                $query->orWhere('page_link', 'like', '%' . $term . '%');
                            }

                            if (str_contains($term, 'Tricho') || str_contains($term, 'line')) {
                                $query->orWhere('page_name', 'like', '%' . $term . '%');
                            }
                            // $query->orWhere('page_id', 'like', '%' . $term . '%');
                        }
                    });
                    // dd($list->get());

                } else if ($dataFilter['group'] == 2){
                    $src = ['mua4-tang2', '335902056281917', '332556043267807', '318167024711625', '341850232325526', 'ruoc-dong', 'mua4tang2', 'giamgia45'];
                    $list = $list->where(function($query) use ($src) {
                        foreach ($src as $term) {
                            if (is_numeric($term)) {
                                $query->orWhere('page_id', 'like', '%' . $term . '%');
                            } else {
                                $query->orWhere('page_link', 'like', '%' . $term . '%');
                            }
                            // $query->orWhere('page_id', 'like', '%' . $term . '%');
                        }
                    });

                }
            }
            */

            /** có chọn 1 nguồn */
            if (isset($dataFilter['src'])) {
                /*if (is_numeric($dataFilter['src'])) {
                    $list->where('page_id', 'like', '%' . $dataFilter['src'] . '%');
                } else {
                    $list->where('page_link', 'like', '%' . $dataFilter['src'] . '%');
                }*/

                $src = SrcPage::find($dataFilter['src']);
                if (!$src) {
                    return ;
                }

                if ($src->type == 'pc') {
                    $list = $list->where('page_id', $src->id_page);
                } else if ($src->type == 'ladi') {
                    $list = $list->where('page_link', $src->link);
                } else if ($src->type == 'hotline') {
                    $list = $list->where('page_id', 'like', '%' . $src->id_page .'%');
                } else if  ($src->type == 'old') {
                    $list = $list->where('page_name', $src->name);
                } else {
                    $list = $list->where('page_id', 'tricho');
                }
            }

            if (isset($dataFilter['mkt'])) {
                $listIDSaleCare = $newIdSaleCare = [];
                $listSrcByMkt = SrcPage::orderBy('id', 'desc')->where('user_digital', $dataFilter['mkt']);

                foreach ($listSrcByMkt->get() as $src) {
                    $mktContronler = new MarketingController();
                    $listSC = $mktContronler->getListSaleCareBySrcId($src, $dataFilter);

                    if ($listSC->count() > 0) {
                        $listIDSaleCare[] = $listSC->pluck('id')->toArray();
                    }
                }

                //gộp mảng
                /* array:3 [
                  0 => [
                    0 => 9752
                    1 => 9733
                    2 => 9731
                  ]
                  1 => array:29 [▶]
                  2 => array:5 [▶]
                ]
                */
                if ($listIDSaleCare) {
                    foreach ($listIDSaleCare as $ids) {
                        foreach ($ids as $id)
                        $newIdSaleCare[] = $id;
                    }
                }

                $newIdSaleCare = array_unique($newIdSaleCare);
                $list = SaleCare::orderBy('id', 'desc')->whereIn('id', $newIdSaleCare);
            }

            if (isset($dataFilter['type_customer'])) {

                $route = \Request::route();
                if ($route->getName() == 'sale-index' || $dataFilter['type_customer'] == 1) {
                    $list->where('old_customer', $dataFilter['type_customer']);  
                } else if ($dataFilter['type_customer'] == 0) {
                    $typeCustomerTmp = [0, 2];
                    $list->whereIn('old_customer',  $typeCustomerTmp);  
                }
            }

            if (isset($dataFilter['statusTN'])) {
                $paramFilter = [-1, 'null'];
                if ($dataFilter['statusTN'] == 1) { //chưa tác nghiệp
                    // $list =   $list->whereIn('result_call', [-1, 'null']);
                    $list = $list->where(function($query) use ($paramFilter) {
                        foreach ($paramFilter as $paramFilter) {
                            if ($paramFilter == -1) {
                                $query->orWhere('result_call', -1);
                            } else {
                                $query->orWhereNull('result_call');
                            }
                        }
                    });
                } else {
                    $list = $list->whereNotNull('result_call');
                }
            }

            if (isset($dataFilter['resultTN'])) {
                $idSaleCares = $list->pluck('id')->toArray();
                $listInFilter = SaleCare:: join('call', 'call.id', '=', 'sale_care.result_call')
                    ->whereIn('sale_care.id', $idSaleCares)
                    ->where('call.result_call',$dataFilter['resultTN']);
                    
                $newIdSaleCare = $listInFilter->pluck('sale_care.id')->toArray();
                $list = SaleCare::whereIn('id', $newIdSaleCare);
            }

            $routeName = Route::currentRouteName();
            if (isset($dataFilter['status']) && $routeName != 'filter-total-sales') {
                $list->whereNotNull('id_order_new');
                $newSCare = [];
                foreach ($list->get() as $scare) {
                    $order = $scare->orderNew;
                    if ($order && $order->status == $dataFilter['status']) {
                        $newSCare[] = $scare->id;
                    }
                }

                $list   = SaleCare::orderBy('id', 'desc')->whereIn('id', $newSCare);
            }

            if (isset($dataFilter['cateCall']) ) {
                if ($dataFilter['cateCall'] == 7) {
                    $cancelSaleC = [];

                    foreach ($list->get() as $saleC) {
                        if ($saleC->result_call && $saleC->result_call != -1 && $saleC->call->then_call == $dataFilter['cateCall']) {
                            $cancelSaleC[] = $saleC->id;
                        }
                    }

                    $list   = SaleCare::orderBy('id', 'desc')->whereIn('id', $cancelSaleC);
                } else {
                    $list   = $list->where('type_TN', $dataFilter['cateCall']);
                }
            }

            if (isset($dataFilter['product'])) {
                $ids = [];
                $list->whereNotNull('id_order_new');
                $newSCare = [];
                foreach ($list->get() as $scare) {
                    $order = $scare->orderNew;

                    $products = json_decode($order->id_product);
                    foreach ($products as $product) {
                        if ($product->id == $dataFilter['product']) {
                            $newSCare[] = $scare->id;
                            break;
                        }
                    }
                }

                $list   = SaleCare::orderBy('id', 'desc')->whereIn('id', $newSCare);
            }
        }
            
        $checkAll   = false;
        $listRole   = [];
        $roles      = json_decode($roles);
        
        $routeName = Route::currentRouteName();
        if ($roles) {
            foreach ($roles as $key => $value) {
                /**
                 * value: 4 = lead sale ko áp dụng cho filter/index dashboard
                 */
                if ($value == 1 || ($value == 4 && $routeName != 'filter-total-sales' && $routeName != 'home')) {
                    $checkAll = true;
                    break;
                } else {
                    $listRole[] = $value;
                }
            }
        }

        $isLeadSale = Helper::isLeadSale(Auth::user()->role);
        if ((isset($dataFilter['sale']) && $dataFilter['sale'] != 999) && ($checkAll || $isLeadSale)) {
            /** user đang login = full quyền và đang lọc 1 sale */
            $sale = Helper::getSaleById($dataFilter['sale']);

            // if ($sale->is_CSKH == 1) {
            //     $list = $list->where('old_customer', 1);
            // } else {
            //     $list = $list->where('old_customer', 0);
            // }
            $list = $list->where('assign_user', $dataFilter['sale']);
           
        } else if ((!$checkAll || !$isLeadSale ) && !$user->is_digital) {
            $list = $list->where('assign_user', $user->id);
        }  

        if ($user->is_digital && $user->name == 'digital.tien') {
            $src = ['mua4-tang2', '335902056281917', '389136690940452'];
            $list = $list->where(function($query) use ($src) {
                foreach ($src as $term) {
                    if (is_numeric($term)) {
                        $query->orWhere('page_id', 'like', '%' . $term . '%');
                    } else {
                        $query->orWhere('page_link', 'like', '%' . $term . '%');
                    }
                    // $query->orWhere('page_id', 'like', '%' . $term . '%');
                }
            });
        } else if ($user->is_digital && $user->name == 'digital.di') {
            $src = [ '424411670749761', '398822199987832'];
            $list = $list->where(function($query) use ($src) {
                foreach ($src as $term) {
                    if (is_numeric($term)) {
                        $query->orWhere('page_id', 'like', '%' . $term . '%');
                    } else {
                        $query->orWhere('page_link', 'like', '%' . $term . '%');
                    }
                    // $query->orWhere('page_id', 'like', '%' . $term . '%');
                }
            });
        }

        return $list;
    }

    public function search(Request $req)
    {
        if ($req->search) {
            $helper     = new Helper();
            $sales      = Helper::getListSale()->get();
            $listCall   = $helper->getListCall()->get();
            $saleCare = SaleCare::where('full_name', 'like', '%' . $req->search . '%')
                ->orWhere('phone', 'like', '%' . $req->search . '%')
                ->orderBy('id', 'desc');
            $count      = $saleCare->count();
            $saleCare   = $saleCare->paginate(10);
            return view('pages.sale.index')->with('count', $count)->with('sales', $sales)->with('saleCare', $saleCare)->with('listCall', $listCall);
        } else {
            return redirect()->route('sale-index');
        }
    }

    public function filterSalesByDate(Request $req) 
    {
        // dd($req->all());
        $dataFilter = [];
        if ($req->search) {
            $dataFilter['search'] = $req->search;
        }

        // dd($req->all());
        if ($req->daterange) {
            $time       = $req->daterange;
            $arrTime    = explode("-",$time); 
            $dataFilter['daterange'] = $arrTime;
        }

        $typeDate = $req->typeDate;
        if ($typeDate && $typeDate != 999) {
            $dataFilter['typeDate'] = $typeDate;
        }

        $sale = $req->sale;
        if ($req->sale && $sale != 999) {
            $dataFilter['sale'] = $sale;
        }

        $mkt = $req->mkt;
        if ($req->mkt && $mkt != 999) {
            $dataFilter['mkt'] = $mkt;
        }     

        $src = $req->src;
        if ($req->src && $src != 999) {
            $dataFilter['src'] = $src;
        }

        // dd($dataFilter);
        $group = $req->group;
        if ($req->group && $group != 999) {
            $dataFilter['group'] = $group;
        }

        $typeCustomer = $req->type_customer;
        if ($typeCustomer != 999) {
            $dataFilter['type_customer'] = $typeCustomer;
        }

        $resultTN = $req->resultTN;
        if ($resultTN != 999) {
            $dataFilter['resultTN'] = $resultTN;
        }

        $status = $req->status;
        if ($status && $status != 999 ||  $status == 0) {
            $dataFilter['status'] = $status;
        }

        if ($req->cateCall && $req->cateCall != 999 ) {
            $dataFilter['cateCall'] = $req->cateCall;
        }

        if ($req->statusTN && $req->statusTN != 999) {
            $dataFilter['statusTN'] = $req->statusTN;
        }

        if ($req->product && $req->product != 999) {
            $dataFilter['product'] = $req->product;
        }

        try {
            $data       = $this->getListSalesByPermisson(Auth::user(), $dataFilter);
            $saleCare   = $data->paginate(50);

            $helper     = new Helper();
            $listCall   = $helper->getListCall()->get();
            $sales      = Helper::getListSale()->get();
            $listSrc    = SrcPage::orderBy('id', 'desc')->get();
            $groups     = Group::orderBy('id', 'desc')->get();
            $callResults = CallResult::orderBy('id', 'desc')->get();
            $typeDate = TypeDate::orderBy('id', 'desc')->get();
            $listMktUser = Helper::getListMktUser();
            $listTypeTN = CategoryCall::orderBy('id', 'asc')->get();
            $listProduct = Product::select('product.*')->orderBy('product.id', 'desc')
                ->join('detail_product_group','detail_product_group.id_product', '=', 'product.id')
                ->where('product.status', 1)->distinct()->get();

            return view('pages.sale.index')->with('listSrc', $listSrc)
                ->with('sales', $sales)->with('groups', $groups)
                ->with('callResults', $callResults)
                ->with('typeDate', $typeDate)
                ->with('listMktUser', $listMktUser)
                ->with('listTypeTN', $listTypeTN)
                ->with('listProduct', $listProduct)
                ->with('saleCare', $saleCare)->with('listCall', $listCall);
        } catch (\Exception $e) {
            // return $e;
            // dd($e);
            return redirect()->route('home');
        }
    }

    public function updateTNcan(Request $r) 
    {
        $id = $r->id;
        $saleCare = SaleCare::find($id);
        $history = SaleCareHistoryTN::where('sale_id', $id)
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->first();

        if (!$saleCare) {
            return response()->json(['error'=>'Đã có lỗi xảy ra trong quá trình cập nhật']);
        }
        if (!$history ) {
            $history = new SaleCareHistoryTN();
            $history->sale_id = $id;
        }


        $history->note = $r->textTN;
        $history->save();
        return response()->json([
            'success' => 'Cập nhật TN thành công!',
            // 'id_his' => $history->id,
            // 'text_his' => date_format($history->created_at,"d/m") . ' ' . $history->note,
        ]);
        /*$saleCare = SaleCare::find($r->id);
        // dd($r->textTN);
        if ($saleCare) {
            $saleCare->TN_can = $r->textTN;
            $saleCare->save();
            return response()->json(['success' => 'Cập nhật TN thành công!']);
        }

        return response()->json(['error'=>'Đã có lỗi xảy ra trong quá trình cập nhật']);
        */
    }
    
    public function delete($id)
    {
        $saleCare = SaleCare::find($id);
        if($saleCare){
            $saleCare->delete();
            notify()->success('Xoá data thành công.', 'Thành công!');            
        } else {
            notify()->error('Xoá loại TN thất bại!', 'Thất bại!');
        }
        
        return back();
    }

    public function updateAssignTNSale(Request $r) 
    {
        $saleCare = SaleCare::find($r->id);
        if ($saleCare) {
            $saleCare->assign_user = $r->assignSale;
            $saleCare->save();
            return response()->json(['success' => 'Cập nhật TN thành công!']);
        }

        return response()->json(['error'=>'Đã có lỗi xảy ra trong quá trình cập nhật']);
    }

    public function getIdOrderNewTNSale(Request $r)
    {
        $saleCare = SaleCare::find($r->TNSaleId);
        // dd( $r->all());
        if ($saleCare && $saleCare->id_order_new) {
            $link = route('view-order', $saleCare->id_order_new);
            return response()->json([
                'id_order_new' => $saleCare->id_order_new,
                'link' => $link
            ]);
        }
    }

    public function updateTNresult(Request $r) {
        $saleCare = SaleCare::find($r->id);
        $nextTN = '';

        if ($saleCare) {
            $saleCare->result_call = $r->value;

            if ($r->value == -1) {
                $saleCare->has_TN = 0;
            } else {
                $saleCare->has_TN = 1;
                $nextTN = $saleCare->resultCall->thenCall->name;
            }
            
            $saleCare->is_runjob = 0;
            $saleCare->time_update_TN = date('Y-m-d H:i:s');
            $saleCare->save();
            return response()->json([
                'success' => 'Cập nhật kết quả TN thành công!',
                'classHasTN' => $saleCare->has_TN,
                'nextTN' => $nextTN,
            ]);
        }

        return response()->json(['error' => 'Đã có lỗi xảy ra trong quá trình cập nhật kết quả TN']);
    }
}
