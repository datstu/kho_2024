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
use App\Models\CategoryCall;
use App\Models\CallResult;


class CallResultController extends Controller
{
    public function index()
    {
        $callResult = CallResult::orderBy('id', 'desc')->paginate(15);
        return view('pages.call.result.index')->with('callResult', $callResult);
    }

    public function add()
    { 
        // $helper = new Helper();
        // $listSale = $helper->getListSale()->get();
        return view('pages.call.result.add');
    }

    public function save(Request $req) {
    
        $validator      = Validator::make($req->all(), [
            'name'       => 'required',
        ],[
            'name.required' => 'Vui lòng nhập loại TN',
        ]);

        if ($validator->passes()) {
            if (isset($req->id)) {
                $call = CallResult::find($req->id);
                $call->status  = $req->status;
                $text = 'Cập nhật kết quả gọi thành công.';
            } else {
                $call = new CallResult();
                $text = 'Tạo kết quả gọi thành công.';
            }
            // dd($request->products);
            $call->name = $req->name;
            $call->save();
            
            notify()->success($text, 'Thành công!');
           
        } else {
            // ['errors'=>$validator->errors()]
            // echo "<pre>";
            // print_r($validator->errors()->messages());
            $resp = $validator->errors()->messages();
                    // for (index in resp) {
            foreach ($resp as $err) {
                // print_r ( $err[0]);
                notify()->error($err[0], 'Thất bại!');
                break;
            }
            // die();
           
            
            // notify(3)->error('Lỗi khi taaaạo call mới', 'Thất bại!');
           return back();
        }

        return redirect()->route('call-result');
    }

    public function update($id) {
        $call = CallResult::find($id);
        if($call){
            return view('pages.call.result.add')->with('callResult', $call);
        } 

        return redirect('/');
    }

    public function delete($id)
    {
        $callRS = CallResult::find($id);
        if($callRS){
    
            if ($callRS->operational->count()) {
                notify()->error('Xoá kết quả TN thất bại vì kq này đang sử dụng!', 'Thất bại!');
                return back();
            }
            $callRS->delete();
            notify()->success('Xoá kết quả TN thành công.', 'Thành công!');
            return back();            
        } 
        notify()->error('Xoá kết quả TN thất bại!', 'Thất bại!');
        return back();
    }
}
