<?php

namespace App\Http\Controllers;

use App\accounting;
use App\Beban;
use App\Credit;
use App\Debit;
use App\Financials;
use App\historyPeople;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index(Request $request)
    {
        if($request->s1 && $request->s2){
            $expense = json_decode(Beban::select('accounting.name')->addSelect('beban.total')->addSelect('beban.id')->join('accounting', 'accounting.ref', 'beban.refbeban')->whereYear('beban.created_at', '=', $request->s2)->whereMonth('beban.created_at', '=', $request->s1)->get(), true);
            $title = 'Beban';
            $month = [ 1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $information = DB::table('information')->select('created_at')->where('id', 1)->first();
            $year = explode(' ', $information->created_at, -1);
            $year = explode('-', $year['0'], -2);$year = [ $year['0'], date('Y') ];
            return view('core/expense', compact('title', 'month', 'year', 'expense'));
        } else {
            $title = 'Beban';
            $expense = [];
            $month = [ 1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $information = DB::table('information')->select('created_at')->where('id', 1)->first();
            $year = explode(' ', $information->created_at, -1);
            $year = explode('-', $year['0'], -2);$year = [ $year['0'], date('Y') ];
            return view('core/expense', compact('title', 'month', 'year', 'expense'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Buat Data Beban';
        $balance = accounting::select('description')->where('ref', 101)->first();
        $time = $this->getMonthName(date('n')) . ' ' . date('Y');
        $refbeban = accounting::select('ref')->addSelect('name')->where('type', '=', 6)->whereNotIn('ref', [600, 601, 614])->get();
        return view('core/createExpense', compact('title', 'time', 'refbeban', 'balance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ref' => 'required',
            'nominal' => 'required',
        ]);
        $nominal = str_replace(',', '', $request->nominal);
        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', $request->ref)->get(),true);
        accounting::where('ref', 101)->update(['description' => ((int)$akun['0']['description'] - (int)$nominal)]);
        accounting::where('ref', $request->ref)->update(['description' => ((int)$akun['1']['description'] + (int)$nominal)]);

        $debit = new Debit();
        $debit->refdebit = $request->ref;
        $debit->debitdesc = $nominal;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = 101;
        $credit->creditdesc = $nominal;
        $credit->save();

        $financial = new Financials();
        $financial->id_debit = $debit->id;
        $financial->id_credit = $credit->id;
        $financial->description = 'Pembayaran Beban';
        $financial->created_at = Auth::user()->id;
        $financial->save();

        $beban = new Beban();
        $beban->id_financial = $financial->id;
        $beban->refbeban = $request->ref;
        $beban->total = $nominal;
        $beban->save();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menambah Data Beban id = '. $beban->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/expense')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Beban Sudah Ditambah!</div>');exit;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Data Beban';
        $balance = accounting::select('description')->where('ref', 101)->first();
        $time = $this->getMonthName(date('n')) . ' ' . date('Y');
        $beban = Beban::select('refbeban')->addSelect('total')->addSelect('beban.id')->addSelect('beban.created_at')->addSelect('accounting.name')->join('accounting', 'accounting.ref', 'beban.refbeban')->where('beban.id', $id)->first();
        return view('core/showExpense', compact('title', 'time', 'beban', 'balance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'ref' => 'required',
            'nominal' => 'required',
        ]);
        $nominal = str_replace(',', '', $request->nominal);
        $beban = Beban::select('beban.id')->addSelect('beban.total')->addSelect('financial.id as id_financial')->addSelect('financial.id_debit')->addSelect('financial.id_credit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->join('financial', 'financial.id', 'beban.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('beban.id', $id)->first();
        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', $request->ref)->get(),true);
        accounting::where('ref', 101)->update(['description' => (((int)$akun['0']['description'] + (int)$beban->total) - (int)$nominal)]);
        accounting::where('ref', $request->ref)->update(['description' => (((int)$akun['1']['description'] - $beban->total) + (int)$nominal)]);

        Debit::where('id', $beban->id_debit)->update(['debitdesc' => $nominal]);
        Credit::where('id', $beban->id_credit)->update(['creditdesc' => $nominal]);
        Financials::where('id', $beban->id_financial)->update(['updated_by' => Auth::user()->id]);

        $affected = Beban::where('id', $id)->update(['total' => $nominal]);

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Beban id = '. $beban->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);

        if($affected > 0)
        {
            return redirect('/expense')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Beban Sudah Diubah!</div>');exit;
        }
        else
        {
            return redirect('/expense')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Beban gagal Diubah!</div>');exit;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $beban = Beban::select('beban.id')->addSelect('beban.refbeban')->addSelect('beban.total')->addSelect('financial.id as id_financial')->addSelect('financial.id_debit')->addSelect('financial.id_credit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->join('financial', 'financial.id', 'beban.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('beban.id', $id)->first();
        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', $beban->refbeban)->get(),true);
        accounting::where('ref', 101)->update(['description' => (((int)$akun['0']['description'] + (int)$beban->total))]);
        accounting::where('ref', $beban->refbeban)->update(['description' => (((int)$akun['1']['description'] - $beban->total))]);
        Financials::where('id', $beban->id_financial)->update(['deleted_by' => Auth::user()->id]);
        Financials::where('id', $beban->id_financial)->delete();

        $affected = Beban::where('id', $id)->delete();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menghapus Data Beban id = '. $beban->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);

        if($affected > 0)
        {
            return redirect('/expense')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Beban Sudah Dihapus!</div>');exit;
        }
        else
        {
            return redirect('/expense')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Beban gagal Dihapus!</div>');exit;
        }
    }
    private function getMonthName($month)
    {
        if (intVal($month) == 1) {
            return 'January';
        } else if (intVal($month) == 2) {
            return 'February';
        } else if (intVal($month) == 3) {
            return 'March';
        } else if (intVal($month) == 4) {
            return 'April';
        } else if (intVal($month) == 5) {
            return 'May';
        } else if (intVal($month) == 6) {
            return 'Juny';
        } else if (intVal($month) == 7) {
            return 'July';
        } else if (intVal($month) == 8) {
            return 'August';
        } else if (intVal($month) == 9) {
            return 'September';
        } else if (intVal($month) == 10) {
            return 'October';
        } else if (intVal($month) == 11) {
            return 'November';
        } else if (intVal($month) == 12) {
            return 'December';
        }
    }
}
