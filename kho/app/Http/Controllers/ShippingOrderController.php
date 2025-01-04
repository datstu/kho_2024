<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ShippingOrder;
use App\Helpers\Helper;

class ShippingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $list = $this->getListOrderByPermisson(Auth::user())->paginate(15);
        return view('pages.orders.index')->with('list', $list);
    }

    public function getListOrderByPermisson($user) {
        $roles      = $user->role;
        $list       = Orders::orderBy('id', 'desc');
        $checkAll   = false;
        $listRole   = [];
        // $roles      = json_decode(Auth::user()->role);
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
       // $listProduct    =  Product::all()->where('qty', '>', 0)->where('status', '=', 1);
        $listProduct    = $this->getListProductByPermisson(Auth::user()->role)->get();
        $listSale       = $this->getListSale()->get();

        return view('pages.orders.addOrUpdate')->with('listProduct', $listProduct)
            ->with('provinces', $provinces)->with('listSale', $listSale);
    }

    public function getListProductByPermisson($roles) {
        $list       = Product::orderBy('id', 'desc')->where('status', '=', 1);

        
        $checkAll   = false;
        $listRole   = [];
        // $roles      = json_decode(Auth::user()->role);
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
            // 'products'  => 'required',
            'sex'       => 'required',
            'phone'     => 'required',
        ],[
            'name.required' => 'Nhập tên khách hàng',
            'price.required' => 'Nhập tổng tiền',
            // 'price.numeric' => 'Chỉ được nhập số',
            'qty.required' => 'Nhập số lượng',
            // 'qty.numeric' => 'Chỉ được nhập số',
            'address.required' => 'Nhập địa chỉ',
            // 'products.required' => 'Chọn sản phẩm',
            'sex.required' => 'Chọn giới tính',
            'phone.required' => 'Nhập số lượng',
            'qty.min' => 'Vui lòng chọn sản phẩm',
        ]);
       
        if ($validator->passes()) {
            if(isset($request->id)){
                $order = Orders::find($request->id);
                $text = 'Cập nhật đơn hàng thành công.';
            } else {
                $order = new Orders();
                $text = 'Tạo đơn hàng thành công.';
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

            foreach (json_decode($order->id_product) as $item) {
                $product = Product::find($item->id);
                $product->qty = $product->qty - $item->val;
                $product->save();
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
            // $provinces      = $this->getProvince();
            $listProduct    =  Product::all();
            // $listDistrict   =  $this->getListDistrictByProvinceId($order->province);
            // $listWard       =  $this->getListWardByDistrictId($order->district);
            $listSale       = $this->getListSale()->get();

            return view('pages.orders.addOrUpdate')->with('order', $order)
                ->with('listSale', $listSale)
                // ->with('provinces', $provinces)
                // ->with('listDistrict', $listDistrict)
                // ->with('listWard', $listWard)
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
        // $list = $this->getListOrderByPermisson(Auth::user());
       
        $orders = Orders::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(10);

        if($orders){
            return view('pages.orders.index')->with('list', $orders);           
        } 

        return redirect('/');
    }

    public function getListSale() {
        return User::where('status', 1)->where('is_sale', 1);
    }

    public function createShipping($id) {
        $orderId = Orders::find($id);
        if ($orderId) {
            return view('pages.orders.shipping')->with('orderId', $id); 
        } 
        
        return redirect()->route('home');
    }

    public function createShippingHas(Request $req) 
    {
        $orderCode = trim($req->id_shipping_has);
        $ship = ShippingOrder::whereOrderCode($orderCode)->whereOrderId($req->orderId)
            ->first();

        if (!$ship) {
            $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail?order_code=" . $orderCode;
            $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])->get($endpoint);
           
            if ($response->status() == 200) {
                $shippingNew = new ShippingOrder();
                $shippingNew->order_code = $orderCode;
                $shippingNew->order_id = $req->order_id;
                $shippingNew->vendor_ship = $req->vendor_ship;
                $shippingNew->save();
                return redirect()->route('order')->with('success', 'Thêm vận đơn thành công!');
            }
        }

        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xoá sản phẩm!');;
    }

    public function getShippingLog($endpoint) {
        $response   = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])->get($endpoint);
        $data       = [];

        if ($response->status() == 200) {
            $content    = json_decode($response->body());
            $data       = $content->data;
        }

        return $data;
    }

    public function detailShippingOrder($id) {
        
        $ship = ShippingOrder::find($id);
        // $view =  view('pages.orders.detailshipping')->with('type', $ship->vendor_ship);
        $orderLog = $orderInfo = $trackingLog = $callLog = [];
        if(!$ship) {
            return redirect()->back();
        }
       
        $orderCode  = $ship->order_code;

        // $endpointTracking   = "https://fe-online-gateway.ghn.vn/order-tracking/public-api/client/tracking-logs?order_code=" . $orderCode;
        // $endpointCall       = "https://fe-online-gateway.ghn.vn/order-tracking/public-api/client/call-logs?order_code=" . $orderCode;
        // $orderLog           = $this->getShippingLog($endpointTracking);
        // $callLogs           = $this->getShippingLog($endpointCall);
        // $orderInfo          = $orderLog->order_info;
        // $trackingLogs       = $orderLog->tracking_logs;
        return view('pages.orders.detailshipping')->with('orderCode' , $orderCode);
        // if ($trackingLogs) {
        //     $str = '';

        //     $reversedKeys = array_reverse(array_keys($trackingLogs));
        //     $date = $trackingLogs[$reversedKeys[0]]->action_at;
        //     $dateToTime = strtotime($date);
        //     $dateNewFormat = date('d', $dateToTime);
        //     // dd ($dateNewFormat);
        //     $str .= "<div class=\"table-row first\">"
        //     . "<div class=\"table-col block-center-between\" aria-controls=\"collapse-text0\" aria-expanded=\"true\"><span>" . Helper::getDaysOfWek($date) . ', Ngày' . Helper::getDateFromStringGHN($date). "</span></div>"
        //     . "<div class=\"table-col mobile-hidden\">Chi tiết</div>"
        //     . "<div class=\"table-col mobile-hidden\">Thời gian</div>"
        //     . "</div>";
        //     foreach ($reversedKeys as $key) {
        //         $dateLogToTime = strtotime($trackingLogs[$key]->action_at);
        //         $dateLogNewFormat = date('d', $dateLogToTime);

        //         // dd( $dateLogNewFormat);
        //         if ($dateNewFormat !=  $dateLogNewFormat) {
        //             $str .= "<div class=\"table-row first\">"
        //                 . "<div class=\"table-col block-center-between\" aria-controls=\"collapse-text0\" aria-expanded=\"true\"><span>" 
        //                 . Helper::getDaysOfWek($trackingLogs[$key]->action_at) . ', Ngày ' . Helper::getDateFromStringGHN($trackingLogs[$key]->action_at). "</span></div>"
        //                 . "<div class=\"table-col mobile-hidden\">Chi tiết</div>"
        //                 . "<div class=\"table-col mobile-hidden\">Thời gian</div>"
        //                 . "</div>";

        //             $dateNewFormat = $dateLogNewFormat;
        //         } else {
        //             $atTime = date('H:s', strtotime($trackingLogs[$key]->action_at));
        //             $str .= "<div id=\"collapse-text0\" class=\"collapse show\">"
        //                 . "<div class=\"table-log-item\">"
        //                 . "   <div class=\"table-row block-align-top\">"
        //                 . "        <div class=\"table-col \">". $trackingLogs[$key]->status_name ."</div>"
        //                 . "        <div class=\"table-col\">"
        //                 . "            <div>". $trackingLogs[$key]->location->address ."</div>"
        //                 . "        </div>"
        //                 . "        <div class=\"table-col\">". $atTime ."</div>"
        //                 . "    </div>"
                        
        //                 . "</div>"
        //                 . "</div>";
        //         }
        //     }
        //     // dd($str);
           
        //     // return view('pages.orders.detailshipping')->with('data', $data)->with('type', $ship->vendor_ship)->with('strLogs', $str); 
        //     return $view->with('strLogs', $str);
        // }

        return redirect()->route('home');
    }
        
}
