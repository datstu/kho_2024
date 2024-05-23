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
    public function index(Request $r)
    {
        $helper     = new Helper();
        $listCall   = $helper->getListCall()->get();
        $sales      = User::where('status', 1)->where('is_sale', 1)->get();
        $saleCare   = $this->getListSalesByPermisson(Auth::user())->paginate(50);
        $count      = $this->getListSalesByPermisson(Auth::user())->count();
        if ($r->sale) {
            // dd( $r->sale);
            $saleCare = SaleCare::orderBy('id', 'desc');
            if ($r->sale != 999) {
                $saleCare = SaleCare::where('assign_user', $r->sale)->orderBy('id', 'desc');
            }
            // $dataFilter['daterange'] = $arrTime;
            if ($r->daterange) {
                $time       = $r->daterange;
                $arrTime    = explode("-",$time); 
                $time       = $arrTime;
                $timeBegin  = str_replace('/', '-', $time[0]);
                $timeEnd    = str_replace('/', '-', $time[1]);

                $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
                $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

                // dd($dataFilter['daterange']);
                // $dateBegin  = $dataFilter['daterange']['dateBegin']; 
                // $dateEnd    = $dataFilter['daterange']['dateEnd']; 

                $saleCare->whereDate('created_at', '>=', $dateBegin)
                    ->whereDate('created_at', '<=', $dateEnd);
            }
            // dd($saleCare->get());
            $count    = $saleCare->count();
            $saleCare = $saleCare->paginate(10);
        }
        // $saleCare   = SaleCare::orderBy('id', 'desc')->where('assign_user', $id)->paginate(50);

        // dd($saleCare);
        return view('pages.sale.index')->with('count', $count)->with('sales', $sales)->with('saleCare', $saleCare)->with('listCall', $listCall);
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

            
            $saleCare->id_order             = $req->id_order;
            $saleCare->sex                  = $req->sex;
            $saleCare->full_name            = $req->name;
            $saleCare->phone                = $req->phone;
            $saleCare->address              = $req->address;
            $saleCare->type_tree            = $req->type_tree;
            $saleCare->product_request      = $req->product_request;
            $saleCare->reason_not_buy       = $req->reason_not_buy;
            $saleCare->note_info_customer   = $req->note_info_customer;
            $saleCare->assign_user          = $req->assgin;
            $saleCare->page_name            = $req->page_name;
            $saleCare->page_id              = $req->page_id;
            $saleCare->messages             = $req->messages;
            $saleCare->old_customer         = $req->old_customer;
            $saleCare->page_link            = $req->page_link;
            $saleCare->save();

            if (!isset($req->id)) {
                $tProduct = Helper::getListProductByOrderId( $saleCare->id_order);
                //gửi thông báo qua telegram
                $telegram = Helper::getConfigTelegram();
                if ($telegram && $telegram->status == 1) {
                    $tokenGroupChat = $telegram->token;
                    $chatId = $req->chat_id;

                    if ($chatId &&  $chatId == 'id_VUI') {
                        $chatId = $telegram->id_VUI;
                    } else {
                        $chatId = $telegram->id_CSKH;
                    }
                    // echo 'chat id: ' . $chatId;
                    
                    $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
                    $client         = new \GuzzleHttp\Client();

                    // $userAssign     = Helper::getUserByID($order->assign_user)->real_name;
                    // $nameUserOrder  = ($order->sex == 0 ? 'anh' : 'chị') ;

                    $notiText       = "Khách hàng: $saleCare->full_name"
                        . "\nSố điện thoại: $saleCare->phone"
                        . "\nNội dung: $saleCare->messages";
                       
                       
                    if ($saleCare->old_customer) {
                        $notiText .= "Đã nhận được hàng."  . "\nĐơn mua: " . $tProduct;     
                    } else {
                        $notiText .= "\nNguồn data: " . $req->text;
                    }
                    // dd($notiText);
                    $name =  $saleCare->user->real_name ?: $saleCare->user->name;
                    
                    $notiText .= "\nSale nhận data: " . $name;
                    
                    // dd ($chatId);
                    // . ($req->text) ? $req->text : "\nĐã nhận được hàng."
                   
                    $response = $client->request('GET', $endpoint, ['query' => [
                        'chat_id' => $chatId, 
                        'text' => $notiText,
                    ]]);
                }
            }
            $routeName = \Request::route();

            // return response()->json(['success'=>$text]);
            // $req->session()->put('success', 'Tạo tác nghiệp sale thành công.');

            if ($routeName && $routeName->getName() == 'sale-care-save') {
                notify()->success($text, 'Thành công!');
            }
           
           
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

    public function getListSalesByPermisson($user, $dataFilter = null) 
    {
        $roles      = $user->role;
        $list       = SaleCare::orderBy('id', 'desc');

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
        } else if (isset($dataFilter['sale'])) {
            /** user đang login = full quyền và đang lọc 1 sale */
            $list = $list->where('assign_user', $dataFilter['sale']);
        }

        return $list;
    }

    public function search(Request $req)
    {
        if ($req->search) {
            $helper     = new Helper();
            $sales      = User::where('status', 1)->where('is_sale', 1)->get();
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
}
