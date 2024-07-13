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
use App\Models\Group;
use App\Models\SrcPage;
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
        $listProduct    = Helper::getListProduct()->get();
        $listSrc        = Helper::getListSrc();

        return view('pages.group.addOrUpdate')->with('listSale', $listSale)->with('listProduct', $listProduct)->with('listSrc', $listSrc);
    }

    public function update($id)
    {
        $listSale       = Helper::getListSale()->get();
        $listProduct    = Helper::getListProduct()->get();
        $listSrc        = Helper::getListSrc();
        // dd($listSrc);
        $group = Group::find($id);
        if ($group) {
            return view('pages.group.addOrUpdate')->with('listSale', $listSale)->with('group', $group)
                ->with('listProduct', $listProduct)->with('listSrc', $listSrc);
        }

        notify()->error('Lỗi không tìm thấy nhóm nào', 'Thất bại!');
        return redirect()->route('manage-group');
    }

    public function updateArrayExist($newArray, $oldArray)
    {
        // $oldUserOfGroup = DetailUserGroup::where('id_group', $req->id)->pluck('id_user')->toArray();
        // $newUserOfGroup = $req->member;
        $tmp = $remove = $add = [];
        

        // echo "<pre>";
        // print_r($oldUserOfGroup);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($newUserOfGroup);
        // echo "</pre>";
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
    

    public function updateFieldOfGroup($id_group, $classDetail, $typeCol, $newReq)
    {
        /** clear data user + group */
        $oldUserOfGroup = $classDetail::where('id_group', $id_group)->pluck($typeCol)->toArray();
        $newUserOfGroup = $newReq;

        $updateUserOfGroup = $this->updateArrayExist($newUserOfGroup, $oldUserOfGroup);
        $remove = $updateUserOfGroup['remove'];
        $add = $updateUserOfGroup['add'];
        foreach ($remove as $id) {
            $dtUserGroup = $classDetail::where('id_group', $id_group)->where($typeCol, $id)->first();
            if ($dtUserGroup) {
                $dtUserGroup->delete();
            }
        }

        foreach ($add as $id) {
            $dtUserGroup = new $classDetail();
            $dtUserGroup->id_group = $id_group;
            $dtUserGroup->$typeCol = $id;
            $dtUserGroup->save();
        } 
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

                /** clear data user + group */
                $classDetail = new DetailUserGroup();
                $this->updateFieldOfGroup($req->id, $classDetail, 'id_user', $req->member); 

                /** clear data product + group */
                $classDetail = new DetailProductGroup();
                $this->updateFieldOfGroup($req->id, $classDetail, 'id_product', $req->product);

                /** reset all data of src page */
                SrcPage::where('id_group', $req->id)->update(['id_group' => 0]);
            } else {
                $gr = new Group();
            }
           
            try {
                /** lưu thông tin nhóm */
                $gr->name   = $req->name;
                $gr->status = $req->status;         
                $gr->save();

                /** lưu thôn tin user trong nhóm */
                if (!isset($req->id)) {
                    $members = $req->member;
                
                    // $tmp = [];
                    foreach ($members as $member) {
                        $detailUser = new DetailUserGroup();
                        $detailUser->id_group = $gr->id;
                        $detailUser->id_user = $member;
                        $detailUser->save();
                        // $tmp[] = $detailUser;
                    }
                }
                // dd($tmp);

                /** lưu thôn tin nguồn data trong nhóm */
                $listSrc = $req->src;
                // dd($listSrc);
                foreach ($listSrc as $src) {
                    $src = SrcPage::find($src);
                    if ($src) {
                        $src->id_group = $gr->id;
                        $src->save();
                    }
                }

                /** lưu thôn tin user trong nhóm */
                if (!isset($req->id)) {
                    $products = $req->product;
                    foreach ($products as $product) {
                        $detailProduct = new DetailProductGroup();
                        $detailProduct->id_group = $gr->id;
                        $detailProduct->id_product = $product;
                        $detailProduct->save();
                    }
                }

            } catch (\Throwable $th) {
                dd($th);
            }

            notify()->success('Lưu thông tin nhóm thành công', 'Thành công!');
            // return redirect()->route('update-group', $gr->id);
            return redirect()->route('manage-group');
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