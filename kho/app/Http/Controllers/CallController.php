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


class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $calls = Call::orderBy('id', 'desc')->paginate(15);
        // dd($calls);
        return view('pages.call.index')->with('call', $calls);
    }

    public function add()
    { 
        // $helper = new Helper();
        // $listSale = $helper->getListSale()->get();
        return view('pages.call.add');
    }

    public function save(Request $req) {
        $validator      = Validator::make($req->all(), [
            'name'  => 'required',
        ],[
            'name.required' => 'Nhập tên khách hàng',
        ]);

        if ($validator->passes()) {
            if (isset($req->id)) {
                $call = Call::find($req->id);
                $call->status  = $req->status;
                $text = 'Cập nhật call thành công.';
            } else {
                $call = new Call();
                $text = 'Tạo call thành công.';
            }
            // dd($request->products);
            $call->name = $req->name;
            $call->time = $req->time;
            $call->save();
            
            notify()->success($text, 'Thành công!');
           
        } else {
            notify()->error('Lỗi khi tạo call mới', 'Thất bại!');
           
        }

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
