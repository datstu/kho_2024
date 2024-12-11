<?php


namespace App\Helpers;
use App\CategoryProduct;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\ShippingOrder;
use App\Models\User;
use App\Models\Call;
use App\Http\Controllers\ProductController;
use App\Models\Group;
use App\Models\Orders;
use App\Models\SaleCare;
use Illuminate\Support\Facades\Log;
use App\Models\Telegram;
use App\Models\Pancake;
use App\Models\LadiPage;
use PHPUnit\TextUI\Help;
use App\Models\SrcPage;
use Illuminate\Support\Facades\DB;

setlocale(LC_TIME, 'vi_VN.utf8');

class Helper
{
    /**
     * 
     * 0: khách mới
     * 1: khách cũ
     * @return int
     */
    public static function checkTypeCustomer($phone, $group)
    {
        $type = 0;
        // dd($phone);
        $saleCare = SaleCare::where('phone', $phone)->where('group_id', $group->id)
            ->where('old_customer', 1)
            ->first();
        // dd($saleCare);
        if ($saleCare) {
            $type = 1;
        }
        return $type;
    }

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
    public static function checkOrderSaleCarebyPhoneV2($phone, $mId, &$is_duplicate, &$assign) 
    {
        if (!$mId || !$phone || $phone == '0986987791' || $phone == '986987791' || $phone == '0961161760') {
            return false;
        } 
        
        // $saleCares = SaleCare::where('old_customer', 0)->where('phone', $phone)->orderBy('id', 'asc')->get();
        $saleCares = SaleCare::where('phone', $phone)->orderBy('id', 'asc')->get();

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

    public static function checkOrderSaleCarebyPhoneV3($phone, $mId, &$is_duplicate, &$assign, $group, &$has_old_order) 
    {
        if (!$mId || !$phone || $phone == '0986987791' || $phone == '986987791' || $phone == '0961161760') {
            return false;
        } 
        
        // $saleCares = SaleCare::where('old_customer', 0)->where('phone', $phone)->orderBy('id', 'asc')->get();
        $saleCares = SaleCare::where('phone', $phone)->orderBy('id', 'asc')->get();

        if ($saleCares->count() == 0) {
            return true;
        }
    
        foreach ($saleCares as $item) {
            if ($item->m_id == $mId) {
                return false;
            }
        }

        $is_duplicate = true;

        
        /** 
         * trùng sđt: set lại assign sale trước đó và set trùng data 
         * set lại sale cskh nếu data đã chốt đơn
         * 
         * có trùng nhưng khác gr thì ko set sale cũ -> sẽ chia cho sale gr mới
        */ 
        $saleCares = SaleCare::where('phone', $phone)
            ->where('group_id', $group->id)->orderBy('id', 'asc')->get();

        // dd(Helper::isOldCustomer($phone, $group->id));
        if ($isSaleCareCSKH = Helper::isOldCustomer($phone, $group->id)) {
            $assign = $isSaleCareCSKH->assign_user;
            $has_old_order = 1;
        } else if ($saleCares->count() > 0 && $saleCares[0]->group_id == $group->id) {
            $assign = $saleCares[0]->assign_user;
        }

        return true;
    }

    public static function checkOrderSaleCarebyPhoneV4($phone, $mId, &$is_duplicate, &$assign, $group, &$has_old_order) 
    {
        if (!$mId || !$phone || $phone == '0986987791' || $phone == '986987791' || $phone == '0961161760') {
            return false;
        } 
        
        // $saleCares = SaleCare::where('old_customer', 0)->where('phone', $phone)->orderBy('id', 'asc')->get();
        $saleCares = SaleCare::where('phone', $phone)->orderBy('id', 'asc')->get();

        if ($saleCares->count() == 0) {
            return true;
        }
    
        foreach ($saleCares as $item) {
            if ($item->m_id == $mId) {
                return false;
            }
        }

        /** trùng sđt: set lại assign sale trước đó và set trùng data */
        $userAssign = $saleCares[0]->user;
        if (!$userAssign->is_receive_data || !$userAssign->status) {
            $assign = Helper::getAssignSaleByGroup($group)->id_user;
        } else {
            $assign = $saleCares[0]->assign_user;
        }

        $is_duplicate = true;

        return true;
    }

    public static function isOldCustomer($phone, $group_id)
    {
        /*khách hàng có đơn và đã hoàn tất
        * đã có data đổ về cskh -> old_customer = 1 
        */
        $saleCSKH = SaleCare::where('phone', $phone)
            ->where('old_customer', 1)
            ->where('group_id', $group_id)
            ->first();

        if ($saleCSKH) {
            return $saleCSKH;
        }
    
        return false;
    }

    public static function checkOrderSaleCarebyPhonePageTricho($phone, $mId, &$is_duplicate, &$assign) 
    {
        if (!$mId || !$phone || $phone == '0986987791' || $phone == '986987791') {
            return false;
        } 
        
        $saleCares = SaleCare::where('old_customer', 0)->where('phone', $phone)->orderBy('id', 'asc')->get();
            
        if ($saleCares->count() == 0) {
            return true;
        }
    
        foreach ($saleCares as $item) {
            if ($item->m_id == $mId) {
                return false;
            }
        }
        // dd($saleCares[0]->assign_user);
        /** trùng sđt: set lại assign sale trước đó và set trùng data */
        $assign = $saleCares[0]->assign_user;
        $is_duplicate = true;
        
        return true;
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

    public static function isOldDataLadi($phone, &$assign, $group, &$has_old_order, &$is_duplicate) 
    {
        $phone = trim($phone);
        $saleCare = SaleCare::where('phone', $phone)->first();

        if ($saleCare) {
            $is_duplicate = 1;
            $userAssign = $saleCare->assign_user;
            $user = User::find($userAssign);
           
            $saleCareByGroup = SaleCare::where('phone', $phone)
                ->where('group_id', $group->id)->first();
                
            if ($saleCareByGroup && $isSaleCareCSKH = Helper::isOldCustomer($phone, $group->id)) {
                $assign = $isSaleCareCSKH->assign_user;
                $has_old_order = 1;
            } else if ($saleCareByGroup && $user && $user->status == 1) {
                $assign = $userAssign;
            }

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
    
    public static function isMkt($user) 
    {
        if ($user->is_digital) {
            return true;
        }

        return false;
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
                    $oldProduct += $data['old_customer']['product'];
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

    public static function getSaleTricho()
    {
        $trichoTeam = config('tricchoTeam.list_sale');
        $saleCare = SaleCare::where('group_id', 'tricho')->where('old_customer', 0)->orderBy('id', 'desc')->first();
        // dd($saleCare);
        if ($saleCare) {
            // dd($trichoTeam );
            if ($saleCare->user->name == 'sale.ly' && $trichoTeam['thu']['status']) {
                return User::where('name', 'sale')->first();
            } else if ($saleCare->user->name == 'sale' && $trichoTeam['hiep']['status']) {
                return User::where('name', 'sale.hiep')->first();
            } else if ($saleCare->user->name == 'sale.hiep' && $trichoTeam['ly']['status']) {
                return User::where('name', 'sale.ly')->first();
            }
        } else {
            return User::where('name', 'sale')->first();
        }

        return  User::where('name', 'sale.hiep')->first();
    }

    /**
     * return false nếu list sp truyền vào chỉ có paulo
     */
    public static function hasAllPaulo($listProduct)
    {
        $listProduct = json_decode($listProduct);

        foreach ($listProduct as $product) {
            $product = Product::find($product->id);
            if ($product && $product->roles != 2) {
                return true;
            }
        }

        return false;
    }

    public static function getListProduct()
    {
        return Product::where('status', 1);
    }

    public static function getListSrc()
    {
        return SrcPage::all();
    }

    /**
     * next_assign chỉ định sale
     *  = 0 sẵn sàn chỉ định
     *  = 1 chỉ định -> người được chọn
     *  = 2 người chỉ định vừa gọi
     */
    public static function getAssignSaleByGroup($group)
    {
        $saleOfGroup = $group->sales->where('type_sale', 1);

        /**lấy user chỉ định bằng 1 trong list sale của group*/
        $saleItem = Helper::getSaleReady($saleOfGroup);

        
        if (!$saleItem) {
            $saleItem = Helper::getSaleFirst($saleOfGroup);  
        }

        /**set user chỉ định đã được lấy, set = 2 = đã dùng trong lần gọi này*/
        $saleItem->next_assign_sale = 2;
        $saleItem->save();

        ///
        /** còn trường hợp ko có sale nào trong ca: có data mới nhưng sale đều off (ko chia data) - chưa xử lý 
         * = auto luôn có 1 sale trực ca/chia data*/
        ////

        Helper::setSaleNextAssign($saleOfGroup, $saleItem);

        return $saleItem;
    }

    /** chỉ định người tiếp theo: lấy toàn bộ những người hợp lệ trừ user vừa set = 2 ở trên (hợp lệ = 0)
    * và lấy user đầu tiên trong danh sách
    * trường hợp ko tìm đc ai (tất cả đều bằng 2) -> reset all về bằng 0 - sẵn sàng assign lần tiếp
    */
    public static function setSaleNextAssign($sales, $currentSale, $typeSale = 'hot') 
    { 
        $nextSale = null;
        if ($typeSale == 'hot') {
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_sale == 0 && $item->user->id != $currentSale->id) {
                    $item->next_assign_sale = 1;
                    $item->save();
                    $nextSale = $item->user;
                    break;
                }
            }
    
            if (!$nextSale) {
                foreach ($sales as $item) {
                    if ($item->user->status) {
                        $item->next_assign_sale = 0;
                        $item->save();
                    }
                }
            }
        } else {
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_cskh == 0 && $item->user->id != $currentSale->id) {
                    $item->next_assign_cskh = 1;
                    $item->save();
                    $nextSale = $item->user;
                    break;
                }
            }
    
            if (!$nextSale) {
                foreach ($sales as $item) {
                    if ($item->user->status && $item) {
                        $item->next_assign_cskh = 0;
                        $item->save();
                    }
                }
            }
        }
       
    }
    
    /**ko có user nào đc chỉ định thì lấy user đầu tiên, điều kiện tất cả user đều = 0 */
    public static function getSaleFirst($sales, $typeSale = 'hot') 
    {
        if ($typeSale == 'hot') {
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_sale == 0) {
                    return $item;
                }
            }
        } else {
           
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_cskh == 0) {
                    return $item;
                }
            }
        }
        
    }

    public static function refeshSaleAssign($sales, $typeSale)
    {
        foreach ($sales as $sale) {
            if ($sale->$typeSale == 2) {
                return $sale;
            }
        }
    }

    /**
     * active + nhận data + được chỉ định => return user
     */
    public static function getSaleReady($sales, $typeAssgin = 'hot') 
    {
        $flag = false;
        if ($typeAssgin == 'hot') {
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_sale == 1) {
                    return $item;
                }
            }

            if (!$flag) {
                return Helper::refeshSaleAssign($sales, 'next_assign_sale');
            }
        } else {
            foreach ($sales as $item) {
                if ($item->user->status && $item->next_assign_cskh == 1) {
                    return $item;
                }
            }

            if (!$flag) {
                return Helper::refeshSaleAssign($sales, 'next_assign_cskh');
            }
        }


    }

    public static function getAssignCskhByGroup($group)
    {
        $saleOfGroup = $group->sales->where('type_sale', 2);
        // dd($saleOfGroup);
        /**lấy user chỉ định bằng 1 trong list sale của group*/
        $saleItem = Helper::getSaleReady($saleOfGroup, 'cskh');
        // dd($saleItem);
        if (!$saleItem) {
            // dd('hi');
            $saleItem = Helper::getSaleFirst($saleOfGroup, 'cskh');  
        }
       
       
        /**set user chỉ định đã được lấy, set = 2 = đã dùng trong lần gọi này*/
        $saleItem->next_assign_cskh = 2;
        $saleItem->save();

        ///
        /** còn trường hợp ko có sale nào trong ca: có data mới nhưng sale đều off (ko chia data) - chưa xử lý 
         * = auto luôn có 1 sale trực ca/chia data*/
        ////

        
        Helper::setSaleNextAssign($saleOfGroup, $saleItem, 'cskh');
        return $saleItem;
    }

    public static function getGroupByPageId($pageId)
    {
        // dd($pageId);
        $page = DB::table('src_page')
            ->select('group_work.id')
            ->join('group_work', 'group_work.id', '=', 'src_page.id_group')
            ->where(['src_page.id_page' => $pageId, 'group_work.status' => 1])
            ->first();

        if ($page) {
            return Group::find($page->id);
        }
    }

    public static function getPageSrcByPageId($pageId)
    {
        return SrcPage::where('id_page', $pageId)->first();
    }

    public static function getListLeadSale()
    {
        $list = User::where('status', 1);
        $result = [];

        /** lấy ra danh sách lead sale */
        foreach ($list->get() as $user) {
            if (in_array(4, json_decode($user->role, true))) {
                $result[] = $user;
            }
        }
        return $result;
    }

    public static function getGroupByLinkLadi($link)
    {
        // dd($link);
        $page = DB::table('src_page')
            ->select('group_work.id')
            ->join('group_work', 'group_work.id', '=', 'src_page.id_group')
            // ->where(['src_page.link' => $link, 'group_work.status' => 1])
            ->where('src_page.link', 'like', '%' . $link . '%')
            ->where('group_work.status' , 1)
            ->first();

        if ($page) {
            return Group::find($page->id);
        }
    }

    public static function listCallByTypeTN($id)
    {
        return  Call::where('if_call', $id)->where('status', 1)->get();
    }

    public static function getTypeCSKH($order)
    {
        $type = 8; //hardcode cskh
        $listProduct = json_decode($order->id_product, true);
        
        if ($listProduct) {
            foreach ($listProduct as $product) {

                /** hardcode set cskh tricho */
                if ($product['id'] == 56) {
                    $type = 17;
                    break;
                }
            }
        }

        return $type;
    }

    public static function getListMktUser($user = null)
    {
        $list = User::where('status', 1)->where('is_digital', 1)
            // ->orWhere('role', 'like', '%' . '1' . '%')
            ->orderBy('id','DESC');

        if ($user && !isFullAccess($user->role)) {
            // dd($list->get());
            $list = $list->where('id', $user->id);
           
        }    

       
        return $list;
    }

    public static function getListGroup()
    {
        $list = Group::where('status', 1)->orderBy('id','DESC');;
        return $list;
    }
}