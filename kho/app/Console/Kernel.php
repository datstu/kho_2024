<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\SaleCare;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Helpers\Helper;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Log;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function() {
          // $this->wakeUp();
          $this->updateStatusOrderGHN();
          Log::channel('new')->info('your_message');
        })->everyMinute();

        $schedule->call(function() {
          // $this->wakeUp();
          $this->crawlerPancake();
          Log::channel('new')->info('run craw pancake');
        })->everyMinute();

        $schedule->call(function() {
          // $this->wakeUp();
          $this->crawlerPancakeTricho();
          Log::channel('new')->info('run craw pancake tricho');
        })->everyMinute();
        
    }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
      $this->load(__DIR__.'/Commands');

      require base_path('routes/console.php');
  }

  private function wakeUp() 
  {
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
              // $chatId         = '-4140296352';
              $chatId         = '-4128471334';
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
        
        /** ko gửi thông báo nếu đơn chỉ có sp paulo */
        $notHasPaulo = Helper::hasAllPaulo($order->id_product);

        //check đơn này đã có data chưa
        $issetOrder = Helper::checkOrderSaleCare($order->id);

        // echo "$order->status $notHasPaulo";
       
        // status = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->status == 3 && $notHasPaulo) {

          $orderTricho = $order->saleCare;
          $groupId = '';
          if (!empty($orderTricho->group_id) && $orderTricho->group_id == 'tricho') {
            // $assgin_user = Helper::getSaleTricho()->id;
            $assgin_user = $order->saleCare->assign_user;
            $groupId = 'tricho';
            // echo 'case 1';
          } else {
            $assignCSKH = Helper::getAssignCSKH();
            // echo 'case 2';
            if ($assignCSKH) {
              $assgin_user = $assignCSKH->id;
              //  echo 'case 2.1';
            } else {
              $assgin_user = $order->assign_user;
              // echo 'case 2.2';
            }
          }
          
          // echo 'sisis';
         
        

          $sale = new SaleController();
          $data = [
            'id_order' => $order->id,
            'sex' => $order->sex,
            'name' => $order->name,
            'phone' => $order->phone,
            'address' => $order->address,
            'assgin' => $assgin_user,
            'group_id' => $groupId,
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


  
  public function crawlerPancake()
  {
    $panCake = Helper::getConfigPanCake();
    if ($panCake->status == 1 && $panCake->page_id != '' && $panCake->token != '') {
      $pageId = $panCake->page_id;
      $pages  = json_decode($pageId,1);
      $token  = $panCake->token;

      if (count($pages) > 0) {
        foreach ($pages as $key => $val) {
          $pIdPan   = $val['id'];
          $namePage = $val['name'];
          $linkPage = $val['link'];
          $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
          $today    = strtotime(date("Y/m/d H:i"));
          $before = strtotime ( '-2 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
          $before = date ( 'Y/m/d H:i' , $before );
          $before = strtotime($before);

          $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
          $response = Http::withHeaders(['access_token' => $token])->get($endpoint);
    
          if ($response->status() == 200) {
            $content  = json_decode($response->body());
            if ($content->success) {
              $data     = $content->conversations;
              foreach ($data as $item) {
                $recentPhoneNumbers = $item->recent_phone_numbers[0];
                $mId      = $recentPhoneNumbers->m_id;
                $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
                $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
                $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';

                $assgin_user = 0;
                $is_duplicate = false;
                $phone = Helper::getCustomPhoneNum($phone);
                $checkSaleCareOld = Helper::checkOrderSaleCarebyPhonePage($phone, $val['id'], $mId, $assgin_user, $is_duplicate);

                if ($name && $checkSaleCareOld) {  
                  if ($assgin_user == 0) {
                    $assignSale = Helper::getAssignSale();
                    $assgin_user = $assignSale->id;
                  }

                  $is_duplicate = ($is_duplicate) ? 1 : 0;
                  $sale = new SaleController();
                  $data = [
                    'page_link' => $linkPage,
                    'page_name' => $namePage,
                    'sex'       => 0,
                    'old_customer' => 0,
                    'address'   => '',
                    'messages'  => $messages,
                    'name'      => $name,
                    'phone'     => $phone,
                    'page_id'   => $pIdPan,
                    'text'      => 'Page ' . $namePage,
                    'chat_id'   => 'id_VUI',
                    'm_id'      => $mId,
                    'assgin'    => $assgin_user,
                    'is_duplicate' => $is_duplicate
                  ];

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
  }

  public function crawlerPancakeTricho()
  {
    $pages = [
      'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIwODY4NGY2ZS1hZjJhLTQwNDgtYTg1Ny0zNzMwMjAxOTViYTQiLCJzZXNzaW9uX2lkIjoiWGFMd3diSk1JeXlaMVg3N09nN2F3K2pOdTVUTXd5bjFzQ1pBZ1JPRmZSbyIsIm5hbWUiOiJEYXQgRGluaCIsImxvZ2luX3Nlc3Npb24iOm51bGwsImluZm8iOnsib3MiOm51bGwsImRldmljZV90eXBlIjozLCJjbGllbnRfaXAiOiI0Mi4xMTUuMTY0LjE0NyIsImJyb3dzZXIiOjF9LCJpYXQiOjE3MTk3MzI1OTgsImZiX25hbWUiOiJEYXQgRGluaCIsImZiX2lkIjoiMTIxMjMxMTg1NDk1NDE4IiwiZXhwIjoxNzI3NTA4NTk4LCJhcHBsaWNhdGlvbiI6MX0.yeksbxM457DJpnHHaIBYvcIbXXf_nyxxW-Tw_Ha_lCY',
      'pages' => [
        [
          "name" => "Trichoderma Basilus - 100 Tỷ Bào Tử - 0986987791",
          "link" => "https://www.facebook.com/profile.php?id=61561895244196",
          "id"   => "378087158713964",
          "group" => 'tricho'
        ],
        [
          "name" => "Tricho Basilus - 1 Lít Pha 1000 Lít Nước - 0986987791",
          "link" => "https://www.facebook.com/profile.php?id=61561817156259",
          "id"   => "352893387908060",
          "group" => 'tricho'
        ],
        [
          "name" => "Trichoderma Basilus - 1 Xô Pha 10.000 Lít Nước",
          "link" => "https://www.facebook.com/profile.php?id=61562087439362",
          "id"   => "381180601741468",
          "group" => 'tricho'
        ]
      ]
    ];

    // dd('hi');
    $token  = $pages['token'];

      foreach ($pages['pages'] as $key => $val) {
        $pIdPan   = $val['id'];
        $namePage = $val['name'];
        $linkPage = $val['link'];
        $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
        $today    = strtotime(date("Y/m/d H:i"));
        $before = strtotime ( '-5 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
        $before = date ( 'Y/m/d H:i' , $before );
        $before = strtotime($before);

        $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
        $response = Http::withHeaders(['access_token' => $token])->get($endpoint);
    
        if ($response->status() == 200) {
          $content  = json_decode($response->body());
          if ($content->success) {
            $data     = $content->conversations;
            // dd($data);
            foreach ($data as $item) {
              $recentPhoneNumbers = $item->recent_phone_numbers[0];
              $mId      = $recentPhoneNumbers->m_id;
              $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
              $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
              $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';

              $assgin_user = 0;
              // $assgin_user = Helper::getSaleTricho()->id;
              $is_duplicate = false;
              $phone = Helper::getCustomPhoneNum($phone);
              $checkSaleCareOld = Helper::checkOrderSaleCarebyPhonePageTricho($phone, $mId, $is_duplicate, $assgin_user);

              if ($name && $checkSaleCareOld) {  
                if ($assgin_user == 0) {
                  $assignSale = Helper::getSaleTricho();
                  $assgin_user = $assignSale->id;
                }

                $is_duplicate = ($is_duplicate) ? 1 : 0;
                $sale = new SaleController();
                $data = [
                  'page_link' => $linkPage,
                  'page_name' => $namePage,
                  'sex'       => 0,
                  'old_customer' => 0,
                  'address'   => '',
                  'messages'  => $messages,
                  'name'      => $name,
                  'phone'     => $phone,
                  'page_id'   => $pIdPan,
                  'text'      => 'Page ' . $namePage,
                  'chat_id'   => 'id_VUI_tricho',
                  'm_id'      => $mId,
                  'assgin'    => $assgin_user,
                  'is_duplicate' => $is_duplicate,
                  'group_id' => 'tricho'
                ];

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
