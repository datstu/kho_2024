<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Call;
use App\Helpers\Helper;
use App\Models\Telegram;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    
        $telegram = Telegram::first();
        // return view('pages.call.index')->with('call', $calls);
        return view('pages.setting.index')->with('telegram', $telegram);
    }

    public function add()
    { 
        // $helper = new Helper();
        // $listSale = $helper->getListSale()->get();
        return view('pages.call.add');
    }

    public function telegramSave(Request $req) {
        // dd($req->all());
        if (isset($req->id)) {
            $tele = Telegram::find($req->id);
            // $call->status  = $req->status;
        } else {
            // dd($req->all());
            $tele = new Telegram();
        }

        $tele->token = $req->token_telegram;
        $tele->id_NVTR = $req->id_NVTR;
        $tele->id_CSKH = $req->id_CSKH;
        $tele->status = $req->status;
        $tele->save();
        notify()->success('Cập nhật thông tin Telegram thành công.', 'Thành công!');
        

        return back();
    }

    public function update($id) {;
        $call = Call::find($id);
        if($call){
            return view('pages.call.add')->with('call', $call);
        } 

        return redirect('/');
    }
}
