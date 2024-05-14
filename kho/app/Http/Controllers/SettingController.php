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
use App\Models\Pancake;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // die();
        $pancake    = Pancake::first();
        $telegram   = Telegram::first();
        // return view('pages.call.index')->with('call', $calls);
        return view('pages.setting.index')->with('telegram', $telegram)->with('pancake', $pancake);
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

    public function pancakeSave(Request $req) {
        // dd($req->all());
        if (isset($req->id)) {
            $pancake = Pancake::find($req->id);
            // $call->status  = $req->status;
        } else {
            // dd($req->all());
            $pancake = new Pancake();
        }

        $pancake->token = $req->token_pancake;
        $pancake->page_id = $req->page_id;
        $pancake->status = $req->status;
        $pancake->save();
        notify()->success('Cập nhật thông tin Pancake thành công.', 'Thành công!');
        

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
