<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;

class FbWebHookController extends Controller
{
    //
    public function webhook(Request $req) 
    {
        /*
        $myVertifyToken = 'dat1shot';
    
        $challenge = $_REQUEST['hub_challenge'];
        $verifyToken = $_REQUEST['hub_verify_token'];
        
        if ($myVertifyToken === $verifyToken) {
            echo $challenge;
            exit;
        }
        
        $PAGE_ACCESS_TOKEN = 'EAAYIjZA0yQwQBOzli80moZAwZCXZAZBWz8AbCN6g8UgUzXxbHAUrteeGHZCH34FWHVhaiHiDnzEZAP3pZCR8Uftw5Kf28iQJVA1lUahTMj2J2ZCaKzSG7kqZCOyw2pZAdNU30K8j3BWqoXSSuCQmqbLrzPnZAD7ZAJvwZCOEAlOXlaUWI3NiVpNTRc9VaqZA2yFNx9vNtwTqgZDZD';
        
        $response = file_get_contents("php://input");
        file_put_contents("text.txt", $response)
        */

        $inputRespone = '{
            "object": "page",
            "entry": [
                {
                "time": 1721793454761,
                "id": "326283683897191",
                "messaging": [
                    {
                    "sender": {
                        "id": "7773592689420079"
                    },
                    "recipient": {
                        "id": "326283683897191"
                    },
                    "timestamp": 1721793454545,
                    "delivery": {
                        "mids": [
                        "m_iFMcWt73jCXzDrXvMdvThk5hkVLiPx0Ms1Xp7nJp3YU1l109QqNAM4dngd5A5yOJYO3G02Qk_WUh75j1Nw8ehg"
                        ],
                        "watermark": 1721793452504
                    },
                    "message": {
                        "mid": "test_message_id",
                        "text": "0369504150 gọi cho toi",
                        "commands": [
                        {
                            "name": "command123"
                        },
                        {
                            "name": "command456"
                        }
                        ]
                    }
                    }
                ]
                }
            ]
            }';

        // Xử lý sự kiện từ Webhook
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Lấy nội dung JSON từ request
            // $input = json_decode(file_get_contents('php://input'), true);
            $input = json_decode($inputRespone, true);
            // dd($input);
            // Kiểm tra đối tượng nhận có phải là trang
            if ($input['object'] === 'page') {
                foreach ($input['entry'] as $entry) {
                    $webhookEvent = $entry['messaging'][0];
        
                    // Lấy ID người gửi
                    $senderPsid = $webhookEvent['sender']['id'];
        
                    // Kiểm tra nếu có tin nhắn văn bản
                    if (isset($webhookEvent['message']['text'])) {
                        $receivedMessage = $webhookEvent['message']['text'];
                        $mid = $webhookEvent['message']['mid'];

                        // Kiểm tra nội dung tin nhắn có chứa số điện thoại không
                        $phoneRegex = '/(?:\D|^)(\d{10,15})(?=\D|$)/'; // Biểu thức regex cho số điện thoại
                        if (preg_match_all($phoneRegex, $receivedMessage, $matches)) {
                            $phoneNumbers = $matches[1]; // Mảng chứa các số điện thoại tìm thấy
                            // Xử lý số điện thoại (lưu trữ, gửi thông báo, v.v.)
                            $pageId = $entry['id'];
                            $group = Helper::getGroupByPageId($pageId);
                            if (!$group) {
                                break;
                            }
                            
                            $pageSrc = Helper::getPageSrcByPageId($pageId);
                            if (!$pageSrc) {
                                break;
                            }

                            $tokenPage = $pageSrc->token;
                            $name = $this->getUserName($senderPsid, $tokenPage);
                            if (!$name) {
                                break;
                            }
                            // $accessToken = Helper::getAccessTokenByPageId($pageId);
                            // if (!$accessToken) {
                            //     break;
                            // }

                            foreach ($phoneNumbers as $phoneNumber) {
                                $this->saveDataWebhookFB($group, $pageId, $phoneNumber, $name, $mid, $receivedMessage, $pageSrc);
                           
                                // Ví dụ: gửi tin nhắn phản hồi với số điện thoại nhận được
                                // $response = "Chúng tôi đã nhận được số điện thoại của bạn: $phoneNumber";
                                // sendTextMessage($senderPsid, $response);
                            }
                        }
                        //  else {
                        //     // Trả lời khi không tìm thấy số điện thoại
                        //     $response = "Không tìm thấy số điện thoại trong tin nhắn của bạn.";
                        //     sendTextMessage($senderPsid, $response);
                        // }
                    }
                }
                http_response_code(200);
                echo 'EVENT_RECEIVED';
            } else {
                http_response_code(404);
                echo 'Not Found';
            }
            exit;
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
        $url = "https://graph.facebook.com/$userId?fields=first_name,last_name&access_token=$access_token";
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $user = json_decode($response, true);
    
        // Trả về tên đầy đủ hoặc chỉ tên riêng
        return $user['last_name'] . ' ' . $user['first_name'];
    }

    public function saveDataWebhookFB($group, $pageId, $phone, $name, $mId, $messages, $pageSrc)
    {
        // foreach ($data as $item) {
        // $recentPhoneNumbers = $phone;
        // $mId      = $recentPhoneNumbers->m_id;
        // $phone    = isset($recentPhoneNumbers) ? $recentPhoneNumbers->phone_number : '';
        // $name     = isset($item->customers[0]) ? $item->customers[0]->name : '';
        // $messages = isset($recentPhoneNumbers) ? $recentPhoneNumbers->m_content : '';

        $assgin_user = 0;
        $is_duplicate = false;
        $phone = Helper::getCustomPhoneNum($phone);
        $checkSaleCareOld = Helper::checkOrderSaleCarebyPhonePage($phone, $pageId, $mId, $assgin_user, $is_duplicate);

        $chatId = $group->tele_hot_data;
        // dd($chatId);
        $linkPage = $pageSrc->link;
        $namePage = $pageSrc->name;
        if ($checkSaleCareOld) {  
            if ($assgin_user == 0 && $group->sales) {
                // dd($group);
                $assignSale = Helper::getAssignSaleByGroup($group);
                if (!$assignSale) {
                    return;
                }
                $assgin_user = $assignSale->id_user;
            }

            // dd($assgin_user);
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
            ];

            $request = new \Illuminate\Http\Request();
            $request->replace($data);
            $sale->save($request);
        }
        //   }
        
    }
}
