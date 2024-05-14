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

        $data       = $this->getListOrderByPermisson(Auth::user());
        $sumProduct = $data->sum('qty');
        $totalOrder = $data->count();
        // dd($totalOrder);
        $list       = $data->paginate(50);
       

        return view('pages.orders.index')->with('totalOrder', $totalOrder)->with('sumProduct', $sumProduct)->with('list', $list)->with('category', $category);
    }

    public function getListOrderByPermisson($user, $dataFilter = null) {
        $roles      = $user->role;
        $list       = Orders::orderBy('id', 'desc');

        if ($dataFilter) {
            $time       = $dataFilter['daterange'];
            $timeBegin  = str_replace('/', '-', $time[0]);
            $timeEnd    = str_replace('/', '-', $time[1]);

            $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
            $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

            $list->whereDate('created_at', '>=', $dateBegin)
                ->whereDate('created_at', '<=', $dateEnd);
            
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

                $list       = Orders::whereIn('id', $ids)->orderBy('id', 'desc');
            }
        }

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
            $list = $list->where('assign_user', $user->id);
        }

        return $list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function add()
    { 
        $provinces      = $this->getProvince();
        $listProduct    = Helper::getListProductByPermisson(Auth::user()->role)->get();
        $listSale       = $this->getListSale()->get();

        return view('pages.orders.addOrUpdate')->with('listProduct', $listProduct)
            ->with('provinces', $provinces)->with('listSale', $listSale);
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

            $order->save();
            if (!isset($request->id)) {
                //gửi thông báo qua telegram
                $telegram = Helper::getConfigTelegram();
                if ($telegram && $telegram->status == 1) {
                    $tokenGroupChat = $telegram->token;
                    $chatId         = $telegram->id_NVTR;
                    $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
                    $client         = new \GuzzleHttp\Client();

                    $userAssign     = Helper::getUserByID($order->assign_user)->real_name;
                    $nameUserOrder  = ($order->sex == 0 ? 'anh' : 'chị') ;

                    $notiText       = "\nĐơn mua: $order->qty sản phẩm: $tProduct \nTổng: " . number_format($order->total) . "đ miễn phí Ship."
                        . "\nGửi về địa chỉ: $nameUserOrder $order->name - $order->phone - $order->address";
                    
                    if ($order->note) {
                        $notiText . "\nLưu ý: $order->note";
                    }

                    //tạo mới order
                    $response = $client->request('GET', $endpoint, ['query' => [
                        'chat_id' => $chatId, 
                        'text' => $userAssign . ' ' . $text . $notiText,
                    ]]);
                }
                
            } else {
                //câp nhật order
                //chỉ áp dụng cho đơn phân bón
                $isFertilizer = Helper::checkFertilizer($order->id_product);

                //check đơn này đã có data chưa
                $issetOrder = Helper::checkOrderSaleCare($order->id);
                // status = 'hoàn tất', tạo data tác nghiệp sale
                // dd($issetOrder);
                if ($order->status == 3 && $isFertilizer && !$issetOrder) {
                    $sale = new SaleController();
                    $data = [
                        'id_order' => $order->id,
                        'sex' => $order->sex,
                        'name' => $order->name,
                        'phone' => $order->phone,
                        'address' => $order->address,
                        'assign_user' => $order->assign_user,
                    ];
                    $request = new \Illuminate\Http\Request();
                    $request->replace($data);

                    $sale->save($request);
                }
                // die();
            }

            return response()->json(['success'=>$text]);
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
        return User::where('status', 1)->where('is_sale', 1);
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
        $time       = $req->daterange;
        $arrTime    = explode("-",$time); 

        $dataFilter['daterange']    = $arrTime;
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

        try {
            $data       = $this->getListOrderByPermisson(Auth::user(), $dataFilter);
            $totalOrder = $data->count();
            $list       = $data->paginate(50);
            $sumProduct = $data->sum('qty');
            // dd($sumProduct);
            $category   = Category::where('status', 1)->get();
            return view('pages.orders.index')->with('list', $list)->with('category', $category)
                ->with('sumProduct', $sumProduct)->with('totalOrder', $totalOrder);
        } catch (\Exception $e) {
            return redirect()->route('home');
        }
        
    }
}