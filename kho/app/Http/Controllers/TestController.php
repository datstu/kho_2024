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
use App\Models\Group;
use App\Models\SrcPage;
use DateTime;
use PHPUnit\TextUI\Help;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use function PHPUnit\Framework\assertFalse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
// setlocale(LC_TIME, 'vi_VN.utf8');
// setlocale(LC_TIME, "vi_VN");
class TestController extends Controller
{
  use WithoutMiddleware;

  public function testBaoKim()
  {
    return view('test.index');
  }
  public function tele() 
  {
    // echo 'hi';
    $strEncode = "Th\u00f4ng b\u00e1o d\u1eef li\u1ec7u t\u1eeb LadiPage\nname : Li\nphone : 0912523644\nform_item3209 : T\u00f4i mu\u1ed1n b\u00e1o gi\u00e1 qua \u0111i\u1ec7n tho\u1ea1i\nNgu\u1ed3n t\u1eeb: https:\/\/www.nongnghiepsachvn.net\/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-\n\u0110\u1ecba ch\u1ec9 IP: 14.160.234.108";
    $str = "Th\u00f4ng b\u00e1o d\u1eef li\u1ec7u t\u1eeb LadiPage\nname : dinh khanh dat\nphone : 0912523644\nform_item3209 : T\u00f4i mu\u1ed1n b\u00e1o gi\u00e1 qua \u0111i\u1ec7n tho\u1ea1i\nNgu\u1ed3n t\u1eeb: https:\/\/www.nongnghiepsachvn.net\/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-\n\u0110\u1ecba ch\u1ec9 IP: 14.160.234.108";
    // $strEncode = "<pre>Thông báo dữ liệu từ LadiPage
    // name : Li
    // phone : 0912523644
    // form_item3209 : Tôi muốn báo giá qua điện thoại
    // Nguồn từ: https://www.nongnghiepsachvn.net/mua4-tang2?utm_source=120208585133120157&utm_campaign=120208585133100157&fbclid=IwAR0rlPJKCCmKp3bQjpV78Qju_3OLfoOK_VfYJ-jXDCOM_jbyLbhnUKmFxgA_aem_AY8k3fYevsitPWBGbMAfIikjN8cDkS4itppXbjvUmJ1u-HGgzpspTx9GCQnQlm_VGYUxmwSF6Wx75UPqSqsNJNQ-
    // Địa chỉ IP: 14.160.234.108</pre>";

    $name = $phone = $mess = $src = '';
    $array = preg_split('/\r\n|\r|\n/', $str);
    
    foreach ($array as $item) {
      $arrItem = explode(":", $item);
      // dd($arrItem);
      if (count($arrItem) > 1) {
        // echo('> 1 ' . $arrItem[0] . '<br>');
        // $arrItem[0] = 'name';
        $strSw = preg_replace('/\s+/', '', $arrItem[0]);
        switch ($strSw) {
          case "name":
            // echo('name' . $arrItem[1] .'<br>');
            $name = $arrItem[1];
            break;
          case 'phone':
            // echo('phone' . $arrItem[1] . '<br>');
            $phone = $arrItem[1];
            break;
          case 'form_item3209':
            // echo('form_item3209' . $arrItem[1] . '<br>');
            $mess = $arrItem[1];
            break;
          case 'form_item3209':
            // echo('form_item3209' . $arrItem[1] . '<br>');
            $name = $arrItem[1];
            break;
          default:
            if (count($arrItem) == 3) {
              // echo('src ' . $arrItem[2] . '<br>');
              $src = $arrItem[2];
            }
            break;
        }

        
      
        // echo "<pre>";
        // print_r($arrItem);
        // echo "</pre>";
      }
    }
    // $name = $phone = $mess = $src ='';
    echo 'name: ' . $name . '<br>';
    echo 'phone: ' . $phone . '<br>';;
    echo 'mess: ' . $mess . '<br>';
    echo 'src: ' . $src . '<br>';
  }
  public function testTelephone() 
  {
    // Kiểm tra các số điện thoại mẫu
    $testNumbers = [
      "+84973409613",
      "0912345678", // đúng
      "0312345678", // đúng
      "07123456789", // sai (nhiều hơn 10 chữ số)
      "02123456789", // đúng (số cố định)
      "051234567", // sai (ít hơn 10 chữ số)
    ];

    foreach ($testNumbers as $number) {
      if ($this->isValidVietnamPhoneNumber($number)) {
          echo "$number là số điện thoại hợp lệ.\n";
          
      } else {
          echo "$number không phải là số điện thoại hợp lệ.\n";
      }

      echo "<br>";
    }
  }

  public function updateData() 
  {
    // $l = SaleCare::where('phone', $phone)->where('page_id', $pageId);
    // // ->update(['m_id' => $mId])
    // ;
    // echo "<pre>";
    // print($l->get());
    // echo "</pre>";

    $panCake = Helper::getConfigPanCake();
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
        // $before   = strtotime(date('Y-m-d H:i', strtotime($today. ' - 1 days')));
        // $before   = strtotime(date('Y-m-d H:i', strtotime($today. ' - 1 hour')));
        $before = strtotime ( '-20 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
        $before = date ( 'Y/m/d H:i' , $before );
        $before = strtotime($before);
        // dd( $today);
        // $response = Http::withHeaders(['token' => $token])
        //   ->get($endpoint, [
        //     'type' => "PHONE,DATE:$before+-+$today",
        //     'access_token' => $token,
        //     'from_platform' => 'web'
        // ]);
        $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
        $response = Http::get($endpoint);

        if ($response->status() == 200) {
          $content  = json_decode($response->body());
          // dd($content);
          $data     = $content->conversations;
          // dd($data);
          $i = 0;
          foreach ($data as $item) {
            $phone = $item->recent_phone_numbers[0]->phone_number;
            $mId = $item->recent_phone_numbers[0]->m_id;
            echo "\n$phone - $pIdPan - $mId" . "<br>";
            // echo "\n" . "<br>";
            $i++;
            
            $l = SaleCare::where('phone', $phone)->where('page_id', $pIdPan)->orderBy('id', 'desc')->get()->first();
              
            if ($l) {
              $l->m_id = $mId;
              $l->save();
            }
            // if ($l) {
            //   echo "<pre>";
            //   print($l->get());
            //   echo "</pre>";
            // }
         
          }
          echo $i;
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

  
  public function isValidVietnamPhoneNumber($phone) {
    // Biểu thức chính quy cho số điện thoại di động
    $mobilePattern = "/^(9|3|7|5|8|09|03|07|08|05)\d{8}$/";
    // Biểu thức chính quy cho số điện thoại cố định
    $landlinePattern = "/^(02|03|04|05|06|07|08|09|84)\d{7,8}$/";
    
    // Biểu thức chính quy cho số điện thoại di động với mã quốc gia
    $mobilePatternWithCountryCode = "/^(\+84|0084)(9|3|7|8|5)\d{8}$/";
    // Biểu thức chính quy cho số điện thoại cố định với mã quốc gia
    $landlinePatternWithCountryCode = "/^(\+84|0084)(2|3|4|5|6|7|8|9)\d{7,8}$/";
    // $customlinePattern = "/^(+84|84)\d{7,8}$/";
    if ( preg_match($mobilePatternWithCountryCode, $phone) || preg_match($mobilePatternWithCountryCode, $phone) || preg_match($mobilePattern, $phone) || preg_match($landlinePattern, $phone)) {
        return true;
    } else {
        return false;
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
            // $assignCSKH = Helper::getAssignCSKH();
            // echo 'case 2';
            // if ($assignCSKH) {
            //   $assgin_user = $assignCSKH->id;
            //    echo 'case 2.1';
            // } else {
            //   $assgin_user = $order->assign_user;
            //   echo 'case 2.2';
            // }
            $assgin_user = 50;
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

  public function testMoveColumn()
  {
    return view('pages.test');
  }

  public function crawlerPancake()
  {
    $panCake = Helper::getConfigPanCake();
    if ($panCake->status == 1 && $panCake->page_id != '' && $panCake->token != '') {
      $pageId = $panCake->page_id;
      $pages  = json_decode($pageId, 1);
      $token  = $panCake->token;

      if (count($pages) > 0) {
        foreach ($pages as $key => $val) {
          $pIdPan   = $val['id'];
          $srcModel = SrcPage::where('id_page', $pIdPan)->first();
          $group = $srcModel->group;

          if ($group && $group->status) {
            $namePage = $val['name'];
            $linkPage = $val['link'];
            $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
            $today    = strtotime(date("Y/m/d H:i"));
            $before = strtotime ( '-12 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
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
                      if ($assgin_user == 0 && $srcModel && $group->sales) {
                        // dd($group);
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
  }

  public function crawlerPancakeTricho()
  {
    $pages = [
      'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiI0MTlkYTE5Ny0xNzFkLTQyMjYtODFiMS0wNDA2OGQyZjA3NTMiLCJzZXNzaW9uX2lkIjoiUzBrQUx5UWtqVUJjcFhmcFJPMS9HUlUyT21jM0owVC9sYkFaR0pCUXdtVSIsIm5hbWUiOiJExrDGoW5nIFRodSIsImxvZ2luX3Nlc3Npb24iOm51bGwsImluZm8iOnsib3MiOm51bGwsImRldmljZV90eXBlIjozLCJjbGllbnRfaXAiOiIxNzEuMjUzLjI3LjIzOSIsImJyb3dzZXIiOjF9LCJpYXQiOjE3MTk5OTI4MTUsImZiX25hbWUiOiJExrDGoW5nIFRodSIsImZiX2lkIjoiMTM1MjI1ODA3NDIyOTMzIiwiZXhwIjoxNzI3NzY4ODE1LCJhcHBsaWNhdGlvbiI6MX0.lAn8-zAl6_GJhpmjj3Wx1305w62mSWj6fBUYY4um6Q4',
      'pages' => [
        [
          "name" => "Tricho Bacillus - 1Xô pha 10.000 lít nước",
          "link" => "https://www.facebook.com/trichobacillus",
          "id"   => "389136690940452",
          "group" => 'tricho'
        ],
        [
          "name" => "Tricho Basilus - 1 Lít Pha 1000 Lít Nước - 0986987791",
          "link" => "https://www.facebook.com/profile.php?id=61561817156259",
          "id"   => "378087158713964",
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

  public function crawlerGroup()
  {
    $groups = Group::where('status', 1);

    foreach ($groups->get() as $group) {

      $pages = $group->srcs;

      foreach ($pages as $page) {
        if ($page->type == 'pc') {
          $this->crawlerPancakePage($page, $group);
        }
      }
    }
  }
  public function crawlerPancakePage($page, $group)
  {
    // dd($page);
    $pIdPan = $page->id_page;
    $token  = $page->token;
    $namePage = $page->name;
    $linkPage = $page->link;
    $chatId = $group->tele_hot_data;

    echo "pIdPan: $pIdPan " . '<br>';
    echo "token: $token \n" . '<br>';
    echo "namePage: $namePage \n" . '<br>';
    echo "linkPage: $linkPage \n" . '<br>';
    echo "chatId: $chatId \n" . '<br>';
    if ( $pIdPan != '' && $token != '' && $namePage != '' && $linkPage != '' && $chatId != '') {
    
      $endpoint = "https://pancake.vn/api/v1/pages/$pIdPan/conversations";
      $today    = strtotime(date("Y/m/d H:i"));
      $before   = strtotime ( '-15 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
      $before   = date ( 'Y/m/d H:i' , $before );
      $before   = strtotime($before);

      $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
      $response = Http::withHeaders(['access_token' => $token])->get($endpoint);
   
      // dd($response);
      if ($response->status() == 200) {
        $content  = json_decode($response->body());
        if ($content->success) {
          $data     = $content->conversations;
          // dd($data);
          
          foreach ($data as $item) {
            
            try {
              $recentPhoneNumbers = $item->recent_phone_numbers[0];
              $mId      = $recentPhoneNumbers->m_id;
              $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
              $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
              $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';

              $assgin_user = 0;
              $is_duplicate = false;
              $phone = Helper::getCustomPhoneNum($phone);
              
              $checkSaleCareOld = Helper::checkOrderSaleCarebyPhoneV2($phone, $mId, $is_duplicate, $assgin_user);

              if ($name && $checkSaleCareOld) {  
                if ($assgin_user == 0) {

                  $assignSale = Helper::getAssignSaleByGroup($group);
                  if (!$assignSale) {
                    break;
                  }

                  //assignSale: item in model detail_user_group
                  $assgin_user = $assignSale->id_user;
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
                  'chat_id'   => $chatId,
                  'm_id'      => $mId,
                  'assgin'    => $assgin_user,
                  'is_duplicate' => $is_duplicate,
                  'group_id'  => $group->id,
                ];

                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
              }
            
          } catch (\Exception $e) {
            // return $e;
            echo '$phone: ' . $phone;
            dd($e);
            // return redirect()->route('home');
          }
        }
        }
      }           
    }
  }

  public function updateStatusOrderGhnV2() 
  {
    $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
    // dd($orders);
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

        // status = 3 = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->status == 3 && $notHasPaulo) {

          $orderTricho = $order->saleCare;
          $chatId = $groupId = '';
          $saleCare = $order->saleCare;

          /** dành cho những data TN và đơn hàng khi chưa nhóm group */
          if ($order->saleCare && $saleCare->group) {

            $group = $saleCare->group;
            $chatId = $group->tele_cskh_data;
            $groupId = $group->id;
            /** có tick chia đều team cskh thì chạy tìm người để phát data cskh
             *  ngược lại ko tick thì đơn của sale nào người đó care
             * nếu chọn chia đều team CSKH thì mặc định luôn có sale nhận data
             */
            if ($group->is_share_data_cskh) {
              $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
              // dd( $assgin_user);
            } else {
              $assgin_user = $order->saleCare->assign_user;
            }

          } else if (!empty($orderTricho->group_id) && $orderTricho->group_id == 'tricho') {
            $groupId = 'tricho';
            
            //id_CSKH_tricho 4234584362
            $chatId = '-4286962864'; 
            $assgin_user = $order->assign_user;
          } else {
            $assgin_user = 50;
            //cskh 4128471334
            $chatId = '-4558910780';
          }

          $typeCSKH = Helper::getTypeCSKH($order);
          $pageName = $order->saleCare->page_name;
          $pageId = $order->saleCare->page_id;
          $pageLink = $order->saleCare->page_link;

          // dd($order->saleCare);
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
            'type_TN' => $typeCSKH, 
            // 'old_customer' => 1
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

  public function export()
  {
    $sale     = new SaleController();

    // $req = new Request();
    $req['daterange'] = ['01/09/2024', '31/10/2024'];
    $req['sale'] = '56';

    $list =  $sale->getListSalesByPermisson(Auth::user(), $req);
    // $list->whereNull('id_order_new');
    $list->where('old_customer', 0);
    $list->where('is_duplicate', 0);
    // $list->where('group_id', '7');
    // $list->where('page_id', '7');

    //aplus
    $src = ['424411670749761', '398822199987832', '397050860162599', 'https://www.phanbonorganic.com/uudai45', 'Hotline Aplus'];
    $list = $list->where(function($query) use ($src) {
      foreach ($src as $term) {
        if (is_numeric($term)) {
            $query->orWhere('page_id', 'like', '%' . $term . '%');
        } else {
            $query->orWhere('page_link', 'like', '%' . $term . '%');
        }

        if (str_contains($term, 'Tricho') || str_contains($term, 'line')) {
            $query->orWhere('page_name', 'like', '%' . $term . '%');
        }
        // $query->orWhere('page_id', 'like', '%' . $term . '%');
      }
    });
    // dd($list->get());

    $dataExport[] = [
      'Tên' , 'Số điện thoại', 'Tin nhắn khách để lại', 'Note TN trước đó', 'Ngày nhận'
    ];

    
    // $list->orderBy('id', 'desc');
    // echo "<pre>";
    // print_r($list->get());
    // echo "</pre>";
    // die();
    foreach ($list->get() as $data) {

      // if ($data->phone != '0942727079') {
      //   continue;
      // } 
        
      $checkOldCustomer = $this->isOldCustomer($data->phone);
      if ($checkOldCustomer) {
        continue;
      }
      
      // echo 'name: ' . $data->full_name . '<br>';
      // echo 'phone: ' . $data->phone . '<br>';
      // echo 'message: : ' . $data->TN_can . '<br>';
      // echo 'date: ' . $data->created_at . '<br>';
      $dataExport[] = [
        $data->full_name,
        $data->phone,
        $data->messages,
        $data->TN_can,
        date_format($data->created_at,"H:i d-m-Y "),
      ];
    }

    // dd($dataExport);
    // dd($dataExport);
    return Excel::download(new UsersExport($dataExport), 'invoices.xlsx');

  }

  public function isOldCustomer($phone)
  {
    $order = Orders::where('phone', $phone)->first();
    if ($order) {
      return true;
    } 

    return false;
  }

  public function wakeUp()
  {
    $listSc = SaleCare::whereNotNull('result_call')
      ->whereNotNull('type_TN')
      ->where('result_call', '!=', 0)
      ->where('result_call', '!=', -1)
      ->where('has_TN', 1)
      ->get();

    foreach ($listSc as $sc) {
      // echo "$sc->id " . "<br>";

      $call = $sc->call;

      if (empty($call->time)) {
        continue;
      }

      $time = $call->time;
      $nameCall   = $call->callResult->name;
      $updatedAt  = $sc->time_update_TN;
      $isRunjob   = $sc->is_runjob;
      $TNcan   = $sc->TN_can;
      $saleAssign   = $sc->user->real_name;
      
      if (!$call || !$time || !$updatedAt || $isRunjob || !$saleAssign) {
        continue;
      }
      
      //cộng ngày update và time cuộc gọi
      $newDate = strtotime("+$time hours", strtotime($updatedAt));
      if ($newDate <= time()) {

        $nextTN = $call->thenCall;
        if (!$nextTN) {
          continue;
        }

        $chatId         = '-4286962864';
        $tokenGroupChat = '7127456973:AAGyw4O4p3B4Xe2YLFMHqPuthQRdexkEmeo';
        $group = $sc->group;

        if ($group) {
          $chatId = $group->tele_nhac_TN;
          $tokenGroupChat =  $group->tele_bot_token;
        }

        //set lần gọi tiếp theo
        if ($sc->type_TN != $nextTN->id) {
          $sc->result_call = 0;
        }

        $sc->type_TN = $nextTN->id;
        $sc->has_TN = 0;
        $sc->is_runjob = 1;
        $sc->save();

        //gửi thông báo qua telegram
        $endpoint       = "https://api.telegram.org/bot$tokenGroupChat/sendMessage";
        $client         = new \GuzzleHttp\Client();

        $notiText       = "Khách hàng $sc->full_name sđt $sc->phone"
          . "\nĐã tới thời gian tác nghiệp."
          . "\nKết quả gọi trước đó: $nameCall"
          . "\nGhi chú trước: $TNcan"
          . "\nSale tác nghiệp: $saleAssign"; 

        if ($chatId) {
          $client->request('GET', $endpoint, ['query' => [
            'chat_id' => $chatId, 
            'text' => $notiText,
          ]]);
        }
        
      }
    }
  }

  public function fix()
  {
    $from = date('2024-07-01');
    $to = date('2024-07-31');
    // $list = Orders::whereNotExists(function ($query) {
    //   $query->select(\DB::raw('*'))
    //       ->from('sale_care')
    //       ->where('sale_care.id', 'orders.sale_care')
    //       ->where('old_customer', 0)
    //       ;
    //   })
    //   ->where('status', 3)
    //   ->whereBetween('created_at', [$from, $to])
    //   ->get();

    $list = \DB::select("SELECT *
FROM   orders
WHERE  NOT EXISTS
  (SELECT *
   FROM   sale_care
   WHERE  
   sale_care.id = orders.sale_care and sale_care.old_customer = 0 
   
   ) AND orders.created_at BETWEEN '2024-07-01' 
                     AND '2024-07-31 23:59:59.993' ORDER BY `id` ASC;");


      // dd($list);
    // echo "<pre>";
    // print_r($list);
    // echo "</pre>";
    //   die();
      // 
    foreach ($list as $item) {
      // dd($item->id);
      $saleCare = SaleCare::
        where('phone', 'like', '%' . $item->phone . '%')
        ->where('old_customer', 0)
        ->first();

        // dd('hi');
        // dd($saleCare);
      // trường hợp có data TN nhưng chưa map => update map
      if (!$saleCare) {
        echo $item->phone . "<br>";
        $sale = new SaleController();
        $data = [
          'page_link' => '',
          'page_name' => '',
          'sex'       => 0,
          'old_customer' => 0,
          'address'   => $item->address,
          'messages'  => '',
          'name'      => $item->name,
          'phone'     => $item->phone,
          'page_id'   => '',
          'text'      => '',
          // 'chat_id'   => $chatId,
          'm_id'      => '',
          'assgin'    => $item->assign_user,
          'is_duplicate' => 0,
          'id_order_new' => $item->id,
          'created_at'  => $item->created_at
        ];

        $request = new \Illuminate\Http\Request();
        $request->replace($data);
        $sale->save($request);

      } else {
        echo $item->phone . "<br>";
        $order = Orders::find($item->id);
        if ($order) {
          $order->sale_care = $saleCare->id;
          $order->save();
        }
       
      }
      // dd($saleCare);
      //trường hợp có đơn hàng nhưng chưa có data TN => create data và map
    }
        // dd($list);
    
  }
}




