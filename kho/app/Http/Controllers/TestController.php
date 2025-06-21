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

use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
  use WithoutMiddleware;

  public function nga()
  {
    $page = 21; // Page number
    $perPage = 10; // Records per page
    $products = DB::table('tbl_product')
      ->join('tbl_category_product', 'tbl_product.catID', '=', 'tbl_category_product.category_id')
      // ->limit(5)
      ->select('tbl_product.*','tbl_category_product.category_name as category_name')
      ->paginate($perPage, ['*'], 'page', $page);
    // dd($products);
    $dataExport[] = [
      // 'Tên' , 'mã', 'Đã đăng', 'còn hàng', 'Gía bán thường', 'Mô tả ngắn', 'Mô tả', 'Hình ảnh'
      'post_title','sku', 'Categories', 'post_content','post_excerpt', 'regular_price','stock_status','type',
    ];
      
      // dd($products);
    foreach ($products as $product)
    {
      // $listImg = '';
      // $listImg = 'https://ngathinkpad.com/wp-content/uploads/2025/' . $product->image;
      // // $listImg .= 'http://localhost/ngaWP/wp-content/uploads/2025/' . $product->image;
      // $productImgs = DB::table('tbl_images')->where('productID', $product->productID)->get();
      // if ($productImgs->count() > 0) {
      //   foreach ($productImgs as $img) {
      //     $listImg .= ',https://ngathinkpad.com/wp-content/uploads/2025/05/' . $img->imgName;
      //   }
      // }

      $dataExport[] = [
        $product->productName,
        $product->sku,
        'Laptop,' . $product->category_name,
        $product->product_desc,
        $product->moTaNgan,
        $product->price,
        'instock',
        'simple',
        // $listImg
      ];
    }

    // dd($dataExport);

  // print_r($dataExport);
  // dd($dataExport);
  return Excel::download(new UsersExport($dataExport), 'nga.csv');

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

    dd($listSc);
    foreach ($listSc as $sc) {
      if ($sc->id != '15967') {
        continue;
      }
      $time       = $sc->call->time;
      $nameCall   = $sc->call->name;
      $updatedAt  = $sc->updated_at;
      $isRunjob   = $sc->is_runjob;
      dd($sc);
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
    // $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();
    $orders = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
      ->whereNotIn('orders.status', [0,3])
      ->where('shipping_order.vendor_ship', 'GHN')
      ->get('orders.*');

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

        // if($group->id != 5) {
        //     continue;
        // }
      $pages = $group->srcs;

      // dd($pages);
// dd($pages);
      foreach ($pages as $page) {
        //  if ($page->id_page != 425922557281500) {
        //      continue;
        //  }
        if ($page->type == 'pc' ) {
          $this->crawlerPancakePage($page, $group);
        }
      }
    }
  }
  public function crawlerPancakePage($page, $group)
  { 
    $srcId = $page->id;
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
      $before   = strtotime ( '-5 hour' , strtotime ( date("Y/m/d H:i") ) ) ;
      $before   = date ( 'Y/m/d H:i' , $before );
      $before   = strtotime($before);

      $endpoint = "$endpoint?type=PHONE,DATE:$before+-+$today&access_token=$token";
      // $endpoint = "$endpoint?DATE:$before+-+$today&access_token=$token";
      $response = Http::withHeaders(['access_token' => $token])->get($endpoint);

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
              $phone = Helper::getCustomPhoneNum($phone);

              // if ($phone != '0778165177') {
              //   continue;
              // }
              
              $inputTime = strtotime($item->inserted_at);
              $now = time();

              $secondsIn3Days = 3 * 24 * 60 * 60;

              if ($now - $inputTime >= $secondsIn3Days) {
                  echo "Đã quá 3 ngày";
                  continue;
              } 
             
              $is_duplicate = $hasOldOrder = $isOldCustomer = $assgin_user = 0;
              $checkSaleCareOld = Helper::checkOrderSaleCarebyPhoneV5($phone, $mId, $is_duplicate, $hasOldOrder);
              $typeCSKH = 1;
             
              if ($name && $checkSaleCareOld) {
                $assignSale = Helper::assignSaleFB($hasOldOrder, $group, $phone, $typeCSKH, $isOldCustomer);
                if (!$assignSale) {
                  break;
                }
                // dd($assignSale);
                if ($isOldCustomer == 1) {
                  $chatId = $group->tele_cskh_data;
                }
               
                $assgin_user = $assignSale->id;
                $is_duplicate = ($is_duplicate) ? 1 : 0;
                // dd($assignSale);
                $sale = new SaleController();
                $data = [
                  'page_link' => $linkPage,
                  'page_name' => $namePage,
                  'sex'       => 0,
                  'old_customer' => $isOldCustomer,
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
                  'has_old_order'  => $hasOldOrder,
                  'src_id'  => $srcId,
                  'type_TN' => $typeCSKH, 
                ];
                
                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
              } else {
                echo $phone . " \n";
              }
            
            } catch (\Exception $e) {
              // return $e;
              // echo '$phone: ' . $phone;
              // dd($e);
              // return redirect()->route('home');
            }
          }
        }
      }           
    }
  }

  public function updateStatusOrderGHTK() 
  {
    $orders = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
      ->whereNotIn('orders.status', [0,3])
      ->where('shipping_order.vendor_ship', 'GHTK')
      ->get('orders.*');

    foreach ($orders as $order) {

      $endpoint = "https://services.giaohangtietkiem.vn/services/shipment/v2/" . $order->shippingOrder->order_code;
      $token = '1L0DDGVPfiJwazxVW0s7AQiUhRH1hb7E1s63rtd';
      $response = Http::withHeaders(['token' => $token])->get($endpoint);
      $response = $response->json();

      if ($response['success']) {
        $data     = $response['order'];
        // dd($data);
        switch ($data['status']) {
          #chờ lây hàng
          case 1:
          case 2:
          case 7:
          case 12:
          case 8:
            $order->status = 1;
            break;
          #chờ lây hàng
            

          # đang giao
          case 3:
          case 10:
          case 4:
          case 9:
            $order->status = 2;       
            break;
          # đang giao
    
          #thành công
          case 5:
          // case 6:
            $order->status = 3;
            break;

          #hoàn/huỷ
          case 20:
          case 21:
          case 11:
          case -1:
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

        //getOriginal lấy trực tiếp field từ db
        // status = 3 = 'hoàn tất', tạo data tác nghiệp sale
        if ($order->getOriginal('status') == 3) {

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

            // dd($group);
            if ($group->is_share_data_cskh) {
              
              $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
            } else {
              $assgin_user = $order->saleCare->assign_user;
              $user = $order->saleCare->user;

              //tài khoản đã khoá hoặc chặn nhận data => tìm sale khác trong nhóm
              if (!$user->is_receive_data || !$user->status) {
                $assgin_user = Helper::getAssignSaleByGroup($group, 'cskh')->id_user;
              }
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
            // $chatId = '-4128471334';
          }
          

          $typeCSKH = Helper::getTypeCSKH($order);
          $pageName = $order->saleCare->page_name;
          $pageId = $order->saleCare->page_id;
          $pageLink = $order->saleCare->page_link;

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

          if ($order->saleCare->src_id) {
            $data['src_id'] = $order->saleCare->src_id;
          } else if ($order->saleCare->type != 'ladi') {
            $pageSrc = SrcPage::where('id_page', $order->saleCare->page_id)->first();
            if ($pageSrc) {
              $data['src_id'] = $pageSrc->id;
            }
          }

          // dd($data);

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

   public function updateStatusOrderGhnV2() 
  {
    $orders = Orders::has('shippingOrder')->whereNotIn('status', [0,3])->get();

    foreach ($orders as $order) {

      // if ($order->id != 3304) {
      //   continue;
      // }

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

            // dd($group);
            if ($group->is_share_data_cskh) {
              
              $assgin_user = Helper::getAssignCskhByGroup($group, 'cskh')->id_user;
            } else {
              $assgin_user = $order->saleCare->assign_user;
              $user = $order->saleCare->user;

              //tài khoản đã khoá hoặc chặn nhận data => tìm sale khác trong nhóm
              if (!$user->is_receive_data || !$user->status) {
                $assgin_user = Helper::getAssignSaleByGroup($group, 'cskh')->id_user;
              }
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
            // $chatId = '-4128471334';
          }
          

          $typeCSKH = Helper::getTypeCSKH($order);
          $pageName = $order->saleCare->page_name;
          $pageId = $order->saleCare->page_id;
          $pageLink = $order->saleCare->page_link;

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

          if ($order->saleCare->src_id) {
            $data['src_id'] = $order->saleCare->src_id;
          } else if ($order->saleCare->type != 'ladi') {
            $pageSrc = SrcPage::where('id_page', $order->saleCare->page_id)->first();
            if ($pageSrc) {
              $data['src_id'] = $pageSrc->id;
            }
          }

          // dd($data);

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

  public function parseProductString($str) 
  {
    // dd($str);
    $products = [];
    
    // Tách ra theo dấu +
    $parts = preg_split('/\s*\+\s*/', $str);

    // Kiểm tra có xN ở cuối không (hệ số nhân)
    $multi = 1;
    if (preg_match('/x(\d+)$/i', trim($str), $m)) {
        $multi = (int) $m[1];
    }

    foreach ($parts as $item) {
        // Loại bỏ hệ số nhân cuối mỗi item nếu có
        $cleanItem = preg_replace('/x\d+$/i', '', trim($item));

        // Lấy số lượng và tên sản phẩm
        if (preg_match('/^(\d+)(kg|l|)?\s*(.+)$/iu', $cleanItem, $matches)) {
            $qty = (int) $matches[1];
            $name = strtolower(trim($matches[3])); // chuẩn hóa tên sản phẩm
            $totalQty = $qty * $multi;

            // Cộng dồn nếu sản phẩm trùng
            if (isset($products[$name])) {
                $products[$name] += $totalQty;
            } else {
                $products[$name] = $totalQty;
            }
        }
    }

    $newProduct = [];
    // foreach ($products as $k => $product) {
    //   echo $k . '<br>';
    //   if ($k == '')
    // }
    // dd($products);
    return $products;
  }

  public function listProductTmp()
  {
    $list = [
      
      'xô tricho 10kg' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'xô tricho' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'tricho 10kg' => [
        'price' => 1440000,
        'unit' => 'Xô',
        'real_name' => 'Phân bón VL Vinakom Bomix - Tricho Bacillus Xô 10Kg'
      ],
      'Đạm tôm 20l' => [
        'price' => 1500000,
        'unit' => 'Can',
        'real_name' => 'Đạm Tôm Agrium 20Kg'
      ],
      'humic' => [
        'price' => 120000,
        'unit' => 'Gói',
        'real_name' => 'Phân bón Ogranic AB03- Humic Acid Powder Usa 1Kg (Hàng tặng không thu tiền)'
      ],
      'siêu lớn trái' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Lớn Trái 500ml (Hàng tặng không thu tiền)'
      ],
      
      'siêu kích hoa' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Kích Hoa 500ml (Hàng tặng không thu tiền)'
      ],
      'vọt đọt' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Vọt Đọt 500ml (Hàng tặng không thu tiền)'
      ],
      'canxibo' => [
        'price' => 120000,
        'unit' => 'Chai',
        'real_name' => 'Phân bón AB02 - Agrium Siêu Canxibo 500ml (Hàng tặng không thu tiền)'
      ],
      'A plus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
      'a plus' => [
        'price' => 1350000,
        'unit' => 'Can',
        'real_name' => 'Phân bón Agroplus organic E can 5kg'
      ],
    ];
    return $list;
  }


  public function phoneGHTK()
  {
    $phones_array = '
      0933191177
      0328793636
      0905379134
      0866450251
      0353970557
      0975331656
      0366142678
      0358816168
      0359617428
      0986173506
      0384228435
      0375960415
      0986483119
      0388149185
      0867931559
      0399055620
      0945606277
      0969030436
      0902468632
      0933122159
      0379811303
      0376751877
      0348117291
      0797977555
      0962696792
      0395383304
      0948849999
      0358679141
      0968820840
      0359133830
      0918012132
      0979090910
      0988483858
      0876950436
      0924306992
      0939370882
      0815411368
      0944606943
      0374557477
      0974369559
      0941232567
      0971770226
      0939631283
      0928736200
      0356999747
      0983907009
      0987688552
      0908889478
      0914089522
      0398248169
      0378890788
      0919178959
      0334477112
      0982147521
      0349040471
      0343131897
      0377689724
      0362739039
      0947410655
      0919669507
      0907616093
      0967549757
      0384321863
      0869887736
      0787977535
      0966631067
      0913624860
      0984339180
      0919189484
      0987628825
      0386036370
      0798084444
      0767721176
      0907898147
      0362493177
      0333658147
      0336389369
      0907005057
      0939626300
      0988068487
      0913008379
      0354464552
      0919361936
      0975472387
      0918543899
      0907379559
      0971633677
      0972345453
      0984608364
      0367398818
      0388688443
      0398860223
      0345033200
      0946286382
      0907812356
      0928891569
      0386549851
      0343279335
      0919236440
      0907132242
      0944633772
      0913918424
      0976514765
      0903784011
      0939282700
      0982440091
      0908718296
      0393555345
      0358132859
      0336963339
      0353260063
      0796950707
      0949506939
      0906625679
      0966423310
      0364160152
      0857770725
      0346345959
      0911297499
      0373433441
      0965995644
      0333915561
      0984834335
      0909106457
      0907184000
      0815753339
      0354216886
      0373205679
      0913967191
      0949656979
      0886637306
      0382582449
      0334763766
      0906953355
      0906818060
      0938032759
      0359946460
      0786524655
      0939270900
      0845628807
      0906359061
      0913125053
      0936443194
      0907328334
      0989205910
      0986739941
      0917866143
      0706382381
      0763869166
      0961620760
      0939642702
      0368762133
      0364405448
      0984596829
      0972500965
      0397781139
      0939391734
      0795840226
      0942740441
      0967725756
      0362287332
      0939195029
      0369675428
      0796848222
      0986223113
      0359924689
      0915739126
      0325599630
      0886099868
      0985958816
      0942020234
      0973005422
      0934074529
      0943460649
      0386439569
      0916963479
      0793228907
      0946318154
      0793906210
      0907299323
      0394546370
      0966192778
      0338385765
      0979990512
      0916727456
      0362213579
      0772176146
      0762829361
      0338373747
      0348764875
      0939442256
      0939038953
      0362828134
      0395850365
      0986000609
      0369176607
      0981495971
      0939903077
      0985778785
      0944335345
      0342040781
      0979408633
      0968579523
      0905916944
      0972174881
      0918054869
      0972203517
      0363162161
      0396547018
      0329937951
      0869246547
      0976508786
      0973578773
      0396566681
      0372192388
      0989796081
      0968494456
      0373228880
      0937563728
      0961833949
      0934208268
      0377529695
      0912356218
      0921612161
      0398186063
      0859744449
      0396573970
      0973165336
      0906475043
      0989802554
      0939011065
      0973733118
      0939185202
      0969603827
      0949862347
      0934128211
      0948579248
      0364244835
      0909125539
      0342569799
      0979441179
      0987191413
      0772819399
      0968904774
      0382864719
      0977543338
      0979901462
      0987062504
      0868201276
      0345421344
      0918281679
      0785431784
      0901284759
      0982014008
      0977505565
      0379038153
      0986417768
      0333399812
      0986688547
      0832710940
      0942919527
      0382755935
      0979931118
      0915848387
      0979060620
      0394954032
      0984692187
      0907044872
      0985464510
      0373223205
      0352441427
      0975108363
      0946777448
      0972792659
      0919938189
      0976105526
      0377890123
      0918769969
      0973262008
      0793161638
      0869447912
      0397120271
      0363251141
      0944571400
      0943954876
      0907616093
      0966658149
      0368848766
      0918782264
      0924965412
      0362390828
      0978040107
      0328419670
      0395528320
      0917811197
      0918714683
      0944113147
      0983855339
      0915516136
      0378839412
      0868196938
      0382261907
      0973587317
      0364519350
      0776639558
      0366664849
      0909889554
      0364643709
      0971404747
      0397927224
      0382323443
      0935282283
      0796638568
      0909379616
      0392337427
      0783834855
      0934914484
      0976351119
      0971255462
      0799416977
      0968938268
      0785431784
      0389482202
      0907091147
      0933313977
      0987535242
      0986149811
      0973587317
      0971335019
      0372890386
      0868672901
      0382531201
      0828419719
      0355401684
      0786245290
      0843471288
      0918992037
      0328283481
      0975047137
      0349153124
      0355964458
      0336642658
      0938006662
      0358692229
      0935112768
      0762933361
      0979586593
      0355334036
      0965150739
      0794334924
      0374839505
      0372539228
      0939784817
      0909477979
      0842852526
      0945550979
      0939370882
      0974788744
      0976727439
      0973791529
      0905319753
      0775890943
      0974317841
      0915618704
      0943422337
      0971657947
      0979181234
      0353009394
      0386514007
      0783235778
      0976258774
      0906412111
      0982012151
      0986411823
      0983158598
      0337890619
      0939293959
      0707138321
      0354517365
      0969051498
      0975553424
      0394377178
      0943236017
      0942272588
      0974040173
      0971649930
      0918236489
      0355701557
      0346076662
      0347183568
      0946187738
      0776888377
      0336694146
      0967763277
      0937735678
      0355055628
      0847435374
      0815411368
      0909938343
      0977636048
      0986485269
      0775890943
      0392458831
      0378151686
      0974624882
      0868147916
      0938464565
      0968237141
      0792095036
      0984188033
      0979289191
      0913703693
      0947619629
      0974187189
      0394975962
      0941421919
      0528652963
      0979276862
      0945167016
      0365712182
      0976809579
      0981589379
      0908876553
      0848152884
      0909501238
      0364005488
      0975550042
      0906512089
      0972646879
      0375445909
      0942555528
      ';

      return $phones_array;
  }
  public function getPhoneArray($phones_array)
  {
      // Loại bỏ khoảng trắng thừa trong từng dòng
    
    $phones_array = preg_split("/\r\n|\n|\r/", trim($phones_array));
    $phones_array = array_map(function($phone) {
      return preg_replace('/\s+/', '', $phone); // Xoá tất cả khoảng trắng
    }, $phones_array);
    return $phones_array;
  }

  public function parseProductComboTricho($productName)
  {
    $arr = explode("+", $productName);
    // dd($arr);
    $newName = '';
    foreach ($arr as $el) {
      if ($newName != '') {
        $newName .= ' + ';
      }

      if (strpos($el, '3 xô tricho 10kg tặng 1 xô tricho 10kg') > -1) {
        $name = '4 xô tricho 10kg';
        $newName .= $name;
      } else {
        $newName .= $el;
      }
      
    }
    
    return $newName;
  }

  public function phoneNhattin()
  {
    $arr = '0328497759
    0933191925
    0847155548
    0983343399
    0377230045
    0969641741
    0366132040
    0935959779
    0349201087
    0979960507
    0985888116
    0375104112
    0918289092
    0981790809
    0843159160
    0355437336
    0869875745
    0862402166
    0374328002
    0827291357
    0336313256
    0973473470
    0978091862
    0382643829
    0569526668
    0916994648
    0393421925
    0399099516
    0989648985
    0915231500
    0982131324
    0963945297
    0985749088
    0357774807
    0918866955
    0387890190
    0974825119
    0375090281
    0918639478
    0962773071
    0986579887
    0987379197
    0969844815
    0903368809
    0359721749
    0978717880
    0334278533
    0986618630
    0979714851
    0376531927
    0859965479
    0889433053
    0908923882
    0374999761
    0984432117
    0375797862
    0984427264
    0368778567
    0988311139
    0375797862
    0983315215
    0986579887
    0919819583
    0964450638
    0563094450
    0989945631
    0364025373
    0965827627
    0383063942
    0349760339
    0985663546
    0988077443
    0354608289
    0979854881
    0916354870
    0394778283
    0383255969
    0947918979
    0918776096
    0326037115
    0986377912
    0966365139
    0396873222
    0369538264
    0915979010
    0865480884
    0942447474
    0935842419
    0335784214
    0917610905
    0961245886
    0985217486
    0357226593
    0862196719
    0949243213
    0842779555
    0967988838
    0384687653
    0963101683
    0372285803
    0398606216
    0333428780
    0987665860
    0985846752
    ';
    return $arr;
  }

  public function exportTax()
  {
    $sale     = new OrdersController();

    // $req = new Request();
    $time = ['01/01/2024', '30/06/2025'];

    $timeBegin  = str_replace('/', '-', $time[0]);
    $timeEnd    = str_replace('/', '-', $time[1]);
    $dateBegin  = date('Y-m-d',strtotime("$timeBegin"));
    $dateEnd    = date('Y-m-d',strtotime("$timeEnd"));

    $list = Orders::join('shipping_order', 'shipping_order.order_id', '=', 'orders.id')
     
      ->where('shipping_order.vendor_ship', 'GHN')
      ->where('orders.status', 3)
      // ->where('orders.status', 3)
      // ->where('orders.status', 3)
      ->whereDate('orders.created_at', '>=', $dateBegin)
      ->whereDate('orders.created_at', '<=', $dateEnd)
      ->orderBy('orders.id', 'desc')
      // ->where('phone', '0971724878')
      // ->limit(7)
      ->get('orders.*');
      // ->sum('orders.total');;

    // $phoneNhatin = $this->phoneNhattin();
    // $phoneGHTK = $this->phoneGHTK();
    
    // $listPhone = $this->getPhoneArray($phoneGHTK);
    // $list = Orders::whereIn('phone', $listPhone)
    //   // ->whereDate('orders.created_at', '>=', $dateBegin)
    //   // ->whereDate('orders.created_at', '<=', $dateEnd)
    //   // ->limit(17)
    //   ->get();
    // dd($list);
    $dataExport[] = [
      'Số thứ tự hóa đơn (*)' , 'Ngày hóa đơn', 'Tên đơn vị mua hàng', 'Mã khách hàng', 'Địa chỉ', 'Mã số thuế', 'Người mua hàng',
      'Email', 'Hình thức thanh toán', 'Loại tiền', 'Tỷ giá', 'Tỷ lệ CK(%)', 'Tiền CK', 'Tên hàng hóa/dịch vụ (*)', 'Mã hàng', 
      'ĐVT', 'Số lượng', 'Đơn giá', 'Tỷ lệ CK (%)', 'Tiền CK', '% thuế GTGT', 'Tiền thuế GTGT', 'Thành tiền(*)'
    ];

    $i = 1;
    $orderTmp = [];

    // dd(count($listPhone));
    // dd($list->count());
    foreach ($list as $data) {
      
     
      //ghtk ko lấy
      /** nếu có thì bỏ qa */
      // if ($data->shippingOrder && $data->shippingOrder->vendor_ship == 'GHN') {
      //   continue;
      // }
      // dd($data);
      $timeday = new DateTime($data->created_at);
      $begin = new DateTime("2025-01-01 00:00:00");
      $end = new DateTime("2025-03-31 00:00:00");
      $orderTmp[] = $data->id;
      $listProduct = json_decode($data->id_product,true);
      // dd($listProduct);
      // if ($i == 4) {
      //   // dd($data);
      // }
      /**
       * 1/ 1 Đạm tôm 20l
       *    3kg humic
       * 2/ 1 Đạm tôm 20l + 3kg humic
       * 3/ 1 Đạm tôm 20l + 3kg humic
       *    1kg humic
       */

       //trường hợp đơn chỉ cho 1 sp
      if (count($listProduct) == 1) {
        $item = $listProduct[0];
        $product = getProductByIdHelper($item['id']);
        $percenTax = 'KCT';
        $totalGTGT = '';
        $total = 0;

        if (!$product) {
          continue;
        }

        $productName = $product->name;
        $k = $i;

        //check trường hợp sản phẩm cb và sản phẩm lẻ
        // có dấu + là sản phẩm combo
        if (strpos($productName, '+') !== false) {
        //  dd('hi');
          $tmp = [];
          if (strpos($productName, '3 xô tricho 10kg tặng 1 xô tricho 10kg') !== false) {
            $productName = $this->parseProductComboTricho($productName);
          }

          $items = $this->parseProductString($productName);
          $productTmp = [];
          
          // dd($items);
          foreach ($items as $key => $val)
          {

            // if ($key == 'aplus') {
            //   dd($items);
            // }
            $list = $this->listProductTmp();

            if ($key == 'xô tricho 10kg tặng 1 xô tricho 10kg') {
              dd($productName);
            }
            if (!isset($list[$key])) {
              continue;
            }

            $productTmp = $list[$key];
            $percenTax = 'KCT';
            $totalGTGT = '';
            $total = 0;

            if (!$productTmp) {
              continue;
            }

            $totalOrder = $data->total;
            $productPrice = $productTmp['price'];
            $qty = $val;
    
            if (strpos($productTmp['real_name'], "Tôm") !== false || strpos($productTmp['real_name'], "tôm") !== false) {
              $percenTax = '5';

              /* tổng tiền bao gồm VAT 5%: 3.150.000
                số lượng: 2 sản phẩm
                thuế VAT: 5%
                b1: tổng tiền chưa VAT = 3150000/ 1.05 = 3000000 (3tr)
                b2: tính giá chưa VAT mỗi sp: 3tr /2 = 1tr5
              */
              $taxBeforeTotal = $totalOrder / 1.05;
              $taxbeforeProduct = $taxBeforeTotal / $qty;
              $productPrice = $taxbeforeProduct;
              $totalGTGT = 0.05 * $taxBeforeTotal;
              $total = $totalOrder;
            } 

            if ($k != $i) {
              $tmp = [
                '',//Số thứ tự hóa đơn (*)
                '', // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                '',// Địa chỉ
                '',// Mã số thuế
                '',// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $productTmp['unit'],// 'ĐVT',
                $qty,//  'Số lượng', 
                $productTmp['price'],//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];
            } else {
              $tmp = [
                $i,//Số thứ tự hóa đơn (*)
                date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                $data->address,// Địa chỉ
                '',// Mã số thuế
                $data->name,// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $productTmp['unit'],// 'ĐVT',
                $qty,//  'Số lượng', 
                $productPrice,//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];
            }
            $dataExport[] = $tmp;
            $k++;
          }
        } else {
          if (strpos($product->name, "Tôm") !== false || strpos($product->name, "tôm") !== false) {
            $percenTax = '5';
            $totalGTGT = 5 * $product->price / 100;
            $total = $totalGTGT + $product->price;
            $tmp = [];
          }

          if ($k != $i) {
            $tmp = [
              '',//Số thứ tự hóa đơn (*)
              '', // Ngày hóa đơn
              '',// Tên đơn vị mua hàng
              '',// Mã khách hàng
              '',// Địa chỉ
              '',// Mã số thuế
              '',// Người mua hàng
              '',// Email
              '',// Hình thức thanh toán
              '',// Loại tiền
              '',// Tỷ giá
              '',// Tỷ lệ CK(%)
              '',// Tiền CK
              $product->name,// Tên hàng hóa/dịch vụ (*)
              '',// Mã hàng
              $product->unit,// 'ĐVT',
              $item->val,//  'Số lượng', 
              $product->price,//  'Đơn giá', 
              '',//  'Tỷ lệ CK (%)', 
              '',//  'Tiền CK',
              $percenTax, // '% thuế GTGT',
              $totalGTGT, //  'Tiền thuế GTGT',
              $total,   // 'Thành tiền(*)'
            ];  
          } else {
            $tmp = [
            $i,//Số thứ tự hóa đơn (*)
            date_format($data->created_at,"d-m-Y "), // Ngày hóa đơn
            '',// Tên đơn vị mua hàng
              '',// Mã khách hàng
              $data->address,// Địa chỉ
              '',// Mã số thuế
              $data->name,// Người mua hàng
              '',// Email
              '',// Hình thức thanh toán
              '',// Loại tiền
              '',// Tỷ giá
              '',// Tỷ lệ CK(%)
              '',// Tiền CK
              $product->name,// Tên hàng hóa/dịch vụ (*)
              '',// Mã hàng
              $product->unit,// 'ĐVT',
              $item['val'],//  'Số lượng', 
              $product->price,//  'Đơn giá', 
              '',//  'Tỷ lệ CK (%)', 
              '',//  'Tiền CK',
              $percenTax, // '% thuế GTGT',
              $totalGTGT, //  'Tiền thuế GTGT',
              $total,   // 'Thành tiền(*)'
            ];
          }
          
          $dataExport[] = $tmp;
          $k++;
          
        }
      } 
      /** số tổng sản phẩm lớn hơn 1 */
      else {
        $j = $i;
        // dd($listProduct);
        foreach ($listProduct as $item) {
          $product = getProductByIdHelper($item['id']);
          $percenTax = 'KCT';
          $totalGTGT = '';
          $total = 0;
          
          $tmp = [];
          if (!$product) {
            continue;
          }
          // if ($item['id'] != 58) {
          //   continue;
          // }
          $productName = $product->name;
          // dd($product);
          if (strpos($productName, '+') !== false) {

            if (strpos($productName, '3 xô tricho 10kg tặng 1 xô tricho 10kg') !== false) {
              $productName = $this->parseProductComboTricho($productName);
            }
            $items = $this->parseProductString($productName);
            $productTmp = [];
            foreach ($items as $key => $val)
            {
              $list = $this->listProductTmp();
              $productTmp = $list[$key];
              $percenTax = 'KCT';
              $totalGTGT = '';
              $total = 0;
              $totalOrder = $data->total;
              $productPrice = $product->price;
              if (!$productTmp) {
                continue;
              }
                
              $qty = $item['val'];
              if (strpos($productTmp['real_name'], "Tôm") !== false || strpos($productTmp['real_name'], "tôm") !== false) {
                $percenTax = '5';

                /* tổng tiền bao gồm VAT 5%: 3.150.000
                  số lượng: 2 sản phẩm
                  thuế VAT: 5%
                  b1: tổng tiền chưa VAT = 3150000/ 1.05 = 3000000 (3tr)
                  b2: tính giá chưa VAT mỗi sp: 3tr /2 = 1tr5
                */
                $taxBeforeTotal = $totalOrder / 1.05;
                $taxbeforeProduct = $taxBeforeTotal / $qty;
                $productPrice = $taxbeforeProduct;
                $totalGTGT = 0.05 * $taxBeforeTotal;
                $total = $totalOrder;
              } 

              if ($j != $i) {
                $tmp = [
                  '',//Số thứ tự hóa đơn (*)
                  '', // Ngày hóa đơn
                  '',// Tên đơn vị mua hàng
                  '',// Mã khách hàng
                  '',// Địa chỉ
                  '',// Mã số thuế
                  '',// Người mua hàng
                  '',// Email
                  '',// Hình thức thanh toán
                  '',// Loại tiền
                  '',// Tỷ giá
                  '',// Tỷ lệ CK(%)
                  '',// Tiền CK
                  $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                  '',// Mã hàng
                  $productTmp['unit'],// 'ĐVT',
                  $val,//  'Số lượng', 
                  $productTmp['price'],//  'Đơn giá', 
                  '',//  'Tỷ lệ CK (%)', 
                  '',//  'Tiền CK',
                  $percenTax, // '% thuế GTGT',
                  $totalGTGT, //  'Tiền thuế GTGT',
                  $total,   // 'Thành tiền(*)'
                ];
              } else {
                $tmp = [
                  $i,//Số thứ tự hóa đơn (*)
                  date_format($data->created_at,"H:i d-m-Y "), // Ngày hóa đơn
                  '',// Tên đơn vị mua hàng
                  '',// Mã khách hàng
                  $data->address,// Địa chỉ
                  '',// Mã số thuế
                  $data->name,// Người mua hàng
                  '',// Email
                  '',// Hình thức thanh toán
                  '',// Loại tiền
                  '',// Tỷ giá
                  '',// Tỷ lệ CK(%)
                  '',// Tiền CK
                  $productTmp['real_name'],// Tên hàng hóa/dịch vụ (*)
                  '',// Mã hàng
                  $productTmp['unit'],// 'ĐVT',
                  $val,//  'Số lượng', 
                  $product['price'],//  'Đơn giá', 
                  '',//  'Tỷ lệ CK (%)', 
                  '',//  'Tiền CK',
                  $percenTax, // '% thuế GTGT',
                  $totalGTGT, //  'Tiền thuế GTGT',
                  $total,   // 'Thành tiền(*)'
                ];
              }
  

              $dataExport[] = $tmp;
              $j++;
            }
              
          } else {
            // dd($data->total);
            $totalOrder = $data->total;
            $productPrice = $product->price;
            $qty = $item['val'];
            if (strpos($product->name, "Tôm") !== false || strpos($product->name, "tôm") !== false) {
              $percenTax = '5';
              /* tổng tiền bao gồm VAT 5%: 3.150.000
                số lượng: 2 sản phẩm
                thuế VAT: 5%
                b1: tổng tiền chưa VAT = 3150000/ 1.05 = 3000000 (3tr)
                b2: tính giá chưa VAT mỗi sp: 3tr /2 = 1tr5
              */
              $taxBeforeTotal = $totalOrder / 1.05;
              $taxbeforeProduct = $taxBeforeTotal / $qty;
              $productPrice = $taxbeforeProduct;
              $totalGTGT = 0.05 * $taxBeforeTotal;
              $total = $totalOrder;
            }

            if ($j != $i) {
              $tmp = [
                '',//Số thứ tự hóa đơn (*)
                '', // Ngày hóa đơn
                '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                '',// Địa chỉ
                '',// Mã số thuế
                '',// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $product->name,// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $product->unit,// 'ĐVT',
                $qty,//  'Số lượng', 
                $productPrice,//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];  
            } else {
              $tmp = [
              $i,//Số thứ tự hóa đơn (*)
              date_format($data->created_at,"H:i d-m-Y "), // Ngày hóa đơn
              '',// Tên đơn vị mua hàng
                '',// Mã khách hàng
                $data->address,// Địa chỉ
                '',// Mã số thuế
                $data->name,// Người mua hàng
                '',// Email
                '',// Hình thức thanh toán
                '',// Loại tiền
                '',// Tỷ giá
                '',// Tỷ lệ CK(%)
                '',// Tiền CK
                $product->name,// Tên hàng hóa/dịch vụ (*)
                '',// Mã hàng
                $product->unit,// 'ĐVT',
                $qty,//  'Số lượng', 
                $productPrice,//  'Đơn giá', 
                '',//  'Tỷ lệ CK (%)', 
                '',//  'Tiền CK',
                $percenTax, // '% thuế GTGT',
                $totalGTGT, //  'Tiền thuế GTGT',
                $total,   // 'Thành tiền(*)'
              ];
            }
            
            $dataExport[] = $tmp;
            $j++;
          }
        }
       
      }
      $i++;
    }

    // dd(($dataExport));
    // dd(($orderTmp));

    // echo "<pre>";
    // print_r($dataExport);
    // echo "</pre>";
    return Excel::download(new UsersExport($dataExport), 'GHN-thang-03.xlsx');
  }


  public function export()
  {
    $sale     = new SaleController();
    $req = new Request();
    $req['daterange'] = ['01/03/2025', '31/03/2025'];
    // $req['sale'] = '97';
    // $req['typeDate'] = '2';

    // $sales = ['50','74'];

    $list =  $sale->getListSalesByPermisson(Auth::user(), $req);
    $list->whereNull('id_order_new');
    $list->whereNull('id_order');
    $list->where('old_customer', 0);
    $list->where('is_duplicate', 0);
    $list->where('group_id', '9');
    $list->paginate(300, ['*'], 'page', 2);
    // $list->whereIn('assign_user', $sales);
    dd($list->get());
    $dataExport[] = [
      'Tên' , 'Số điện thoại', 'Tin nhắn khách để lại', 'Note TN trước đó', 'Ngày nhận'
    ];

    foreach ($list->get() as $data) {

      $tnCan = $data->TN_can;
      if ($data->listHistory) {
        foreach ($data->listHistory as $his) {
          $tnCan .= date_format($his->created_at,"d-m-Y ") . ': ' . $his->note . ', ';
        }

      }
      $dataExport[] = [
        $data->full_name,
        $data->phone,
        $data->messages,
        $tnCan,
        date_format($data->created_at,"H:i d-m-Y "),
      ];
    }

    return Excel::download(new UsersExport($dataExport), 'thang02.xlsx');
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

  public function wakeUp()
  {
    // $listSc = SaleCare::whereNotNull('result_call')
    //   ->whereNotNull('type_TN')
    //   ->where('result_call', '!=', 0)
    //   ->where('result_call', '!=', -1)
    //   ->where('has_TN', 1)
    //   ->where('created_at', '>' , '2025-04-30')
    //   ->get();

      
  $listSc = SaleCare::where('phone', '0979410529')->get();
    foreach ($listSc as $sc) {

      // if ($sc->id != '15967') {
      //   continue;
      // }
      
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

      if (!$sc->user->status || !$sc->user->is_receive_data) {
        continue;
      }
      if ($sc->listHistory->count()) {
        $sc->listHistory;
        $TNcan = $sc->listHistory[0]->note;
      }
      
      if (!$call || !$time || !$updatedAt || $isRunjob || !$saleAssign) {
        continue;
      }

      //cộng ngày update và time cuộc gọi
      if ($sc->time_wakeup_TN) {
        $newDate = strtotime($sc->time_wakeup_TN);
      } else {
        $newDate = strtotime("+$time hours", strtotime($updatedAt));
      }

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

          if ($sc->old_customer && $sc->old_customer == 1 && $group->tele_nhac_TN_CSKH) {
            $chatId = $group->tele_nhac_TN_CSKH;
          }
        }

        //set lần gọi tiếp theo
        if ($sc->type_TN != $nextTN->id) {
          $sc->result_call = 0;
        }

        // 24 id: nhắc lại
        if ($nextTN->id != 24) {
          $sc->type_TN = $nextTN->id;
        }
        
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
}




