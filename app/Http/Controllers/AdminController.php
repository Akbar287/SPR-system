<?php

namespace App\Http\Controllers;

use App\historyPeople;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Admin';
        $admins = User::select('id')->addSelect('name')->addSelect('phone')->addSelect('roles')->addSelect('is_active')->where('roles', 1)->get();
        return view('core/admin', compact('title', 'admins'));
    }
    public function create()
    {
        if(Auth::user()->roles == 1){
            $title = 'Tambah Admin';
            return view('core/createadmin', compact('title'));
        } else {
            redirect('/home')->with('status', '<div class="alert alert-warning" role="alert">Hak Akses Terbatas!</div>');exit;
        }
    }
    public function store(Request $request)
    {
        if(Auth::user()->roles == 1){
            $this->validate($request, [
                'name' => 'required',
                'address' => 'required',
                'religion' => 'required',
                'gender' => 'required',
                'username' => 'required',
                'password' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
            ]);
            $name = 'nophoto.png';
            if(!is_null($request->file('imgadmin'))){
                $file = $request->file('imgadmin');
                $name = $this->imgRandom($file->getClientOriginalName());
                $file->move(public_path() . '/img/profil/', $name);
            }
            User::create([
                'name' => $request->name,
                'religion' => $request->religion,
                'gender' => $request->gender,
                'roles' => 1,
                'image' => $name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'is_active' => (!is_null($request->is_active)) ? 1 : 0,
            ]);

            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Admin',
                'description' => 'Menambahkan Admin',
                'icon' => 'fas fa-user-plus',
                'ip_address' => $request->ip()
            ]);
            return redirect('/admin')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Admin Sudah Ditambah!</div>');exit;
        } else {
            redirect('/home')->with('status', '<div class="alert alert-warning" role="alert">Hak Akses Terbatas!</div>');exit;
        }
    }
    public function show($id)
    {
        if(Auth::user()->roles == 1){
            $title = 'Detail Admin';
            $admin = User::select('id')->addSelect('name')->addSelect('religion')->addSelect('gender')->addSelect('roles')->addSelect('image')->addSelect('phone')->addSelect('address')->addSelect('email')->addSelect('username')->addSelect('is_active')->where('id', $id)->first();
            return view('core/showadmin', compact('title', 'admin'));
        } else {
            redirect('/home')->with('status', '<div class="alert alert-warning" role="alert">Hak Akses Terbatas!</div>');exit;
        }
    }
    public function update(Request $request, $id)
    {
        if(Auth::user()->roles == 1){
            $this->validate($request, [
                'name' => 'required',
                'address' => 'required',
                'religion' => 'required',
                'gender' => 'required',
                'username' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
            ]);
            $oldImage = User::select('image')->addSelect('password')->where('id', $id)->first();
            $password = (!empty($request->password)) ? Hash::make($request->password) : $oldImage->password;
            $name = $oldImage->image;
            if(!is_null($request->file('imgadmin'))){
                if (!is_null($oldImage)) {
                    if($name != 'nophoto.png'){
                        File::delete(public_path() . '/img/profil/' . $oldImage->image);
                    }
                }
                unset($oldImage);
                $file = $request->file('imgadmin');
                $name = $this->imgRandom($file->getClientOriginalName());
                $file->move(public_path() . '/img/profil/', $name);
            }
            $affected = User::where('id', $id)->update([
                'name' => $request->name,
                'religion' => $request->religion,
                'gender' => $request->gender,
                'roles' => 1,
                'image' => $name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $password,
                'is_active' => (!is_null($request->is_active)) ? 1 : 0,
            ]);
            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Admin',
                'description' => 'Mengubah data Admin id='. $id,
                'icon' => 'fas fa-user-tag',
                'ip_address' => $request->ip()
            ]);
            if($affected == 1){
                return redirect('/admin')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Admin Sudah Diubah!</div>');exit;
            } else {
                return redirect('/admin')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Admin gagal Diubah!</div>');exit;
            }
        } else {
            redirect('/home')->with('status', '<div class="alert alert-warning" role="alert">Hak Akses Terbatas!</div>');exit;
        }
    }
    public function destroy($id)
    {
        //
    }
    private function imgRandom($img)
    {
        $img = (explode('.', $img));
        $ekstensi = $img[count($img) - 1];
        unset($img[count($img) - 1]);
        $img = implode('.', $img) . '.' . time() . '.' . $ekstensi;
        return $img;
    }
}
