<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Helpers\Helper;
use App\Models\Group;

class LadipageController  extends Controller
{
    //
    public function index2(Request $r) 
    {
        Log::info('run api ladipage');

        // dd($r->all());
        $phone = $r->phone;
        $name = $r->name;
        // $email = $r->email;
        $item = $r->form_item3209;
        $address = $r->address;
        $linkPage = $r->link;
        
        $messages = $item;
        if ( $address) {
            $messages .= "\n" . $address;
        }
        $all = json_encode($r->all());
 Log::info('sao z');
        Log::info($all);

        $ladiPage = Helper::getConfigLadiPage();
        $namePage = 'Ladi Page';
        
        $assgin_user = 0;
        $is_duplicate = 0;
        $isOldDataLadi = Helper::isOldDataLadi($phone, $linkPage, $assgin_user);

        if (!$isOldDataLadi) {
            $assignSale = Helper::getAssignSale();
            $assgin_user = $assignSale->id;
        } else {
            $is_duplicate = 1;
        }

        if($ladiPage->status == 1) {
            $sale = new SaleController();
                $data = [
                  'page_link' => $linkPage,
                  'page_name' => $namePage,
                  'sex'       => 0,
                  'old_customer' => 0,
                  'address'   => '...',
                  'messages'  => $messages,
                  'name'      => $name,
                  'phone'     => $phone,
                  'text'      => $namePage,
                  'chat_id'   => 'id_VUI',
                  'assgin'    => $assgin_user,
                  'is_duplicate' => $is_duplicate
                ];

                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
        }
        return response()->json(['success' => 'oke'], 200);
    }

    public function index(Request $r) 
    {
        $phone = $r->phone;
        $name = $r->name;
        // $email = $r->email;
        $item = $r->form_item3209;
        $address = $r->address;
        $linkPage = $r->link;
        
        $messages = $item;
        if ( $address) {
            $messages .= "\n" . $address;
        }
        $all = json_encode($r->all());
        // Log::channel('webhook')->info($all);
        // $str = '{"variant_url":null,"utm_campaign":null,"ip":"58.187.189.59","utm_medium":null,"link":"https:\/\/www.nongnghiepsachvn.net\/tricho-bacillus-km","form_item3209":"T\u00f4i mu\u1ed1n b\u00e1o gi\u00e1 2 x\u00f4","message_id":"ed9dbbde-5005-11ef-a2e3-2325c6b4e731","utm_term":null,"message_time":1722517186337,"phone":"0973409613","url_page":"https:\/\/www.nongnghiepsachvn.net\/tricho-bacillus-km","name":"dattest","ladi_form_id":"FORM14","variant_content":null,"utm_source":null,"utm_content":null}';
        $str = $all;
        $arr = json_decode($str, true);
        $linkPage = $arr['link'];
        //  Log::channel('webhook')->info('link: ' .$linkPage);
        // dd($arr);
        Log::info($all);

        $assgin_user = 0;
        $is_duplicate = 0;

        // $group = Group::where('link', 'like', '%' . $linkPage . '%')->first();
        // Log::channel('webhook')->info('--------------------------');
        
        if (str_contains($linkPage, 'tricho-bacillus-km')) {
            $linkPage = 'https://www.nongnghiepsachvn.net/tricho-bacillus-km';
        } else if (str_contains($linkPage, 'uudai45')) {
            $linkPage = 'https://www.phanbonorganic.com/uudai45';
        } else if (str_contains($linkPage, 'uudai-trichoderma')) {
            $linkPage = 'https://www.phanbonorganic.com/uudai-trichoderma';
        }
        Log::info($linkPage);
        $group = Helper::getGroupByLinkLadi($linkPage);
        
        if ($group && $phone != '0344411068' && $phone != '0841111116' && $phone != '0841265116' && $phone != '0841265117') {
            $chatId = $group->tele_hot_data;
            $phone = Helper::getCustomPhoneNum($phone);
            $isOldDataLadi = Helper::isOldDataLadi($phone, $assgin_user);
            if (!$isOldDataLadi) {
                // $assignSale = Helper::getAssignSale();
                // $assgin_user = $assignSale->id;
                $assignSale = Helper::getAssignSaleByGroup($group);
                $assgin_user = $assignSale->id_user;
                
            } else {
                $is_duplicate = 1;
            }
           
            // echo '$linkPage ' . $linkPage . '<br>';
            // echo '$messages ' . $messages . '<br>';
            // echo '$name ' . $name . '<br>';
            // echo '$phone ' . $phone . '<br>';
            // echo '$chatId ' . $chatId . '<br>';
            // echo '$assgin_user ' . $assgin_user . '<br>';
            // echo '$is_duplicate ' . $is_duplicate . '<br>';
            // echo '$group ' . $group->id;

            // die();
            $pageNameLadi = 'Ladi Page. Link: ' . $linkPage;
            $sale = new SaleController();
            $data = [
                'page_link' => $linkPage,
                'page_name' => $pageNameLadi,
                'sex'       => 0,
                'old_customer' => 0,
                'address'   => '',
                'messages'  => $messages,
                'name'      => $name,
                'phone'     => $phone,
                'page_id'   => '',
                'text'      => $pageNameLadi,
                'chat_id'   => $chatId,
                'm_id'      => 'mId',
                'assgin'    => $assgin_user,
                'is_duplicate' => $is_duplicate,
                'group_id'  => $group->id,
            ];

            Log::info( $data);
            $request = new \Illuminate\Http\Request();
            $request->replace($data);
            $sale->save($request);
        }
        
        return response()->json(['success' => 'oke'], 200);
    }
}
