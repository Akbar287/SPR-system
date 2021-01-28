<?php

namespace App\Http\Controllers;

use App\historyPeople;
use App\markets;
use App\resellerMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Toko';
        $stores = markets::select('markets.id')->addSelect('markets.name')->addSelect('markets.owner')->join('reseller_market', 'reseller_market.id_market', 'markets.id')->where('reseller_market.id_reseller', Auth::user()->id)->get();
        return view('core/store', compact('title', 'stores'));
    }

    public function show($id)
    {
        $title = 'Detail Toko';
        $store = markets::select('id')->addSelect('name')->addSelect('owner')->addSelect('address')->addSelect('description')->addSelect('image')->addSelect('created_at')->where('id', $id)->first();
        return view('core/detailstore', compact('title', 'store'));
    }
    public function request()
    {
        $title = 'Request Toko';
        return view('core/requeststore', compact('title'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'owner' => 'required',
            'description' => 'required',
        ]);
        $name = 'nophoto.png';
        if(!is_null($request->file('imgstore'))){
            $file = $request->file('imgstore');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/market/', $name);
        }
        $market = new markets();
        $market->name = $request->name;
        $market->owner = $request->owner;
        $market->address = $request->address;
        $market->image = $name;
        $market->description = $request->description;
        $market->is_active = 0;
        $market->created_by = Auth::user()->id;
        $market->save();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Toko',
            'description' => 'Request Toko id='.$market->id,
            'icon' => 'fas fa-store',
            'ip_address' => $request->ip()
        ]);
        return redirect('/store')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Request Toko Kamu Akan dipertimbangkan!</div>');exit;
    }
    public function statement()
    {
        $title = 'Laporan Keuangan';
        return view('core/statement', compact('title'));
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
