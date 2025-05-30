<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function login() {
        if (Auth::check()) {
            return redirect()->route('home');
        } 
        return view('pages.users.login');
    }

    public function postLogin(Request $r) {
        if (Auth::attempt(['name' => $r->name, 'password' => $r->password, 'status' => 1])) {
            return redirect()->route('home');
        } 

        return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi đăng nhập.')
            ->with('email',  $r->email)->with('password',  $r->password);
    }

    public function logOut() {
        Auth::logout();
        return redirect()->route('login');
    }

    public function index() {
        $list = User::orderBy('id', 'desc')->paginate(15);
        return view('pages.users.index')->with('list', $list);
    }

    public function add() {
        return view('pages.users.addOrUpdate');
    }

    public function save(Request $req) {
        // dd($req->all());
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'password' => 'required',
        ],[
            'name.required' => 'Nhập tên thành viên',
            'password.required' => 'Nhập mật khẩu',
        ]);
       
        if ($validator->passes()) {
            if(isset($req->id)){
                $user           = User::find($req->id);
                $user->status   = $req->status;

                // dd( $req->status);
                if ($user->password != $req->password) {
                    $pass = Hash::make($req->password);
                    $user->password = $pass;
                } else {
                    $user->password = $req->password;
                }
               
                $text = 'Cập nhật thành viên thành công.';
            } else{
                $user = new User();
                // $user->status = 1;
                $pass = Hash::make($req->password);
                $user->password =  $pass;
                
                $text = 'Tạo thành viên thành công.';
            }
           
            try {
                // dd($req->all());
                $user->name         = $req->name;
                $user->real_name    = $req->real_name;
                $user->email        = $req->email;
                $user->is_sale      = $req->is_sale;
                $user->is_digital   = $req->is_digital;
                $user->is_CSKH      = $req->is_CSKH;
                
                $user->is_receive_data = ($req->is_receive_data) ? $req->is_receive_data : 0 ;
                $user->role         = json_encode($req->roles);
                $user->save();
            } catch (\Throwable $th) {
                dd($th);
                $text = 'Đã có lỗi xảy ra. Vui lòng thử lại sau.';
                return response()->json(['error'=>$text]);
            }
            return response()->json(['success'=> $text]);
        }
     
        return response()->json(['errors'=>$validator->errors()]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewUpdate($id)
    {
        $user = User::find($id);
        if($user){
            return view('pages.users.addOrUpdate')->with('user', $user);
        } 

        return redirect('/');
      
    }

    public function delete($id)  
    {
        $product = User::find($id);
        if($product){
            $product->delete();
            return redirect('/quan-ly-thanh-vien')->with('success', 'Thành viên xoá thành công!');            
        } 

        return redirect('/danh-sach-san-pham') ->with('error', 'Đã xảy ra lỗi khi xoá thành viên!');
    }

    public function search(Request $str)
    {
        $list = User::where('real_name',  'like', '%' . $str->search . '%')->paginate(15);
        return view('pages.users.index')->with('list', $list);
    }
}
