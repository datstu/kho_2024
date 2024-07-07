<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AddressController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\SaleCare;
use App\Helpers\Helper;
use App\Models\Group;
use Validator;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $list = Group::get();
        return view('pages.group.index')->with('list', $list);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function add()
    {
        $listSale       = Helper::getListSale()->get();
        return view('pages.group.addOrUpdate')->with('listSale', $listSale);
    }

    public function update($id)
    {
        $listSale       = Helper::getListSale()->get();

        $group = Group::find($id);
        if ($group) {
            return view('pages.group.addOrUpdate')->with('listSale', $listSale)->with('group', $group);
        }

        notify()->error('Lỗi không tìm thấy nhóm nào', 'Thất bại!');
        return redirect()->route('manage-group');
       
    }

    

    public function save(Request $req) {
        // dd($req->all());
        $validator = Validator::make($req->all(), [
            'name' => 'required',
        ],[
            'name.required' => 'Nhập tên nhóm',
        ]);
       
        if ($validator->passes()) {
            if(isset($req->id)){
                $gr = Group::find($req->id);
            } else {
                $gr = new Group();
            }
           
            try {
                // dd($req->all());
                $member     = json_encode($req->member);
                $gr->name   = $req->name;
                $gr->member = $member;
                $gr->status = $req->status;
            
                $gr->save();
            } catch (\Throwable $th) {
                dd($th);
            }

            notify()->success('Lưu thông tin nhóm thành công', 'Thành công!');
            return redirect()->route('update-group', $gr->id);
        }
     
        return response()->json(['errors'=>$validator->errors()]);
    }

    public function delete($id)
    {
        $gr = Group::find($id);
        if ($gr) {
            $gr->delete();
            notify()->success('Xoá nhóm thành công', 'Thành công!');
            
        } else {
            notify()->error('Xoá nhóm thất bại', 'Thất bại!');
        }

        return redirect()->route('manage-group');
    }
}
