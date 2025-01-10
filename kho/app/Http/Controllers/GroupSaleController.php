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
use App\Models\DetailProductGroup;
use App\Models\DetailUserGroup;
use App\Models\DetailUserGroupSale;
use App\Models\Group;
use App\Models\GroupSale;
use App\Models\SrcPage;
use Validator;
class GroupSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $checkAll = isFullAccess(Auth::user()->role);
        if ($checkAll) {
            $list = GroupSale::get();
        } else {
            $list = GroupSale::where('id_user',Auth::user()->id)
                ->andWhere('is_leader', 1)->get();
        }
       
        return view('pages.groupSale.index')->with('list', $list);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function add()
    {
        $listSaleNotIn = DetailUserGroupSale::pluck('id_user')->toArray();
        $listSale = User::where('status', 1)->where('is_receive_data', 1)->where('is_sale', 1)
            ->whereNotIn('id', $listSaleNotIn)->get();
        $listProduct    = Helper::getListProduct()->get();
        $listSrc        = Helper::getListSrc();

        return view('pages.groupSale.addOrUpdate')->with('listSale', $listSale)->with('listProduct', $listProduct)->with('listSrc', $listSrc);
    }

    public function update($id)
    {
        $listSaleNotIn = DetailUserGroupSale::where('id_group_sale', '!=', $id)
            ->pluck('id_user')->toArray();
        $listSale = User::where('status', 1)->where('is_receive_data', 1)->where('is_sale', 1)
            ->whereNotIn('id', $listSaleNotIn)->get();

        $listProduct    = Helper::getListProduct()->get();
        $listSrc        = Helper::getListSrc();

        $group = GroupSale::find($id);
        if ($group) {
            return view('pages.groupSale.addOrUpdate')->with('listSale', $listSale)->with('group', $group)
                ->with('listProduct', $listProduct)->with('listSrc', $listSrc);
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
                $gr = GroupSale::find($req->id);

                /** clear data user + group */
                $this->updateFieldOfGroupSale($req->id, $req->member); 
            } else {
                $gr = new GroupSale();
            }

            try {
                $gr->name   = $req->name;
                $gr->status = $req->status; 
                $gr->lead_sale   = $req->leadSale;
                
                $gr->save();

                /** lưu thôn tin user trong nhóm */
                if (!isset($req->id)) {
                    $members = $req->member;
                    // dd( $members);
                    // $tmp = [];
                    foreach ($members as $member) {
                        $detailUser = new DetailUserGroupSale();
                        $detailUser->id_group_sale = $gr->id;
                        $detailUser->id_user = $member;
                        $detailUser->save();
                    }
                }

            } catch (\Throwable $th) {
                dd($th);
            }

            notify()->success('Lưu thông tin nhóm thành công', 'Thành công!');
            // return redirect()->route('update-group', $gr->id);
            return redirect()->route('group-sale');
        }
     
        return response()->json(['errors'=>$validator->errors()]);
    }

    public function updateArrayExist($newArray, $oldArray)
    {
        // $oldUserOfGroup = DetailUserGroup::where('id_group', $req->id)->pluck('id_user')->toArray();
        // $newUserOfGroup = $req->member;
        $tmp = $remove = $add = [];

        foreach ($oldArray as $user) {
            if (in_array($user, $newArray)) {
                $tmp[] = $user;
            } else {
                $remove[] = $user;
            }
        }
        
        foreach ($newArray as $user) {
            if (!in_array($user, $tmp)) {
                $add[] = $user;
            }
        }

        return [
            'remove' => $remove,
            'add' => $add
        ];
    }

    public function updateFieldOfGroupSale($id_group, $newReq)
    {
        /** clear data user + group */
        $oldUserOfGroup = DetailUserGroupsale::where('id_group_sale', $id_group);
        $oldUserOfGroup = $oldUserOfGroup->pluck('id_user')->toArray();
        $newUserOfGroup = $newReq;
        
        $updateUserOfGroup = $this->updateArrayExist($newUserOfGroup, $oldUserOfGroup);
        $remove = $updateUserOfGroup['remove'];
        $add = $updateUserOfGroup['add'];

        // dd($updateUserOfGroup);
        $classDetail = new DetailUserGroupsale();
        foreach ($remove as $id) {
            $dtUserGroup = $classDetail::where('id_group_sale', $id_group)->first();
            if ($dtUserGroup) {
                $dtUserGroup->delete();
            }
        }

        // dd($add);
        foreach ($add as $id) {
            $dtUserGroup = new DetailUserGroupsale();
            $dtUserGroup->id_user = $id;
            $dtUserGroup->id_group_sale = $id_group;
            $dtUserGroup->save();
        }
    }

    public function delete($id)
    {
        $gr = GroupSale::find($id);
        if ($gr) {
            $gr->delete();
            notify()->success('Xoá nhóm thành công', 'Thành công!');
            
        } else {
            notify()->error('Xoá nhóm thất bại', 'Thất bại!');
        }

        return redirect()->route('group-sale');
    }
}
