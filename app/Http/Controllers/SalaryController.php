<?php

namespace App\Http\Controllers;

use App\accounting;
use App\Beban;
use App\Credit;
use App\Debit;
use App\Financials;
use App\historyPeople;
use App\User;
use App\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $time = (is_null($request->s1) && is_null($request->s2)) ? [date('n') , date('Y')] : [$request->s1, $request->s2];
        $total = Salary::select(DB::raw('SUM(total) as total'))->whereYear('salary.created_at', '=', $time['1'])->whereMonth('salary.created_at', '=', $time['0'])->first();
        $gaji = json_decode(Salary::select('users.name')->addSelect('salary.total')->addSelect('salary.id')->join('users', 'users.id', 'salary.id_user')->whereYear('salary.created_at', '=', $time['1'])->whereMonth('salary.created_at', '=', $time['0'])->get(), true);
        $title = 'Gaji';
        $month = [ 1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $information = DB::table('information')->select('created_at')->where('id', 1)->first();
        $year = explode(' ', $information->created_at, -1);
        $year = explode('-', $year['0'], -2);$year = [ $year['0'], date('Y') ];
        return view('core/salary', compact('title', 'month', 'year', 'gaji', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Gaji';
        $balance = accounting::select('description')->where('ref', 101)->first();
        $users = User::select('id')->addSelect('name')->addSelect('roles')->orderBy('roles', 'ASC')->get();
        $time = $this->getMonthName(date('n')) . ' ' . date('Y');
        $role = [1=>'Admin', 'Reseller Produk', 'Reseller Non-Produk'];
        return view('core/createSalary', compact('title', 'time', 'balance', 'role', 'users'));
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
            'user' => 'required',
            'nominal' => 'required',
        ]);
        $nominal = str_replace(',', '', $request->nominal);
        $data = Beban::select('beban.id')->addSelect('beban.total')->addSelect('financial.id_debit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->addSelect('financial.id_credit')->addSelect('beban.id_financial')->join('financial', 'financial.id', 'beban.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('refbeban', 600)->whereYear('beban.created_at', '=', date('Y'))->whereMonth('beban.created_at', '=', date('m'))->first();
        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', 600)->get(),true);
        accounting::where('ref', 101)->update(['description' => ((int)$akun['0']['description'] - (int)$nominal)]);
        accounting::where('ref', 600)->update(['description' => ((int)$akun['1']['description'] + (int)$nominal)]);
        if(is_null($data))
        {
            $debit = new Debit();
            $debit->refdebit = 600;
            $debit->debitdesc = $nominal;
            $debit->save();

            $credit = new Credit();
            $credit->refcredit = 101;
            $credit->creditdesc = $nominal;
            $credit->save();

            $financial = new Financials();
            $financial->id_debit = $debit->id;
            $financial->id_credit = $credit->id;
            $financial->description = 'Pembayaran Gaji';
            $financial->created_by = Auth::user()->id;
            $financial->save();

            $beban = new Beban();
            $beban->id_financial = $financial->id;
            $beban->refbeban = 600;
            $beban->total = $nominal;
            $beban->save();

            $salary = new Salary();
            $salary->id_user = $request->user;
            $salary->id_beban = $beban->id;
            $salary->total = $nominal;
            $salary->status = 1;
            $salary->save();

            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Akuntansi',
                'description' => 'Menambah Data Gaji id = '. $salary->id,
                'icon' => 'fas fa-dollar-sign',
                'ip_address' => $request->ip()
            ]);
            return redirect('/salary')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data gaji Sudah Ditambah!</div>');exit;
        } else {
            $salary = new Salary();
            $salary->id_user = $request->user;
            $salary->id_beban = $data->id;
            $salary->total = $nominal;
            $salary->status = 1;
            $salary->save();

            Debit::where('id', $data->id_debit)->update(['debitdesc' => ((int)$data->debitdesc + (int)$nominal)]);
            Credit::where('id', $data->id_credit)->update(['creditdesc' => ((int)$data->creditdesc + (int)$nominal)]);
            Financials::where('id', $data->id_financial)->update(['updated_by'=> Auth::user()->id]);
            $affected = Beban::where('id', $data->id)->update(['total' => ((int)$data->total + (int)$nominal)]);
            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Akuntansi',
                'description' => 'Menambah Data Gaji id = '. $data->id,
                'icon' => 'fas fa-dollar-sign',
                'ip_address' => $request->ip()
            ]);
            if($affected > 0)
            {
                return redirect('/salary')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data gaji Sudah Ditambah!</div>');exit;
            }
            else
            {
                return redirect('/salary')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data gaji gagal Ditambah!</div>');exit;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Detail Gaji';
        $balance = accounting::select('description')->where('ref', 101)->first();
        $role = [1=>'Admin', 'Reseller Produk', 'Reseller Non-Produk'];
        $time = $this->getMonthName(date('n')) . ' ' . date('Y');
        $gaji = Salary::select('salary.id')->addSelect('users.id as id_user')->addSelect('users.name')->addSelect('users.roles')->addSelect('total')->addSelect('status')->addSelect('salary.created_at')->join('users', 'salary.id_user', 'users.id')->where('salary.id', $id)->first();
        return view('core/showSalary', compact('title', 'time', 'gaji', 'balance', 'role'));
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
            'user' => 'required',
            'nominal' => 'required',
        ]);
        $nominal = str_replace(',', '', $request->nominal);
        $data = Salary::select('salary.id')->addSelect('salary.total')->addSelect('beban.total as total_beban')->addSelect('beban.id_financial')->addSelect('id_debit')->addSelect('id_credit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->join('beban', 'beban.id', 'salary.id_beban')->join('financial', 'financial.id', 'beban.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('salary.id', $id)->first();

        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', 600)->get(),true);
        accounting::where('ref', 101)->update(['description' => ((int)$akun['0']['description'] + $data->total) - $nominal]);
        accounting::where('ref', 600)->update(['description' => ((int)$akun['1']['description'] - $data->total) + $nominal]);

        Debit::where('id', $data->id_debit)->update(['debitdesc' => (((int)$data->debitdesc - $data->total) + (int)$nominal)]);
        Credit::where('id', $data->id_credit)->update(['creditdesc' => (((int)$data->creditdesc - $data->total ) + (int)$nominal)]);
        Financials::where('id', $data->id_financial)->update(['updated_by' => Auth::user()->id]);
        Beban::where('id', $data->id)->update(['total' => ($data->total_beban - $data->total) + $nominal]);
        $affected = Salary::where('id', $id)->update(['total' => ($nominal)]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Gaji id = '. $data->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0)
        {
            return redirect('/salary')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data gaji Sudah Diubah!</div>');exit;
        }
        else
        {
            return redirect('/salary')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data gaji gagal Diubah!</div>');exit;
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
        $gaji = Salary::select('salary.id')->addSelect('salary.total')->addSelect('beban.total as total_beban')->addSelect('salary.id_beban')->addSelect('financial.id as id_financial')->addSelect('financial.id_debit')->addSelect('financial.id_credit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->join('beban', 'beban.id', 'salary.id_beban')->join('financial', 'financial.id', 'beban.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('salary.id', $id)->first();
        $akun = json_decode(accounting::select('ref')->addSelect('description')->where('ref', 101)->orWhere('ref', 600)->get(),true);
        accounting::where('ref', 101)->update(['description' => (((int)$akun['0']['description'] + (int)$gaji->total))]);
        accounting::where('ref', 600)->update(['description' => (((int)$akun['1']['description'] - $gaji->total))]);
        Beban::where('id', $gaji->id_beban)->update(['total' => ($gaji->total_beban - $gaji->total)]);
        $affected = Salary::where('id', $id)->delete();

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menghapus Data Gaji id = '. $gaji->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);

        if($affected > 0)
        {
            return redirect('/salary')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Gaji Sudah Dihapus!</div>');exit;
        }
        else
        {
            return redirect('/salary')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Gaji gagal Dihapus!</div>');exit;
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
