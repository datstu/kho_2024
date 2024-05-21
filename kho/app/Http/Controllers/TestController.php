<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AddressController;
use App\Models\SaleCare;
use App\Models\User;
use App\Helpers\Helper;
use DateTime;
use PHPUnit\TextUI\Help;

// setlocale(LC_TIME, 'vi_VN.utf8');
// setlocale(LC_TIME, "vi_VN");
class TestController extends Controller
{
  public function updateStatusOrderGHN() 
  {
    $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
    foreach ($orders as $order) {
      $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail" ;
      $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])
        ->post($endpoint, [
          'order_code' => $order->shippingOrder->order_code,
          'token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55',
        ]);
    
      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        $data     = $content->data;

        switch ($data->status) {
          case 'ready_to_pick':
            $order->status = 1;
          case 'picking':
            #chờ lây hàng
            $order->status = 1;
            break;
            
          case 'delivered':
            #hoàn tât
            $order->status = 3;
            break;

          case 'return':
            $order->status = 0;
          case 'cancel':
            $order->status = 0;
          case 'returned':
            #hoàn/huỷ
            $order->status = 0;
            break;
          
          default:
            # đang giao
            $order->status = 2;
            break;
        }
        
        $order->save();
        
        //chỉ áp dụng cho đơn phân bón
        $isFertilizer = Helper::checkFertilizer($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);
        
        // status = 'hoàn tất', tạo data tác nghiệp sale
        // dd()
        if ($order->status == 3 && $isFertilizer) {
            $sale = new SaleController();
            $data = [
                'id_order' => $order->id,
                'sex' => $order->sex,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'assgin' => $order->assign_user,
            ];

            if ($issetOrder || $order->id) {
              $data['old_customer'] = 1;
            }

            $request = new \Illuminate\Http\Request();
            $request->replace($data);
            $sale->save($request);
        }
      }
    }
  }

  public function t() {
    $t = 6500 + 12000 +8800 + 29500 + 24000;
    return $t;
  }

  public function crawlerPancake()
  {
    $panCake = Helper::getConfigPanCake();
    
    if($panCake->status == 1 && $panCake->page_id != '' && $panCake->token != '') {
      $pageId = $panCake->page_id;
      $pages  = json_decode($pageId,1);
      $token  = $panCake->token;

      if (count($pages) > 0) {
        foreach ($pages as $key => $val) {
          $endpoint = "https://pancake.vn/api/v1/pages/$val/conversations";
          $today    = strtotime(date("Y/m/d H:i"));
          // $before   = strtotime(date('Y-m-d H:i', strtotime($today. ' - 1 days')));
          $before   = strtotime(date('Y-m-d H:i', strtotime($today. ' - 1 hour')));

          $response = Http::withHeaders(['token' => $token])
            ->get($endpoint, [
              'type' => "PHONE,DATE:$today+-+$before",
              'access_token' => $token,
          ]);
    
          if ($response->status() == 200) {
            $content  = json_decode($response->body());
            $data     = $content->conversations;

            // dd($data);
            // $i = 0;
            foreach ($data as $item) {
              // if ($i > 5) break;
              // $i++;
    
              $length = count($item->recent_phone_numbers);
             
              $recentPhoneNumbers = $item->recent_phone_numbers[$length-1];
              $phone = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
              $name = isset($item->customers[0]) ? $item->customers[0]->name : '';
              $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';
              if ($phone && $name && !Helper::checkOrderSaleCarebyPhonePage($phone, $val)) {            
                $sale = new SaleController();
                $data = [
                    'page_name' => $key,
                    'sex'       => 0,
                    'address'   => '...',
                    'messages'  => $messages,
                    'name'      => $name,
                    'phone'     => $phone,
                    'page_id'   => $val,
                    'text'      => 'Page ' .$key,
                    'chat_id'   => 'id_VUI'
                ];
    
                $assignSale = Helper::getAssignSale();
                if ($assignSale) {
                  // $idSale = $assignSale->id;
                  $data['assgin'] = $assignSale->id;
                }

                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
              }
            }
          }
        }
      }
    }
  }

  public function test3() {
    $str = Helper::getListProductByOrderId(285);
    echo($str);
  }
  public function test() {
    $listSc = SaleCare::whereNotNull('next_step')->get();
    foreach ($listSc as $sc) {
      $time       = $sc->call->time;
      $nameCall   = $sc->call->name;
      $updatedAt  = $sc->updated_at;
      $isRunjob   = $sc->is_runjob;
  
      if ($time && !$isRunjob) {
        //cộng ngày update và time cuộc gọi
        $newDate = strtotime("+$time hours", strtotime($updatedAt));

        if ($newDate <= time()) {
          $sc->is_runjob = 1;
          $sc->save();

          //gửi thông báo qua telegram
          $tokenGroupChat = '7127456973:AAGyw4O4p3B4Xe2YLFMHqPuthQRdexkEmeo';
          $chatId         = '-4140296352';
          $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
          $client         = new \GuzzleHttp\Client();

          $notiText       = "Khách hàng $sc->full_name sđt $sc->phone"
            . "\nĐã tới thời gian tác nghiệp."
            . "\nKết quả gọi trước đó: $nameCall"
            . "\nCây trồng: $sc->type_tree"
            . "\nNhu cầu dòng sản phẩm: $sc->product_request"
            . "\nLý do không mua hàng: $sc->reason_not_buy"
            . "\nGhi chú thông tin khách hàng: $sc->note_info_customer.";  

          $client->request('GET', $endpoint, ['query' => [
            'chat_id' => $chatId, 
            'text' => $notiText,
          ]]);
        }
      }
    }
  }

  public function test2() {
    $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
    foreach ($orders as $order) {
      $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail" ;
      $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])
        ->post($endpoint, [
          'order_code' => $order->shippingOrder->order_code,
          'token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55',
        ]);
   
      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        $data     = $content->data;

        switch ($data->status) {
          case 'delivered':
            #hoàn tât
            $order->status = 3;
            break;
          case 'return':
            $order->status = 0;
          case 'cancel':
            $order->status = 0;
          case 'returned':
            #hoàn/huỷ
            $order->status = 0;
            break;
          
          default:
            # đang giao
            $order->status = 2;
            break;
        }
        
        $order->save();
        
        //chỉ áp dụng cho đơn phân bón
        $isFertilizer = Helper::checkFertilizer($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);
        
        // status = 'hoàn tất', tạo data tác nghiệp sale
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
      }
    }
  }
}
