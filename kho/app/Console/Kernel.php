<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\SaleCare;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Helpers\Helper;
use App\Http\Controllers\SaleController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function() {
            $this->wakeUp();
            $this->updateStatusOrderGHN();
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

    private function updateStatusOrderGHN() 
    {
        $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
        foreach ($orders as $order) {
          $endpoint = "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/detail" ;
          $response = Http::withHeaders(['token' => '180d1134-e9fa-11ee-8529-6a2e06bbae55'])
            ->post($endpoint, [
              'order_code' => 'G8PU4H68',
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
    
              case 'cancel':
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
            $isFertilizer = Helper::checkFertilizer($order->assign_user);
    
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
