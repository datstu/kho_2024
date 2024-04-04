<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
class AddressController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return Response
    //  */
    // public function index()
    // {
    //     $list = Orders::orderBy('id', 'desc')->paginate(5);
    //     return view('pages.orders.index')->with('list', $list);
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return Response
    //  */
    // public function add()
    // {
    //     $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province";
    //     $response = Http::withHeaders([
    //         'token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897',
    //     ])->post($endpoint);
  
    //     $statusCode = $response->status();
    //     $provinces  = [];
    //     if ( $response->status() == 200) {
    //         $content    = json_decode($response->body());
    //         $provinces  = $content->data;
    //     }

    //     $listProduct =  Product::all();

    //     return view('pages.orders.addOrUpdate')->with('listProduct', $listProduct)
    //         ->with('provinces', $provinces);
    // }

    
     /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWardById(Request $request)
    {
        if(isset($request->id)){
            // print ($request->id);
            $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=" . $request->id;
            $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);
            $wards = [];
            if ($response->status() == 200) {
                $content    = json_decode($response->body());
                $wards  = $content->data;
                return $wards;
            }
        }
    }

     /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDistrictById(Request $request)
    {
        if(isset($request->id)){
            // print ($request->id);
            $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=" . $request->id;
            $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);

            $district = [];
            if ($response->status() == 200) {
                $content   = json_decode($response->body());
                $district  = $content->data;
                return $district;
            }
        }
    }
    

    // /**
    //  * Display a listing of the myformPost.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function save(Request $request)
    // {
    //     $validator      = Validator::make($request->all(), [
    //         'name'      => 'required',
    //         'price'     => 'required',
    //         'qty'       => 'required|numeric|min:1',
    //         'address'   => 'required',
    //         // 'products'  => 'required',
    //         'sex'       => 'required',
    //         'phone'     => 'required',
    //     ],[
    //         'name.required' => 'Nhập tên khách hàng',
    //         'price.required' => 'Nhập tổng tiền',
    //         // 'price.numeric' => 'Chỉ được nhập số',
    //         'qty.required' => 'Nhập số lượng',
    //         // 'qty.numeric' => 'Chỉ được nhập số',
    //         'address.required' => 'Nhập địa chỉ',
    //         // 'products.required' => 'Chọn sản phẩm',
    //         'sex.required' => 'Chọn giới tính',
    //         'phone.required' => 'Nhập số lượng',
    //         'qty.min' => 'Vui lòng chọn sản phẩm',
    //     ]);
       
    //     if ($validator->passes()) {
    //         if(isset($request->id)){
    //             $order = Orders::find($request->id);
    //             $text = 'Cập nhật đơn hàng thành công.';
    //         } else {
    //             $order = new Orders();
    //             $text = 'Tạo đơn hàng thành công.';
    //         }
           
    //         $order->id_product  = $request->products;
    //         $order->phone       = $request->phone;
    //         $order->address     = $request->price;
    //         $order->name        = $request->name;
    //         $order->sex         = $request->sex;
    //         $order->total       = $request->price;
    //         $order->qty         = $request->qty;

            
    //         // echo "<pre>";
    //         // print_r($request->products);
    //         // echo "</pre>";
    //         $order->save();
    //         return response()->json(['success'=>$text]);
    //     }
     
    //     return response()->json(['errors'=>$validator->errors()]);
    // }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewUpdate($id)
    {
        // $product = Product::find($id);
        // $listCategory =  Category::all();
        // if($product){
        //     return view('pages.product.addOrUpdate')->with('product', $product)
        //         ->with('listCategory', $listCategory);
        // } 

        // return redirect('/');
      
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function delete($id)
    {
        // $product = Product::find($id);
        // if($product){
        //     $product->delete();
        //     return redirect('/danh-sach-san-pham')->with('success', 'Sản phẩm đã xoá thành công!');            
        // } 

        // return redirect('/danh-sach-san-pham') ->with('error', 'Đã xảy ra lỗi khi xoá sản phẩm!');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function search(Request $request)
    {
        // $product = Product::where('name', 'like', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate(5);
        // if($product){
        //     return view('pages.product.index')->with('list', $product);           
        // } 

        // return redirect('/');
    }

    public function setProducts(){
        // $list = Product::orderBy('id', 'desc')->paginate(5);

        // return view('pages.product.index')->with('list', $list);
    }

    public function setProductsByMonth(Request $request){
        // $month  = $request->month;
        // $list   = Product::orderBy('id', 'desc')
        //     ->whereMonth('created_at', '=', $month)
        //     ->paginate(5);

        // return view('pages.product.index')->with('list', $list);
    }

    public function setProductsByYear(Request $request){
        // $year  = $request->year;
        // $list   = Product::orderBy('id', 'desc')
        //     ->whereYear('created_at', '=', $year)
        //     ->paginate(5);

        // return view('pages.product.index')->with('list', $list);
    }

    public function getProvinceNameById($id) {
       
    }
    
}
