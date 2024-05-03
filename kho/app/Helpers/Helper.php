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
        return User::where('status', 1)->where('is_sale', 1);
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
}