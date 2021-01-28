<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\historyPeople;
use App\markets;
use App\resellerMarket;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MarketController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {

        $title = 'Toko';
        $markets = markets::select('markets.id')->addSelect('markets.name')->addSelect('users.name as reseller')->addSelect('owner')->addSelect('markets.address')->addSelect('markets.is_active')->join('reseller_market', 'reseller_market.id_market', 'markets.id')->join('users', 'reseller_market.id_reseller', 'users.id')->get();
        return view('core/markets', compact('title', 'markets'));

    }

    public function create()
    {

        $title = 'Tambah Toko';
        $resellers = User::select('id')->addSelect('name')->where('is_active', 1)->where('roles', 2)->orWhere('roles', 3)->get();
        return view('core/createmarket', compact('title', 'resellers'));

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'owner' => 'required',
            'description' => 'required',
            'reseller' => 'required',
        ]);
        $name = 'nophoto.png';
        if(!is_null($request->file('imgmarket'))){
            $file = $request->file('imgmarket');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/market/', $name);
        }
        $market = new markets();
        $market->name = $request->name;
        $market->owner = $request->owner;
        $market->address = $request->address;
        $market->image = $name;
        $market->description = $request->description;
        $market->is_active = ((!is_null($request->is_active)) ? 1 : 0);
        $market->created_by = Auth::user()->id;
        $market->save();

        resellerMarket::create([
            'id_reseller' => $request->reseller,
            'id_market' => $market->id,
            'created_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Toko',
            'description' => 'Menambahkan Toko id='.$market->id,
            'icon' => 'fas fa-store',
            'ip_address' => $request->ip()
        ]);
        return redirect('/market')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Toko Sudah Ditambah!</div>');exit;

    }

    public function show($id)
    {
        $title = 'Detail Toko';
        $resellers = User::select('id')->addSelect('name')->where('is_active', 1)->where('roles', 2)->orWhere('roles', 3)->get();
        $market = markets::select('markets.id')->addSelect('markets.name')->addSelect('users.id as id_reseller')->addSelect('users.name as reseller')->addSelect('owner')->addSelect('markets.address')->addSelect('markets.is_active')->addSelect('markets.description')->addSelect('markets.image')->join('reseller_market', 'reseller_market.id_market', 'markets.id')->join('users', 'reseller_market.id_reseller', 'users.id')->where('markets.id', $id)->first();
        return view('core/showmarket', compact('title', 'market', 'resellers'));

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'owner' => 'required',
            'description' => 'required',
            'reseller' => 'required',
        ]);
        $reseller = resellerMarket::select('id')->addSelect('id_reseller')->where('id_market', $id)->first();
        if($request->reseller != $reseller->id_reseller){
            resellerMarket::where('id', $id)->update(['id_reseller' => $request->reseller]);
        }
        $oldImage = markets::select('image')->where('id', $id)->first();
        $name = $oldImage->image;
        if(!is_null($request->file('imgmarket'))){
            if (!is_null($oldImage)) {
                if($name != 'nophoto.png'){
                    File::delete(public_path() . '/img/market/' . $oldImage->image);
                }
            }
            unset($oldImage);
            $file = $request->file('imgmarket');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/market/', $name);
        }
        $affected = markets::where('id', $id)->update([
            'name' => $request->name,
            'owner' => $request->owner,
            'address' => $request->address,
            'description' => $request->description,
            'image' => $name,
            'is_active' => (!is_null($request->is_active)) ? 1 : 0,
            'updated_by' => Auth::user()->id,
        ]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Toko',
            'description' => 'Mengubah data Toko id='. $id,
            'icon' => 'fas fa-store',
            'ip_address' => $request->ip()
        ]);
        if($affected == 1){
            return redirect('/market')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Toko Sudah Diubah!</div>');exit;
        } else {
            return redirect('/market')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Toko gagal Diubah!</div>');exit;
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
