<?php

namespace App\Http\Controllers;

use App\historyPeople;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ResellerController extends Controller
{

    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Reseller';
        $resellers = User::select('id')->addSelect('name')->addSelect('phone')->addSelect('roles')->addSelect('is_active')->where('roles', 2)->orWhere('roles', 3)->get();
        return view('core/reseller', compact('title', 'resellers'));
    }

    public function create()
    {
        $title = 'Tambah Reseller';
        return view('core/createreseller', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'religion' => 'required',
            'gender' => 'required',
            'username' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'roles' => 'required',
        ]);
        $name = 'nophoto.png';
        if(!is_null($request->file('imgreseller'))){
            $file = $request->file('imgreseller');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/profil/', $name);
        }
        User::create([
            'name' => $request->name,
            'religion' => $request->religion,
            'gender' => $request->gender,
            'roles' => $request->roles,
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
            'title' => 'Reseller',
            'description' => 'Menambahkan Reseller',
            'icon' => 'fas fa-user-plus',
            'ip_address' => $request->ip()
        ]);
        return redirect('/reseller')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Reseller Sudah Ditambah!</div>');exit;
    }

    public function show($id)
    {
        $title = 'Detail Reseller';
        $reseller = User::select('id')->addSelect('name')->addSelect('religion')->addSelect('gender')->addSelect('roles')->addSelect('image')->addSelect('phone')->addSelect('address')->addSelect('email')->addSelect('username')->addSelect('is_active')->where('id', $id)->first();
        return view('core/showreseller', compact('title', 'reseller'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'religion' => 'required',
            'gender' => 'required',
            'username' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'roles' => 'required',
        ]);
        $oldImage = User::select('image')->addSelect('password')->where('id', $id)->first();
        $password = (!empty($request->password)) ? Hash::make($request->password) : $oldImage->password;
        $name = $oldImage->image;
        if(!is_null($request->file('imgreseller'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/profil/' . $oldImage->image);
                }
            }
            unset($oldImage);
            $file = $request->file('imgreseller');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/profil/', $name);
        }
        $affected = User::where('id', $id)->update([
            'name' => $request->name,
            'religion' => $request->religion,
            'gender' => $request->gender,
            'roles' => $request->roles,
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
            'title' => 'Reseller',
            'description' => 'Mengubah data Reseller id='. $id,
            'icon' => 'fas fa-user-tag',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/reseller')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Reseller Sudah Diubah!</div>');exit;
        } else {
            return redirect('/reseller')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Reseller gagal Diubah!</div>');exit;
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
