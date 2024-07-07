<?php


namespace App\Helpers;
use App\CategoryProduct;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\ShippingOrder;
use App\Models\User;
use App\Models\Call;
use App\Http\Controllers\ProductController;
use App\Models\Orders;
use App\Models\SaleCare;
use Illuminate\Support\Facades\Log;
use App\Models\Telegram;
use App\Models\Pancake;
use App\Models\LadiPage;
use PHPUnit\TextUI\Help;
use App\Models\SrcPage;

setlocale(LC_TIME, 'vi_VN.utf8');

class Helper
{
    public static function getListStatus() {
        $listStatus = [
            1 => 'Chưa Giao Vận',
            2 => 'Đang Giao', 
            3 => 'Hoàn Tất',
            0 => 'Huỷ',
        ];
        return $listStatus;
    }
    
    public static function getProductByIdHelper($id) {
        return Product::find($id);
    }

    public static function getSexHelper($sex) {
        return $sex == 0 ? 'Nam' : 'Nữ';
    }

    public static function getWardNameHelper($wardId, $districtId) {
        $result = "";
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=" . $districtId;
        $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);
       
        if ( $response->status() == 200) {
            $content    = json_decode($response->body());
            foreach ($content->data as $ward) {
                if ($ward->WardCode == $wardId) {
                    $result = $ward->WardName;
                    break;
                }
            }
        }

        return $result;
    }

    public static function getDistrictNameHelper($districtId, $provinceId) {
        $result = "";
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=" . $provinceId;
        $response = Http::withHeaders(['token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897'])->get($endpoint);

        // echo "<pre>";
        // print_r($response->status() );
        // die();
        if ( $response->status() == 200) {
            $content    = json_decode($response->body());
            foreach ($content->data as $district) {
                if ($district->DistrictID == $districtId) {
                    $result = $district->DistrictName;
                    break;
                }
            }
        }

        return $result;
    }

    public static function getProvinceNameHelper($provinceId) {
        $result = "";
        $endpoint = "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province";
        $response = Http::withHeaders([
            'token' => 'c0ddcdf9-df81-11ee-b1d4-92b443b7a897',
        ])->post($endpoint);
    
        if ( $response->status() == 200) {
            $content    = json_decode($response->body());
            foreach ($content->data as $province) {
                if ($province->ProvinceID == $provinceId) {
                    $result = $province->ProvinceName;
                    break;
                }
            }
        }

        return $result;
    }

    public static function getListCategory()
    {
    	return CategoryProduct::get();
    }

    public static function getBaseUrl(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function isMappingShippByOrderId($orderId) {
        $shippOrder = ShippingOrder::whereOrderId($orderId)->first();
        // dd($shippOrder);
        if ($shippOrder) {
            return $shippOrder;
        }
        return false;
    }

    public static function getStatusOrderShip($status, $type) {
        $rs = '';
        if ($type == 'GHN') {
            switch ($status) {
                case 'delivered' :
                    $rs = 'Giao hàng thành công';
                    break;
                default: 
                    $rs = 'Giao hàng thất bại';
                    break;
            }
        }

        return $rs;
    }

    public static function getDaysOfWek($dateString) {
        $dateToTime = strtotime($dateString);
        $dayOfWeekNumber = date('N', $dateToTime);
        $thuArray = array(
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
            7 => 'Chủ Nhật'
        );

        return $thuArray[$dayOfWeekNumber];
    }

    public static function getDateFromStringGHN($dateString) {
        $dateToTime = strtotime($dateString);
        return date('d/m/Y', $dateToTime);
    }

    public static function getUserByID($id) {
        return User::find($id);
    }

    public static function getListSale() {
        $arrQuery = [1];
        return  User::where('status', 1)
            ->where(function($query) use ($arrQuery) {
            foreach ($arrQuery as $term) {
                $query->orWhere('is_sale', $term)->orWhere('is_cskh', $term);
            }
        });
    }

    public static function getListCall() {
        return Call::where('status', 1);
    }

    public static function getListProductByPermisson($role) {
        $a = new ProductController();
        return $a->getListProductByPermisson($role);
    }

    public static function checkFertilizer($listProduct) {
        $listProduct = json_decode($listProduct);

        foreach ($listProduct as $product) {
            $product = Product::find($product->id);
            if ($product && $product->roles == 3) {
                return true;
            }
        }

        return false;
    }

    // public static function checkFertilizer($userId) {
    //     $result = false;
    //     $user   = User::find($userId);
    //     if ($user) {
    //         $role = json_decode($user->role);

    //         //toàn quyền admin hoặc phân bón
    //         if (in_array(1, $role) || in_array(3, $role)) {
    //             $result = true;
    //         }
                
    //     }

    //     return $result;
    // }

    public static function checkOrderSaleCare($id) {
        $saleCare = SaleCare::where('id_order', $id)->get()->first();
        
        $str = json_encode($saleCare);
        Log::channel('new')->info('id: '. $id . '$saleCare: ' . $str );
        if ($saleCare) {
            return true;
        }
        return false;
    }

    
    public static function checkOrderSaleCarebyPhonePage($phone, $pageId, $mId, &$assign, &$is_duplicate) 
    {
        if (!$mId || !$phone || $phone == '0961161760' || $phone == '961161760' || $phone == '0372625799') {
            return false;
        } 
        
        $saleCares = SaleCare::where('old_customer', 0)->where('phone', $phone)
            ->where('page_id', $pageId)->orderBy('id', 'asc')->get();
            
        if ($saleCares->count() == 0) {
            return true;
        }
    
        foreach ($saleCares as $item) {
            if ($item->m_id == $mId) {
                return false;
            }
        }
       
        /** trùng sđt: set lại assign sale trước đó và set trùng data */
        $assign = $saleCares[0]->assign_user;
        $is_duplicate = true;
        
        return true;
    }

    public static function getStatusGHNtoKho($id) {
        $arr = [];
        $arr = [
            'delivered' => 3,
            
        ];
        return $arr;
    }

    /**
     * return string 
     */
    public static function getListProductByOrderId($id) 
    {
        $text = '';
        $order = Orders::find($id); 
        if($order) {
            foreach (json_decode($order->id_product) as $item) {
                if ($text != '') {
                    $text .= ', ';
                }
                $product    = Product::find($item->id);
                $text   .= "\n$product->name: $item->val";
            }
        }
            
        return $text;
    }

    public static function getConfigTelegram() {
        return Telegram::first();
    }

    public static function getConfigPanCake() {
        return Pancake::first();
    }

    
    public static function checkProductsOfCategory($products, $idCategory) {
        foreach ($products as $product) {
            $productModel = Product::find($product->id);
            // dd($productModel->category_id);
            if ($productModel && $productModel->category_id == $idCategory) {
                return true;
            }
        }
        return false;
    }

    /**
     * next_assign chỉ định sale
     *  = 0 sẵn sàn chỉ định
     *  = 1 chỉ định -> người được chọn
     *  = 2 người chỉ định vừa gọi
     */
    public static function getAssignSale()
    {
        /**lấy user chỉ định bằng 1 */
        $sale = User::where('status', 1)->where('is_sale', 1)->where('is_receive_data', 1)->where('next_assign', 1)->first();

        /**ko có user nào đc chỉ định thì lấy user đầu tiên, điều kiện tất cả user đều = 0 */
        if (!$sale) {
            $sale = User::where('status', 1)->where('is_sale', 1)->where('is_receive_data', 1)->orderBy('id', 'DESC')->first();
        }

        /**set user chỉ định đã được lấy, set = 2 = đã dùng trong lần gọi này*/
        $sale->next_assign = 2;
        $sale->save();

        /** chỉ định người tiếp theo: lấy toàn bộ những người hợp lệ trừ user vừa set = 2 ở trên (hợp lệ = 0)
         * và lấy user đầu tiên trong danh sách
         * trường hợp ko tìm đc ai (tất cả đều bằng 2) -> reset all về bằng 0 - sẵn sàng assign lần tiếp
         */
        $nextAssign = User::where('status', 1)->where('is_receive_data', 1)->where('is_sale', 1)->where('id', '!=', $sale->id)
            ->where('next_assign', 0)->orderBy('id', 'DESC')->first();
                    
        if ($nextAssign) {
            $nextAssign->next_assign = 1;
            $nextAssign->save();
        } else {
            User::where('status', 1)->where('is_receive_data', 1)->where('is_sale', 1)->update(['next_assign' => 0]);
        }

        return $sale;
    }
    
    public static function getConfigLadiPage() 
    {
        return LadiPage::first();
    }

    public static function isOldDataLadi($phone, $link, &$assign) 
    {
        $phone = trim($phone);
        $saleCare = SaleCare::where('phone', $phone)->where('page_link', 'like', '%' . $link . '%')->first();

        if ($saleCare) {
            $assign = $saleCare->assign_user;
            return true;
        }
       
        return false;
    }

    public static function getAssignCSKH()
    {
        /**lấy user chỉ định bằng 1 */
        $sale = User::where('status', 1)->where('is_CSKH', 1)->where('is_receive_data', 1)->where('next_assign', 1)->first();

        /**ko có user nào đc chỉ định thì lấy user đầu tiên, điều kiện tất cả user đều = 0 */
        if (!$sale) {
            $sale = User::where('status', 1)->where('is_CSKH', 1)->where('is_receive_data', 1)->orderBy('id', 'DESC')->first();
        }

        if (!$sale) {
            return ;
        }
        
        /**set user chỉ định đã được lấy, set = 2 = đã dùng trong lần gọi này*/
        $sale->next_assign = 2;
        $sale->save();

        /** chỉ định người tiếp theo: lấy toàn bộ những người hợp lệ trừ user vừa set = 2 ở trên (hợp lệ = 0)
         * và lấy user đầu tiên trong danh sách
         * trường hợp ko tìm đc ai (tất cả đều bằng 2) -> reset all về bằng 0 - sẵn sàng assign lần tiếp
         */
        $nextAssign = User::where('status', 1)->where('is_receive_data', 1)->where('is_CSKH', 1)->where('id', '!=', $sale->id)
            ->where('next_assign', 0)->orderBy('id', 'DESC')->first();
                    
        if ($nextAssign) {
            $nextAssign->next_assign = 1;
            $nextAssign->save();
        } else {
            User::where('status', 1)->where('is_receive_data', 1)->where('is_CSKH', 1)->update(['next_assign' => 0]);
        }

        return $sale;
    }
    
    public static function isLeadSale($role) 
    {
        $arr = json_decode($role);

        /** 4: leadsale */
        if (in_array(4, $arr)) {
            return true;
        }

        return false;
    }

    public static function getSaleById($id)
    {
        return User::find($id);
    }

    public static function stringToNumberPrice($number_with_commas)
    {
        // // Original number as a string with commas
        // $number_with_commas = "123,456,789";

        // Remove commas
        $number_without_commas = str_replace(",", "", $number_with_commas);

        // Convert the string to an integer or float
        $number = (int)$number_without_commas;

        // Display the result
        return $number;
    }

    public static function getSumCustomer($dataSale) 
    {
        $totalSum = $avgSum = $newContact = $newOrder = $newRate = $newProduct = $newTotal = $oldAvg = $oldTotal = $oldProduct = $oldRate = $newAvg = $oldContact = $oldOrder= 0;
        $result = [];
        $sumNewCustomer = $sumOldCustomer = [
            'contact' => 0,
            'order' => 0,
            'rate' => 0,
            'product' => 0,
            'total' => 0,
            'avg' => 0,
        ];

        if (isset($dataSale)) {
            // dd($dataSale);
            foreach ($dataSale as $data) {
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die();
                if (isset($data['new_customer'])) {
                    $newContact += $data['new_customer']['contact'];
                    $newOrder += $data['new_customer']['order'];
                    $newProduct += $data['new_customer']['product'];
                    $newTotal += ($data['new_customer']['total']);
                }
                if (isset($data['old_customer'])) {
                    $oldContact += $data['old_customer']['contact'];
                    $oldOrder += $data['old_customer']['order'];
                    $oldRate += $data['old_customer']['rate'];
                    $oldProduct += $data['old_customer']['total'];
                    $oldTotal += ($data['old_customer']['total']);
                }
            }

           
            $sumNewCustomer['contact'] = $newContact;
            $sumNewCustomer['order'] = $newOrder;
            if ($newContact > 0) {
            $newRate = $newOrder / $newContact * 100;
            $sumNewCustomer['rate'] = round($newRate, 2);
            }

            $sumNewCustomer['product'] = $newProduct;
            $sumNewCustomer['total'] = round($newTotal, 0);
            $sumNewCustomer['avg'] = round((($newOrder != 0) ? $newTotal/$newOrder : 0), 0);

            $sumOldCustomer['contact'] = $oldContact;
            $sumOldCustomer['order'] = $oldOrder;
            if ($oldContact > 0) {
                $oldRate = $oldOrder / $oldContact * 100;
                $sumOldCustomer['rate'] = round($oldRate, 2);
            }

            $sumOldCustomer['product'] = $oldProduct;
            $sumOldCustomer['total'] = round($oldTotal, 0);
            $sumOldCustomer['avg'] = round((($oldOrder != 0) ? $oldTotal/$oldOrder : 0), 0);

            $totalSum = $oldTotal + $newTotal;
            if ($oldOrder + $newOrder) {
                $avgSum = round(($totalSum / ($oldOrder + $newOrder)), 0);
            }

            $result['sum_new_customer'] = $sumNewCustomer;
            $result['sum_old_customer'] = $sumOldCustomer;
            $result['summary'] = [
                'total' => $totalSum,
                'avg' => $avgSum,
            ];
        }

        return $result;
    }

    public static function checkTypeOrderbyPhone($phone, $type)
    {
        $rs = false;
        $order = Orders::where('phone', 'like', '%' . $phone . '%')->orderBy('id', 'desc')->first();
        if (!$order) {
            return $rs;
        }

        $assign_user = $order->assign_user;
        $sale = Helper::getSaleById($assign_user);
        if (!$sale ) {
            return $rs;
        }

        $routeName = \Request::route();
        // dd($routeName->getName());
        /**data nóng */
        if ($type == 0 && !$sale->is_CSKH && $sale->is_sale) {
            $rs = true;
        } else if ($type == 1 && $sale->is_CSKH && !$sale->is_sale) {
            /**CSKH */
            $rs = true;
        } else if ($type = 999 && $routeName->getName() != 'home' && $routeName->getName() != 'filter-total-digital') {

            /** lấy tất cả */
            $rs = true;
        }
        // dd($rs);
        return $rs;
    }

        /**
     * input:
     *  +84973409613
     *  84973409613
     *  0973409613
     *  973409613
     * 
     * output: 0973409613
     */
    public static function getCustomPhoneNum($phone)
    {
        $length = strlen($phone);
        $pos = $length - 9;
        return '0' . substr($phone, $pos);
    }

    public static function getListDigital()
    {
        return  User::where('status', 1)->where('is_digital', 1);
    }

    public static function getSrcById($id)
    {
        return  SrcPage::find($id);
    }
}