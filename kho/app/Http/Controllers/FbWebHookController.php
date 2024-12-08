<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FbWebHookController extends Controller
{
    //
    public function webhook(Request $req) 
    {
        $data = $req->all();
 
        Log::channel('a')->info('api run webhook');
        Log::channel('a')->info($data);
        if ($data) {
            sleep(5);
            $this->callDataPc($data);
        }
    }

    // Hàm gửi tin nhắn sử dụng Facebook Send API
    function sendTextMessage($senderPsid, $message)
    {
        global $PAGE_ACCESS_TOKEN;
        $url = 'https://graph.facebook.com/v13.0/me/messages?access_token=' . $PAGE_ACCESS_TOKEN;
    
        $ch = curl_init($url);
    
        $jsonData = [
            'recipient' => ['id' => $senderPsid],
            'message' => ['text' => $message]
        ];
    
        $jsonDataEncoded = json_encode($jsonData);
    
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
        curl_close($ch);
    
        if ($result) {
            echo "Tin nhắn đã được gửi!";
        } else {
            echo "Không thể gửi tin nhắn.";
        }
    }

    public function getUserName($userId, $access_token) {
        // dd($userId);
        $url = "https://graph.facebook.com/$userId?fields=first_name,last_name&access_token=$access_token";
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $user = json_decode($response, true);
        // if ()
        // dd( $user);
        // Trả về tên đầy đủ hoặc chỉ tên riêng
        return $user['last_name'] . ' ' . $user['first_name'];
    }

    public function saveDataWebhookFB($group, $pageId, $phone, $name, $mId, $messages, $pageSrc)
    {
        $assgin_user = 0;
        $is_duplicate = false;
        $phone = Helper::getCustomPhoneNum($phone);
        $hasOldOrder = 0;
        $checkSaleCareOld = Helper::checkOrderSaleCarebyPhoneV4($phone, $mId, $is_duplicate, $assgin_user, $group, $hasOldOrder);

        $chatId = $group->tele_hot_data;
        $linkPage = $pageSrc->link;
        $srcId = $pageSrc->id;
        $namePage = $pageSrc->name;
        Log::channel('a')->info('$namePage' . $namePage);
        if ($checkSaleCareOld) {  
            if ($assgin_user == 0) {
                // dd($group);
                $assignSale = Helper::getAssignSaleByGroup($group);
                if (!$assignSale) {
                    return;
                }
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
                'page_id'   => $pageId,
                'text'      => 'Page ' . $namePage,
                'chat_id'   => $chatId,
                'm_id'      => $mId,
                'assgin'    => $assgin_user,
                'is_duplicate' => $is_duplicate,
                'group_id'  => $group->id,
                'has_old_order'  => $hasOldOrder,
                'src_id' => $srcId,
            ];

            $request = new \Illuminate\Http\Request();
            $request->replace($data);
            $sale->save($request);
        }
    }


    // Xử lý sự kiện webhook
    public function handle($data)
    {
        Log::channel('new')->info('run webhook googogo ');
        Log::channel('new')->info(($data) ? 'true' : 'false');
          
        if ($data) {
            $phone = $data['phone'];
            $receivedMessage = $data['receivedMessage'];
            $mid = $data['mid'];
            $name = $data['name'];
            $pageId = $data['pageId'];
            $group = Helper::getGroupByPageId($pageId);

            if (!$group) {
                return;
            }
            
            $pageSrc = Helper::getPageSrcByPageId($pageId);
            Log::channel('new')->info( $pageSrc ? 'có pageSrc' : 'nono');
            if (!$pageSrc) {
                return;
            }

            $tokenPage = $pageSrc->token;
            if (!$name) {
                $name = "Anh 3";
            }

            $this->saveDataWebhookFB($group, $pageId, $phone, $name, $mid, $receivedMessage, $pageSrc);
        }

        return response('Sự kiện đã nhận', 200);
    }

    public function callDataPc($data)
    {
        // $data = array (
        //     'phone' => '0973409613',
        //     'receivedMessage' => '0973409613 go',
        //     'mid' => 'm_3RWA8svAbHssJhEYb3IrlRSX13JMTib20xEA6BqKI-0Zsa9a4XJoKC3Qe_llMV-tF_q9LRDNFhNDPZIUraidmQ',
        //     'name' => 'Dat Dinh',
        //     'pageId' => '381180601741468'
        // );
    Log::channel('a')->info('run callDataPc');
        $pageId =  $data['pageId'];
        $phone =  $data['phone'];
        $mid =  $data['mid'];
        $receivedMessage = $data['receivedMessage'];
        
        $str  = 'pageId: ' . $pageId . '<br>';
        $str  .= 'phone: ' . $phone . '<br>';
        $str  .= 'mid: ' . $mid . '<br>';
        $str  .= 'receivedMessage: ' . $receivedMessage . '<br>';
        Log::channel('a')->info($str);
        Log::channel('a')->info('run callDataPc');
        
        $group = Helper::getGroupByPageId($pageId);
             
        if (!$group) {
            Log::channel('a')->info('no group');
            return;
        }
        
        $pageSrc = Helper::getPageSrcByPageId($pageId);
        if (!$pageSrc) {
                        Log::channel('a')->info('no pageSrc');
            return;
        }

        $token = $pageSrc->token;
        $endpoint = "https://pancake.vn/api/v1/pages/$pageId/conversations/";
        $endpoint .= "search?q=$phone&access_token=$token";
        $responseJson = file_get_contents($endpoint);
        $response = json_decode($responseJson, true);

        if (!$response) {
            Log::channel('a')->info('no response');
            return false;
        }

        Log::channel('a')->info($response);
        if (!$response['success'] || !$response['conversations']) {
            Log::channel('a')->info('success repssont is not');
             Log::channel('a')->info($response);
            return false;
        }

        $data = $response['conversations'][0];
        $name = $data['customers'][0]['name'];
        
        Log::channel('a')->info('name: ' . $name);
        $this->saveDataWebhookFB($group, $pageId, $phone, $name, $mid, $receivedMessage, $pageSrc);
    }
}