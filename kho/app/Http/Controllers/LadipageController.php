<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use App\Helpers\Helper;

class LadipageController  extends Controller
{
    //
    public function index(Request $r) 
    {
        // Log::info('run api ladipage');

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

        Log::info($all);

        $ladiPage = Helper::getConfigLadiPage();
        $namePage = 'Ladi Page';
        
        $assgin_user = 0;
        $isOldDataLadi = Helper::isOldDataLadi($phone, $linkPage, $assgin_user);
        if (!$isOldDataLadi) {
            $assignSale = Helper::getAssignSale();
            $assgin_user = $assignSale->id;
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
                  'assgin'    => $assgin_user
                ];

                $request = new \Illuminate\Http\Request();
                $request->replace($data);
                $sale->save($request);
        }
        return response()->json(['success' => 'oke'], 200);
    }
}
