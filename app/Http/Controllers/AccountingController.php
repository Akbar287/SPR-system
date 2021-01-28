<?php

namespace App\Http\Controllers;

use App\accounting;
use App\historyPeople;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Akuntansi';
        $asset = accounting::select('id')->addSelect('ref')->addSelect('name')->where('type', 1)->get();
        $liabilitas = accounting::select('id')->addSelect('ref')->addSelect('name')->where('type', 2)->get();
        $equitas = accounting::select('id')->addSelect('ref')->addSelect('name')->where('type', '>=', 3)->get();
        return view('core/account', compact('title', 'asset', 'liabilitas', 'equitas'));
    }
    public function create()
    {
        $title = 'Akuntansi';
        return view('core/createaccount', compact('title'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:128',
            'ref' => 'required|max:4',
            'type' => 'required',
        ]);
        if(accounting::select('id')->where('ref', $request->ref)->orWhere('name', $request->name)->first()){
            return redirect('/accounting')->with('status', '<div class="alert alert-warning" role="alert"><strong>Gagal</strong>, Data Akuntansi Sudah Ada Sebelumnya!</div>');exit;
        }
        $account = new accounting();
        $account->ref = $request->ref;
        $account->type = $request->type;
        $account->name = $request->name;
        $account->description = (is_null($request->description)) ? '' : $request->description;
        $account->save();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menambah Data Akuntansi id = '. $account->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/accounting')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Akuntansi Sudah Ditambah!</div>');exit;
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
