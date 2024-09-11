<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Http\Controllers\SaleController;
use App\Models\SaleCare;
use PhpParser\Node\Stmt\TryCatch;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $req)
    {
        // dd($req->all());
        if (count($req->all())) {
            return $this->filterOrderByDate($req);
        }
        $category = Category::where('status', 1)->get();
        // $list = $this->getListOrderByPermisson(Auth::user())->paginate(50);

        // dd('hiho');
        $data       = $this->getListOrderByPermisson(Auth::user());
        $sumProduct = $data->sum('qty');
        $totalOrder = $data->count();
        $list       = $data->paginate(50);
        $sales      = Helper::getListSale()->get();

        return view('pages.orders.index')->with('sales', $sales)->with('totalOrder', $totalOrder)->with('sumProduct', $sumProduct)->with('list', $list)->with('category', $category);
    }

    public function  getListOrderByPermisson($user, $dataFilter = null) 
    {
        $roles  = $user->role;
        $list   = Orders::orderBy('id', 'desc');
  
        if ($dataFilter) {
            if (isset($dataFilter['daterange'])) {
                $time       = $dataFilter['daterange'];
                $timeBegin  = str_replace('/', '-', $time[0]);
                $timeEnd    = str_replace('/', '-', $time[1]);

                $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
                $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

                // dd($dataFilter['daterange']);
                // $dateBegin  = $dataFilter['daterange']['dateBegin']; 
                // $dateEnd    = $dataFilter['daterange']['dateEnd']; 
                // dd($dateBegin);
                $list->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd);
            }
            
            if (isset($dataFilter['status'])) {
                $list->whereStatus($dataFilter['status']);
            }

            if (isset($dataFilter['category'])) {
                $ids = [];
                foreach ($list->get() as $order) {
                    $products = json_decode($order->id_product);
                    $isProductOfCategory = Helper::checkProductsOfCategory($products, $dataFilter['category']);
                    if ($isProductOfCategory) {
                        $ids[] = $order->id;
                    }
                }

                $list       = Orders::whereIn('id', $ids)->orderBy('id', 'desc');
            }

            if (isset($dataFilter['product'])) {
                $ids = [];
                
                foreach ($list->get() as $order) {
                    $products = json_decode($order->id_product);
                    foreach ($products as $product) {
                        if ($product->id == $dataFilter['product']) {
                            $ids[] = $order->id;
                            break;
                        }
                    }
                }

                $list = Orders::whereIn('id', $ids)->orderBy('id', 'desc');
            }
              
            /**
             * 1: nhóm Tricho
             * 2: nhóm Lúa
             */
            if (isset($dataFilter['group'])) {
                if ($dataFilter['group'] == 1) {
                    $productTricho = [
                        58 => '1kg humic',
                        57 => 'Xô Tricho 10kg',
                        56 => '	1 xô Tricho + 3kg Humic'
                    ];
                    $ids = [];
                
                    // dd($list->get());
                    foreach ($list->get() as $order) {
                        $products = json_decode($order->id_product);
                        foreach ($products as $product) {
                            if (array_key_exists($product->id, $productTricho)) {
                                $ids[] = $order->id;
                                break;
                            }
                        }
                    }

                } else if ($dataFilter['group'] == 2){
                    $productOg = [
                        55 => 'Xô OG 10kg',
                        54 => 'OG vô gạo',
                        53 => 'OG rước đòng',
                        43 => 'S400'
                    ];
                    $ids = [];
                
                    // dd($list->get());
                    foreach ($list->get() as $order) {
                        $products = json_decode($order->id_product);
                        foreach ($products as $product) {
                            if (array_key_exists($product->id, $productOg)) {
                                $ids[] = $order->id;
                                break;
                            }
                        }
                    }

                }

                $list = Orders::whereIn('id', $ids)->orderBy('id', 'desc');
                // $list->whereStatus($dataFilter['status']);
            }

            /** mrNguyen = 1
             *  mrTien = 2
             *
             * lấy list sđt từ order
             * get sale care ( where phone = sđt và &page_id/link của mkt
             * kqua sđt này bao gồm data thuộc mkt và có đơn theo điều kiên lọc ban đầu của order
             * lấy order từ sđt vừa lọc sale care
             */
            $dataFilterSale = [];
            if (isset($dataFilter['src'])) {
                $dataFilterSale['src'] = $dataFilter['src'];
            }

            if (isset($dataFilter['mkt'])) {
                $dataFilterSale['mkt'] = $dataFilter['mkt'];
            }  

            // dd($dataFilter);
            if (count($dataFilterSale) > 0 ) {
                // dd('hi');
                $phoneFilter = [];
                $listPhoneOrder = $list->pluck('phone')->toArray();
                // dd($listPhoneOrder);
                $flag = false;

                
                // if ($dataFilter['type_customer'] == 1) {
                    // dd($dataFilter['type_customer']);
                    
                foreach ($listPhoneOrder as $phone) {
                    // if ($phone == '0967330586') {
                        // dd($phone);
                            // dd($dataFilterSale);
                        
                        $saleCtl = new SaleController();
                        $listsaleCare = $saleCtl->getListSalesByPermisson(Auth::user(), $dataFilterSale);
                        
                        $cus9phone = $this->getCustomPhone9Num($phone);
                        // dd($listsaleCare->get());
                        $careFromOrderPhone = $listsaleCare->where('phone', 'like', '%' . $cus9phone . '%')
                            ->where('assign_user', '!=', 55)->first();
                        
                        // dd($careFromOrderPhone);
                        // $dataFilter['type_customer'] = 0;
                        // dd( $careFromOrderPhone);

                        if (!isset($dataFilter['type_customer']) ) {
                            $dataFilter['type_customer'] = 999; //lấy tất cả data nóng và CSKH
                        }
                        
                        // dd($dataFilter['type_customer']);

                        $flag = Helper::checkTypeOrderbyPhone($cus9phone, $dataFilter['type_customer']);

                        if ($careFromOrderPhone && $flag) {
                            $phoneFilter[] = $phone;
                        }
                    // }
                }

                // dd($phoneFilter);
                $list = Orders::whereIn('phone', $phoneFilter)->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd)->orderBy('id', 'desc');  
                   
            } 
        } 
        // dd($list->get());
        
        if ($user->is_digital == 1){
            // $today  = date("Y-m-d", time());
            // $dateBegin  = date('Y-m-d',strtotime("$today"));
            // $dateEnd    = date('Y-m-d',strtotime("$today"));
            // $list->whereDate('created_at', '>=', $dateBegin)
            //     ->whereDate('created_at', '<=', $dateEnd);

            // $phoneFilter = [];
            // $listPhoneOrder = $list->pluck('phone')->toArray();
            // dd($listPhoneOrder);
            // $flag = false;
            // $dataFilterSale['mkt'] = 2;
           
            // foreach ($listPhoneOrder as $phone) {
            //     $saleCtl = new SaleController();
            //     $listsaleCare = $saleCtl->getListSalesByPermisson(Auth::user(), $dataFilterSale);
            //     $cus9phone = $this->getCustomPhone9Num($phone);
            //     $careFromOrderPhone = $listsaleCare->where('phone', 'like', '%' . $cus9phone . '%')->first();
            
            //     if (!isset($dataFilter['type_customer']) ) {
            //         $dataFilter['type_customer'] = 999; //lấy tất cả data nóng và CSKH
            //     }
                
            //     $flag = Helper::checkTypeOrderbyPhone($cus9phone, $dataFilter['type_customer']);
            //     if ($careFromOrderPhone && $flag) {
            //         $phoneFilter[] = $phone;
            //     }
            // }

            // $list = Orders::whereIn('phone', $phoneFilter)->whereDate('created_at', '>=', $dateBegin)
            //     ->whereDate('created_at', '<=', $dateEnd)->orderBy('id', 'desc');  
        }

        $checkAll   = false;
        $listRole   = [];
        $roles      = json_decode($roles);
        if ($roles) {
            foreach ($roles as $key => $value) {
                if ($value == 1 || $value == 4) {
                    $checkAll = true;
                    break;
                } else {
                    $listRole[] = $value;
                }
            }
        }

        
        $isLeadSale = Helper::isLeadSale(Auth::user()->role);
        $routeName = \Request::route();

        
        if ((isset($dataFilter['sale']) && $dataFilter['sale'] != 999) && ($checkAll || $isLeadSale)) {
            /** user đang login = full quyền và đang lọc 1 sale */
            $list = $list->where('assign_user', $dataFilter['sale']);
        } else if ($user->is_digital == 1 && $routeName->getName() == 'order' && $user->name == 'digital.tien') {
            if (!$dataFilter) {
                $today  = date("Y-m-d", time());
                $dateBegin  = date('Y-m-d',strtotime("$today"));
                $dateEnd    = date('Y-m-d',strtotime("$today"));
                $list->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd);
            }
            
            $phoneFilter = [];
            $listPhoneOrder = $list->pluck('phone')->toArray();
            $dataFilterSale['mkt'] = 2; //aT
            foreach ($listPhoneOrder as $phone) {
                $saleCtl = new SaleController();
                $listsaleCare = $saleCtl->getListSalesByPermisson(Auth::user(), $dataFilterSale);
                $careFromOrderPhone = $listsaleCare->where('phone', 'like', '%' . $phone . '%')->first();

                if ($careFromOrderPhone) {
                    $phoneFilter[] = $phone;
                } 
            }

            $list = Orders::whereIn('phone', $phoneFilter)->orderBy('id', 'desc');
        } else if ($user->is_digital == 1 && $routeName->getName() == 'order' && $user->name == 'digital.di') {
            if (!$dataFilter) {
                $today  = date("Y-m-d", time());
                $dateBegin  = date('Y-m-d',strtotime("$today"));
                $dateEnd    = date('Y-m-d',strtotime("$today"));
                $list->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd);
            }
            
            $phoneFilter = [];
            $listPhoneOrder = $list->pluck('phone')->toArray();
            $dataFilterSale['mkt'] = 3; //aT
            foreach ($listPhoneOrder as $phone) {
                $saleCtl = new SaleController();
                $listsaleCare = $saleCtl->getListSalesByPermisson(Auth::user(), $dataFilterSale);
                $careFromOrderPhone = $listsaleCare->where('phone', 'like', '%' . $phone . '%')->first();

                if ($careFromOrderPhone) {
                    $phoneFilter[] = $phone;
                } 
            }

            $list = Orders::whereIn('phone', $phoneFilter)->orderBy('id', 'desc');
        } else if ((!$checkAll || !$isLeadSale) && !$user->is_digital) {
            $list = $list->where('assign_user', $user->id);
        }

        // dd($list->get());
        /**ẩn thông tin tricho đối với leadsale lúa */
        // if ( Helper::isLeadSale(Auth::user()->role) && !isFullAccess(Auth::user()->role)) {
        //     $newOrderId = [];
        //     // dd($list->get());
        //     foreach ($list->get() as $k => $order) {
        //         if (empty($order->salecare->group_id) ||  $order->salecare->group_id != 'tricho') {
        //             $newOrderId[] = $order->id;
        //         }
        //     } 

        //     $list = Orders::whereIn('id', $newOrderId)->orderBy('id', 'desc');
        // }

        // dd($list->sum('total'));
        return $list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function add()
    { 

        $saleCareId = request()->get('saleCareId');
        $listProduct = $listSale = [];

        $saleCare = SaleCare::find($saleCareId);
        if ($saleCare) {
            if ($group = $saleCare->group) {
                $sales     = $group->sales;
                $products    = $group->products;

                foreach ($products as $item) {
                    $listProduct[] = $item->product;
                }

                foreach ($sales as $item) {
                    $listSale[] = $item->user;
                }
            } else {
                //data TN cũ chưa có group => hiển thị toàn bộ list ban đầu
                $listProduct    = Helper::getListProductByPermisson(Auth::user()->role)->get();
                $listSale       = $this->getListSale()->get();
            }
    
        }

        return view('pages.orders.addOrUpdate')->with('listProduct', $listProduct)
            // ->with('provinces', $provinces)
            ->with('saleCareId', $saleCareId)
            ->with('listSale', $listSale);
    }

    public function getListProductByPermisson($roles) {
        $list       = Product::orderBy('id', 'desc')->where('status', '=', 1);
        $checkAll   = false;
        $listRole   = [];
        $roles      = json_decode($roles);
        if ($roles) {
            foreach ($roles as $key => $value) {
                if ($value == 1) {
                    $checkAll = true;
                    break;
                } else {
                    $listRole[] = $value;
                }
            }
        }

        if (!$checkAll) {
            $list = $list->where('roles', $listRole);
        }

        return $list;
    }

    public function getProvince(){
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province";
        $response = Http::withHeaders([
            'token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897',
        ])->post($endpoint);
  
        $provinces  = [];
        if ( $response->status() == 200) {
            $content    = json_decode($response->body());
            $provinces  = $content->data;
        }

        return $provinces;
    }
    
     /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWardById(Request $request)
    {
        if(isset($request->id)){
            print ($request->id);
        }
    }

    /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $validator      = Validator::make($request->all(), [
            'name'      => 'required',
            'price'     => 'required',
            'qty'       => 'required|numeric|min:1',
            'address'   => 'required',
            'sex'       => 'required',
            'phone'     => 'required',
        ],[
            'name.required' => 'Nhập tên khách hàng',
            'price.required' => 'Nhập tổng tiền',
            'qty.required' => 'Nhập số lượng',
            'address.required' => 'Nhập địa chỉ',
            'sex.required' => 'Chọn giới tính',
            'phone.required' => 'Nhập số lượng',
            'qty.min' => 'Vui lòng chọn sản phẩm',
        ]);

        if ($validator->passes()) {
            if (isset($request->id)) {
                $order = Orders::find($request->id);
                $text = 'Cập nhật đơn hàng thành công.';
                
                $oldPro = json_decode($order->id_product);
                $newPro = json_decode($request->products);

                foreach ($oldPro as $oldItem) {
                    $flag = false;
                    foreach ($newPro as $key => $newItem) {
                        if ($newItem->id == $oldItem->id) {
                            $flag = true;
                            unset($newPro[$key]);
                            break;
                        }
                    }
                    
                    if ($flag) {
                        $oldItem->val = (int)$newItem->val - (int)$oldItem->val;
                    } else {
                        $oldItem->val = -(int)$oldItem->val;
                    }
                }
              
                /** cập nhật số lượng khi old nhiều hơn new */
                foreach ($oldPro as $item) {
                    $product        = Product::find($item->id);
                    $product->qty   = (int)$product->qty - (int)$item->val;
                    $product->save();
                }

                /** cập nhật số lượng khi new nhiều hơn old: new đã trừ, còn lại chưa update  */
                foreach ($newPro as $item) {
                    $product        = Product::find($item->id);
                    $product->qty   = (int)$product->qty - (int)$item->val;
                    $product->save();
                }

            } else {
                $order = new Orders();
                $text = 'đã tạo đơn hàng.';

                $listProductName = $tProduct = '';
                foreach (json_decode($request->products) as $item) {
                    if ($tProduct != '') {
                        $tProduct .= ', ';
                    }
                    $product        = Product::find($item->id);
                    $tProduct       .= "\n$product->name: $item->val";
                    $product->qty   = (int)$product->qty - (int)$item->val;
    
                    if ($listProductName != "") {
                        $listProductName    .= ' + ';
                    }
                    $listProductName    .= $product->name;
                    $product->save();
                }
            }

            $order->id_product      = $request->products;
            $order->phone           = $request->phone;
            $order->address         = $request->address;
            $order->name            = $request->name;
            $order->sex             = $request->sex;
            $order->total           = $request->price;
            $order->province        = $request->province;
            $order->district        = $request->district;
            $order->ward            = $request->ward;
            $order->qty             = $request->qty;
            $order->assign_user     = $request->assignSale;
            $order->is_price_sale   = $request->isPriceSale;
            $order->note            = $request->note;
            $order->status          = $request->status;
            $order->sale_care       = $request->saleCareId;

            $order->save();

            if (!isset($request->id)) {
                /**cập nhật mã đơn hàng được tạo vào record sale_care
                 * workflow hiện tại đơn tạo từ TN Sale => luôn tồn tại saleCare
                 * 
                 */
                $chatId = '-4286962864'; //khởi tạo nhóm Test
                $tokenGroupChat = '';
                $saleCare = SaleCare::find($order->sale_care);
                if ($saleCare) {
                    $saleCare->id_order_new = $order->id;
                    $saleCare->save();
                    $group = $saleCare->group;
                    if ($group) {
                        // dd($saleCare);
                        /** ko xoá group đã có saleCare => luôn tồn tại group */
                        $chatId = $group->tele_create_order;
                        $tokenGroupChat = $group->tele_bot_token;
                    } else {
                        $tokenGroupChat = '7127456973:AAGyw4O4p3B4Xe2YLFMHqPuthQRdexkEmeo';
                        $chatId = '-4167465219';
                    }
                }

                //gửi thông báo qua telegram
                if ($chatId != '' && $tokenGroupChat != '') {
                    $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
                    $client         = new \GuzzleHttp\Client();

                    $userAssign     = Helper::getUserByID($order->assign_user)->real_name;
                    $nameUserOrder  = ($order->sex == 0 ? 'anh' : 'chị');
                    $notiText       = "\nĐơn mua: $order->qty sản phẩm: $tProduct \nTổng: " . number_format($order->total) . "đ miễn phí Ship."
                        . "\nGửi về địa chỉ: $nameUserOrder $order->name - $order->phone - $order->address";
                    
                    if ($order->note) {
                        $notiText . "\nLưu ý: $order->note";
                    }

                    //tạo mới order
                    try {
                        $client->request('GET', $endpoint, ['query' => [
                            'chat_id' => $chatId, 
                            'text' => $userAssign . ' ' . $text . $notiText,
                        ]]);
                    } catch (\Exception $e) {
                        return $e;
                    }
                }
                
            } else {
                //câp nhật order
                //chỉ áp dụng cho đơn phân bón
                $isFertilizer = Helper::checkFertilizer($order->id_product);

                //check đơn này đã có data chưa
                $issetOrder = Helper::checkOrderSaleCare($order->id);
                // status = 'hoàn tất', tạo data tác nghiệp sale

                if ($order->status == 3 && $isFertilizer && !$issetOrder) {

                    $pageName = $order->saleCare->page_name;
                    $pageId = $order->saleCare->page_id;
                    $pageLink = $order->saleCare->page_link;

                    $group = $order->saleCare->group;
                    $groupId = $group->id;
                    $chatId = $group->tele_cskh_data;

                    if ($group->is_share_data_cskh) {
                        $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
                        // dd( $assgin_user);
                    } else {
                        $assgin_user = $order->saleCare->assign_user;
                    }

                    $sale = new SaleController();
                    $data = [
                        'id_order' => $order->id,
                        'sex' => $order->sex,
                        'name' => $order->name,
                        'phone' => $order->phone,
                        'address' => $order->address,
                        'assgin' => $assgin_user,
                        'page_name' => $pageName,
                        'page_id' => $pageId,
                        'page_link' => $pageLink,
                        'group_id' => $groupId,
                        'chat_id' => $chatId,
                        'type_TN' => 8, 
                        'old_customer' => 1
                    ];

                    $request = new \Illuminate\Http\Request();
                    $request->replace($data);

                    $sale->save($request);
                }
            }

            $link = route('update-order', $order->id);
            return response()->json([
                'success' => $text,
                'link' => $link,
        ]);
        }
     
        return response()->json(['errors'=>$validator->errors()]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewUpdate($id)
    {
        $order          = Orders::find($id);
        if($order){
            $listProduct    =  Product::all();
            $listSale       = $this->getListSale()->get();

            return view('pages.orders.addOrUpdate')->with('order', $order)
                ->with('listSale', $listSale)
                ->with('listProduct', $listProduct);
        } 

        return redirect('/');
    }

    public function getListDistrictByProvinceId($id) {
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=" . $id;
        $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);

        $district = [];
        if ($response->status() == 200) {
            $content   = json_decode($response->body());
            $district  = $content->data;
            return $district;
        }
    }

    public function getListWardByDistrictId($id) {
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=" . $id;
        $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);
        $wards = [];
        if ($response->status() == 200) {
            $content    = json_decode($response->body());
            $wards  = $content->data;
            return $wards;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function delete($id)
    {
        $order = Orders::find($id);
        if($order){
            $order->delete();
            return redirect('/don-hang')->with('success', 'Đơn hàng đã xoá thành công!');            
        } 

        return redirect('/don-hang') ->with('error', 'Đã xảy ra lỗi khi xoá đơn hàng!');
    }

    
      /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function search(Request $request)
    {
        $orders = Orders::select('orders.*')
            ->where('orders.name', 'like', '%' . $request->search . '%')
            ->orWhere('orders.phone', 'like', '%' . $request->search . '%')
            ->orderBy('orders.id', 'desc');

        if ($orders->count() == 0) {
            $orders = Orders::select('orders.*')->join('shipping_order', 'shipping_order.order_id','=', 'orders.id')
            ->where('shipping_order.order_code', 'like', '%' . $request->search . '%')
            ->orderBy('orders.id', 'desc');
        }

        if ($orders) {
            $totalOrder = $orders->count();
            $list       = $orders->paginate(50);
            $sumProduct = $orders->sum('qty');
            return view('pages.orders.index')->with('list', $list)->with('search', $request->search)
                ->with('totalOrder', $totalOrder)->with('sumProduct', $sumProduct);           
        } 

        return redirect('/');
    }

    public function getListSale() {
        return User::where('status', 1)->where('is_sale', 1)
            ->orWhere('is_cskh', 1);
    }

    public function createShipping($id) {
        return view('pages.orders.shipping'); 
    }

    public function view($id) {
        $order = Orders::find($id);
        if($order){
            return view('pages.orders.detail')->with('order', $order); 
        } 
        return redirect('/don-hang') ->with('error', 'Đã xảy ra lỗi hoặc đơn hàng không tồn tại!');
    }

    public function filterOrderByDate(Request $req) {
        $dataFilter = [];

        // dd($req->daterange);
        if ($req->daterange) {
            $time       = $req->daterange;
            $arrTime    = explode("-",$time); 
            $dataFilter['daterange'] = $arrTime;
        }
        
        // $dataFilter['status']       = 1; //chưa giao vận
        if ($req->status != 999) {
            $dataFilter['status'] = $req->status;
        }

        $category = $req->category;
        if ($category != 999) {
            $dataFilter['category'] = $category;
        }

        $product = $req->product;
        if ($product != 999) {
            $dataFilter['product'] = $product;
        }

        $sale = $req->sale;
        if ($sale != 999) {
            $dataFilter['sale'] = $sale;
        } 

        $src = $req->src;
        if ($src != 999) {
            $dataFilter['src'] = $src;
        } 

        $mkt = $req->mkt;
        if ($mkt != 999) {
            $dataFilter['mkt'] = $mkt;
        }

        // $typeCustomer = $req->type_customer;
        // if (!$typeCustomer) {
        //     $dataFilter['type_customer'] = 1;
        // }

        try {
            $data       = $this->getListOrderByPermisson(Auth::user(), $dataFilter);
            $totalOrder = $data->count();
          
            $sumProduct = $data->sum('qty');
            // dd($sumProduct);
            $category   = Category::where('status', 1)->get();
            $list       = $data->paginate(50);
            $sales      = Helper::getListSale()->get();

            return view('pages.orders.index')->with('list', $list)->with('category', $category)
                ->with('sumProduct', $sumProduct)->with('sales', $sales)->with('totalOrder', $totalOrder);
        } catch (\Exception $e) {
            // return $e;
            dd($e);
            return redirect()->route('home');
        }
    }

    /**
     * input:
     *  +84973409613
     *  84973409613
     *  0973409613
     *  973409613
     * 
     * output: 973409613
     */
    public function getCustomPhone9Num($phone)
    {
        $length = strlen($phone);
        $pos = $length - 9;
        return substr($phone, $pos);
    }
}