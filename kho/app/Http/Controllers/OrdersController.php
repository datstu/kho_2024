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

class OrdersController extends Controller
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
        // dd($roles);
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
        // dd($roles);
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
            // dd($request->products);
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
            
            
        //    dd($order);
            $order->save();
            // dd(json_decode($order->id_product));
            foreach (json_decode($order->id_product) as $item) {
                $product = Product::find($item->id);
                // dd($item->id);
                $product->qty = $product->qty - $item->val;
                $product->save();
            }
            // dd($order->get());
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
       
        $orders = Orders::select('orders.*')->join('shipping_order', 'shipping_order.order_id','=', 'orders.id')
            ->where('orders.name', 'like', '%' . $request->search . '%')
            ->orWhere('orders.phone', 'like', '%' . $request->search . '%')
            ->orWhere('shipping_order.order_code', 'like', '%' . $request->search . '%')
            ->orderBy('orders.id', 'desc')->paginate(10);

        if($orders){
            return view('pages.orders.index')->with('list', $orders)->with('search', $request->search);           
        } 

        return redirect('/');
    }

    public function getListSale() {
        return User::where('status', 1)->where('is_sale', 1);
    }

    public function createShipping($id) {
        return view('pages.orders.shipping'); 
    }
}
