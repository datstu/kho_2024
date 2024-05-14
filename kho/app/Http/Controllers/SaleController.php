<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\SaleCare;
use App\Helpers\Helper;
use PHPUnit\TextUI\Help;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $helper     = new Helper();
        $listCall   = $helper->getListCall()->get();
        $saleCare   = SaleCare::orderBy('id', 'desc')->paginate(50);

        return view('pages.sale.index')->with('saleCare', $saleCare)->with('listCall', $listCall);
    }

    public function add()
    { 
        // notify()->success('Laravel Notify is awesome!');
        // drakify('','fail');
        // notify()->error('Welcome to Laravel Notify ⚡️');
        $helper = new Helper();
        $listSale = $helper->getListSale()->get();
        return view('pages.sale.add')->with('listSale', $listSale);
    }

    public function save(Request $req) {
        // dd($req->all());
        $validator      = Validator::make($req->all(), [
            'name'      => 'required',
            'address'   => 'required',
            'phone'     => 'required',
            'id_order'  => 'numeric',
        ],[
            'name.required' => 'Nhập tên khách hàng',
            'address.required' => 'Nhập địa chỉ',
            'phone.required' => 'Nhập số điện thoại',
            'id_order.numeric' => 'Chỉ được nhập số',
        ]);

        // dd($validator->errors());
        if ($validator->passes()) {
            if (isset($req->id)) {
                $saleCare = SaleCare::find($req->id);
                $text = 'Cập nhật tác nghiệp thành công.';
            } else {
                $saleCare = new SaleCare();
                $text = 'Tạo tác nghiệp thành công.';
            }
            // dd($req->all());
            $saleCare->id_order             = $req->id_order;
            $saleCare->sex                  = $req->sex;
            $saleCare->full_name            = $req->name;
            $saleCare->phone                = $req->phone;
            $saleCare->address              = $req->address;
            $saleCare->type_tree            = $req->type_tree;
            $saleCare->product_request      = $req->product_request;
            $saleCare->reason_not_buy       = $req->reason_not_buy;
            $saleCare->note_info_customer   = $req->note_info_customer;
            $saleCare->assign_user          = $req->assign_sale;
            $saleCare->page_name            = $req->page_name;
            $saleCare->page_id              = $req->page_id;
            // $saleCare->number_of_call       = json_encode($req->call);

            $saleCare->save();
            
            if (!isset($req->id)) {
                $tProduct = Helper::getListProductByOrderId( $saleCare->id_order);
                //gửi thông báo qua telegram
                $telegram = Helper::getConfigTelegram();
                if ($telegram && $telegram->status == 1) {
                    $tokenGroupChat = $telegram->token;
                    $chatId         = $telegram->id_CSKH;
                    $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
                    $client         = new \GuzzleHttp\Client();

                    // $userAssign     = Helper::getUserByID($order->assign_user)->real_name;
                    // $nameUserOrder  = ($order->sex == 0 ? 'anh' : 'chị') ;

                    $notiText       = "Khách hàng: $saleCare->full_name"
                        . "\nSố điện thoại: $saleCare->phone";
                       
                    if ($req->text) {
                        $notiText .= "\n" . $req->text;
                    } else {
                        $notiText .= "\nĐã nhận được hàng."  . "\nĐơn mua: " . $tProduct; 
                    }
                    // dd ($notiText);
                    // . ($req->text) ? $req->text : "\nĐã nhận được hàng."
                  

                   
                    $response = $client->request('GET', $endpoint, ['query' => [
                        'chat_id' => $chatId, 
                        'text' => $notiText,
                    ]]);
                }
            }

            // return response()->json(['success'=>$text]);
            // $req->session()->put('success', 'Tạo tác nghiệp sale thành công.');
            notify()->success($text, 'Thành công!');
           
        } else {
            // dd($validator->errors()->getMessages());
            notify()->error('Lỗi khi tạo tác nghiệp mới', 'Thất bại!');

            // foreach ($validator->errors()->getMessages() as $kE => $err) {
            //     if ($kE == 'id_order') {
            //         $kE = 'Mã đơn hàng';
            //     }
            //     foreach ($err as $k => $val) {
            //         echo $kE . ' ' . $val;

            //     }
            // }
            // echo die();
            // notify()->error('Lỗi khi tạo tác nghiệp mới', 'Thất bại!');
            //  return response()->json(['errors'=>$validator->errors()]);
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
}
