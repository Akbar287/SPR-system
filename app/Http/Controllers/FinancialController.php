<?php

namespace App\Http\Controllers;

use App\accounting;
use App\Credit;
use App\Debit;
use App\Financials;
use App\historyPeople;
use App\Income;
use App\Materials;
use App\Orders;
use App\Outcome;
use App\Purchase;
use App\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FinancialController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        $title = 'Keuangan';
        return view('core/financial', compact('title'));
    }

    public function income()
    {
        $title = 'Pendapatan';
        $income = json_decode(Income::select('income.id')->addSelect('income.total')->addSelect('income.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('income.created_at')->join('financial', 'financial.id', 'income.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('income.created_at', 'desc')->get(), true);
        $ref = json_decode(accounting::select('name')->addSelect('ref')->get(), true);
        $total = 0;
        for($i=0;$i < count($income);$i++)
        {
            $total += $income[$i]['total'];
            $temp = explode(' ', $income[$i]['created_at']);
            $time = explode('-', $temp['0']);
            $income[$i]['created_at'] = $temp['1'] . ' ' . $time['2'] . ' ' . $this->getMonthName($time['1']) . ' ' . $time['0'];
            for($j=0;$j < count($ref);$j++)
            {
                if($income[$i]['refdebit'] == $ref[$j]['ref'])
                {
                    $income[$i]['refdebit'] = $ref[$j]['name'];
                }
                if($income[$i]['refcredit'] == $ref[$j]['ref'])
                {
                    $income[$i]['refcredit'] = $ref[$j]['name'];
                }
            }
        }
        $total = $this->rupiah($total);
        return view('core/income', compact('title', 'income', 'total'));
    }
    public function incomecreate()
    {
        $title = 'Tambah Pendapatan';
        $income = json_decode(Income::select('income.id')->addSelect('income.total')->addSelect('income.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('income.created_at')->join('financial', 'financial.id', 'income.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('income.created_at', 'desc')->get(), true);
        $total = 0;
        $ref = (accounting::select('name')->addSelect('ref')->get());
        for($i=0;$i < count($income);$i++)
        {
            $total += $income[$i]['total'];
            $temp = explode(' ', $income[$i]['created_at']);
            $time = explode('-', $temp['0']);
            $income[$i]['created_at'] = $temp['1'] . ' ' . $time['2'] . ' ' . $this->getMonthName($time['1']) . ' ' . $time['0'];
            for($j=0;$j < count($ref);$j++)
            {
                if($income[$i]['refdebit'] == $ref[$j]['ref'])
                {
                    $income[$i]['refdebit'] = $ref[$j]['name'];
                }
                if($income[$i]['refcredit'] == $ref[$j]['ref'])
                {
                    $income[$i]['refcredit'] = $ref[$j]['name'];
                }
            }
        }
        $total = $this->rupiah($total);
        return view('core/createincome', compact('title', 'ref', 'total'));
    }
    public function incomeedit()
    {
        $title = 'Pendapatan';
        $income = (Income::select('income.id')->addSelect('income.price')->addSelect('income.discount')->addSelect('income.total')->addSelect('income.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('income.invoice')->addSelect('income.image')->addSelect('income.created_at')->join('financial', 'financial.id', 'income.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('income.created_at', 'desc')->first());
        $ref = (accounting::select('name')->addSelect('ref')->get());
        return view('core/incomeedit', compact('title', 'income', 'ref'));
    }
    public function incomestore(Request $request)
    {
        $this->validate($request, [
            'income' => 'required',
            'discount' => 'required',
            'nota' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'description' => 'required',
        ]);

        $myID = Auth::user()->id;
        $incomes = str_replace(',', '', $request->income);
        $discount = str_replace(',', '', $request->discount);
        $total = (int)$incomes - (int)$discount;

        $name = 'nophoto.png';
        if(!is_null($request->file('imgincome'))){
            $file = $request->file('imgincome');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/income/', $name);
        }

        $debit = new Debit();
        $debit->refdebit = $request->debit;
        $debit->debitdesc = $total;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $request->credit;
        $credit->creditdesc = $total;
        $credit->save();

        $finance = new Financials();
        $finance->id_debit = $debit->id;
        $finance->id_credit = $credit->id;
        $finance->description = $request->description;
        $finance->created_by = $myID;
        $finance->save();

        $income = new Income();
        $income->id_financial = $finance->id;
        $income->price = $incomes;
        $income->discount = $discount;
        $income->total = $total;
        $income->image = $name;
        $income->invoice = $request->nota;
        $income->created_by = $myID;
        $income->save();

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){ if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] + $total)]);
        accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] + $total)]);

        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Menambah Data Pendapatan Non-Penjualan id = '. $income->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/income')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pendapatan Non-Penjualan Sudah Ditambah!</div>');exit;
    }
    public function incomeupdate(Request $request, $id)
    {
        $this->validate($request, [
            'income' => 'required',
            'discount' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'nota' => 'required',
            'description' => 'required',
        ]);

        $myID = Auth::user()->id;
        $incomes = str_replace(',', '', $request->income);
        $discount = str_replace(',', '', $request->discount);
        $total = (int)$incomes - (int)$discount;

        $debit = [];$credit = []; $financial = ['updated_by' => Auth::user()->id]; $income = ['updated_by' => Auth::user()->id];

        $oldData = (income::select('income.id')->addSelect('income.price')->addSelect('income.discount')->addSelect('income.total')->addSelect('income.id_financial')->addSelect('financial.id_debit')->addSelect('financial.id_credit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('income.invoice')->addSelect('income.image')->addSelect('income.created_at')->join('financial', 'financial.id', 'income.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('income.created_at', 'desc')->first());
        if(!is_null($request->file('imgincome'))){
            if (!is_null($oldData)) {
                if($oldData->image !== 'nophoto.png'){
                    File::delete(public_path() . '/img/income/' . $oldData->image);
                }
            }
            $file = $request->file('imgincome');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/income/', $name);
            $income['image'] = $name;
        }

        if($oldData->total != $request->total)
        {
            $debit['debitdesc'] = $total;
            $credit['creditdesc'] = $total;
            $income['total'] = $total;
            $income['price'] = $incomes;
            $income['discount'] = $discount;

        }
        if($oldData->refdebit != $request->debit)
        {
            $debit['refdebit'] = $request->debit;
        }
        if($oldData->refcredit != $request->credit)
        {
            $credit['refcredit'] = $request->credit;
        }
        if($oldData->invoice != $request->nota)
        {
            $income['invoice'] = $request->nota;
        }
        if($oldData->description != $request->description)
        {
            $financial['description'] = $request->description;
        }
        $temp = []; $oldtemp = [];
        $oldacc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $oldData->refcredit)->orWhere('ref', '=', $oldData->refdebit)->get(), true);
        for($i=0;$i<count($oldacc);$i++){
            if($oldData->refdebit == $oldacc[$i]['ref']) {$oldtemp['debit'] = $oldacc[$i]['description'];}
            if($oldData->refcredit == $oldacc[$i]['ref']) {$oldtemp['credit'] = $oldacc[$i]['description'];}
        }
        accounting::where('ref', $oldData->refdebit)->update(['description' => ($oldtemp['debit'] - (int)$oldData->debitdesc)]);
        accounting::where('ref', $oldData->refcredit)->update(['description' => ($oldtemp['credit'] - (int)$oldData->creditdesc)]);

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){
            if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}
            if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];}
        }
        accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] +$total)]);
        accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] + $total)]);

        $affected = Income::where('id', $id)->update($income);
        Debit::where('id', $oldData->id_debit)->update($debit);
        Credit::where('id', $oldData->id_credit)->update($credit);
        Financials::where('id', $oldData->id_financial)->update($financial);

        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Pendapatan Non-Penjualan id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0)
        {
            return redirect('/income')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pendapatan Non-Penjualan Sudah Diubah!</div>');exit;
        }
        else
        {
            return redirect('/income')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Pendapatan Non-Penjualan gagal Diubah!</div>');exit;
        }
    }
    public function incomedelete(Request $request, $id)
    {
        $financial = Income::select('id_financial')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->join('financial', 'financial.id', 'income.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->first();
        Financials::where('id', $financial->id_financial)->update(['deleted_by' => Auth::user()->id]);
        Income::where('id', $id)->update(['deleted_by' => Auth::user()->id]);

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $financial->refcredit)->orWhere('ref', '=', $financial->refdebit)->get(), true);$temp = [];
        for($i=0;$i<count($acc);$i++){ if($financial->refdebit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($financial->refcredit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        accounting::where('ref', $financial->refdebit)->update(['description' => (($temp['debit'] - $financial->debitdesc))]);
        accounting::where('ref', $financial->refcredit)->update(['description' => (($temp['credit'] - $financial->creditdesc))]);

        Financials::where('id', $financial->id_financial)->delete();
        $affected = Income::where('id', $id)->delete();
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menghapus Data Pendapatan Non-Penjualan id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0)
        {
            return redirect('/income')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pendapatan Non-Penjualan Sudah Dihapus!</div>');exit;
        }
        else
        {
            return redirect('/income')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Pendapatan Non-Penjualan gagal Dihapus!</div>');exit;
        }
    }
    //Pengeluaran
    public function outcome()
    {
        $title = 'Pengeluaran';
        $outcome = json_decode(Outcome::select('outcome.id')->addSelect('outcome.total')->addSelect('outcome.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('outcome.created_at')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('outcome.created_at', 'desc')->get(), true);
        $ref = json_decode(accounting::select('name')->addSelect('ref')->get(), true);
        $total = 0;
        for($i=0;$i < count($outcome);$i++)
        {
            $temp = explode(' ', $outcome[$i]['created_at']);
            $time = explode('-', $temp['0']);
            $outcome[$i]['created_at'] = $temp['1'] . ', '. $time['2'] . ' ' . $this->getMonthName($time['1']) . ' ' . $time['0'];
            $total += $outcome[$i]['total'];
            for($j=0;$j < count($ref);$j++)
            {
                if($outcome[$i]['refdebit'] == $ref[$j]['ref'])
                {
                    $outcome[$i]['refdebit'] = $ref[$j]['name'];
                }
                if($outcome[$i]['refcredit'] == $ref[$j]['ref'])
                {
                    $outcome[$i]['refcredit'] = $ref[$j]['name'];
                }
            }
        }
        $total = $this->rupiah($total);
        return view('core/outcome', compact('title', 'outcome', 'total'));
    }
    public function createoutcome()
    {
        $title = 'Tambah Pengeluaran';
        $outcome = Outcome::select(DB::raw('SUM(total) as total'))->first();
        $ref = (accounting::select('name')->addSelect('ref')->get());
        $total = $this->rupiah($outcome->total);
        return view('core/createoutcome', compact('title', 'ref', 'total'));
    }
    public function editoutcome($id)
    {
        $title = 'Ubah Data Pengeluaran';
        $outcome = (Outcome::select('outcome.id')->addSelect('outcome.price')->addSelect('outcome.discount')->addSelect('outcome.total')->addSelect('outcome.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('outcome.invoice')->addSelect('outcome.image')->addSelect('outcome.created_at')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('outcome.created_at', 'desc')->first());
        $ref = (accounting::select('name')->addSelect('ref')->get());
        return view('core/editoutcome', compact('title', 'outcome', 'ref'));
    }

    public function storeoutcome(Request $request)
    {
        $this->validate($request, [
            'outcome' => 'required',
            'discount' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'nota' => 'required',
            'description' => 'required',
        ]);

        $myID = Auth::user()->id;
        $outcomes = str_replace(',', '', $request->outcome);
        $discount = str_replace(',', '', $request->discount);
        $total = (int)$outcomes - (int)$discount;

        $name = 'nophoto.png';
        if(!is_null($request->file('imgoutcome'))){
            $file = $request->file('imgoutcome');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/outcome/', $name);
        }

        $debit = new Debit();
        $debit->refdebit = $request->debit;
        $debit->debitdesc = $total;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $request->credit;
        $credit->creditdesc = $total;
        $credit->save();

        $finance = new Financials();
        $finance->id_debit = $debit->id;
        $finance->id_credit = $credit->id;
        $finance->description = $request->description;
        $finance->created_by = $myID;
        $finance->save();

        $outcome = new Outcome();
        $outcome->id_financial = $finance->id;
        $outcome->price = $outcomes;
        $outcome->discount = $discount;
        $outcome->total = $total;
        $outcome->image = $name;
        $outcome->invoice = $request->nota;
        $outcome->created_by = $myID;
        $outcome->save();

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){ if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        if($request->debit > 100 && $request->debit < 200){
            accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] + $total)]);
            accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] - $total)]);
        } else {
            accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] - $total)]);
            accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] - $total)]);
        }

        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Menambah Data Pengeluaran id = '. $outcome->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/outcome')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pengeluaran Sudah Ditambah!</div>');exit;
    }

    public function updateoutcome(Request $request, $id)
    {
        $this->validate($request, [
            'outcome' => 'required',
            'discount' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'nota' => 'required',
            'description' => 'required',
        ]);

        $myID = Auth::user()->id;
        $outcomes = str_replace(',', '', $request->outcome);
        $discount = str_replace(',', '', $request->discount);
        $total = (int)$outcomes - (int)$discount;

        $debit = [];$credit = []; $financial = ['updated_by' => Auth::user()->id]; $outcome = ['updated_by' => Auth::user()->id];

        $oldData = (Outcome::select('outcome.id')->addSelect('outcome.price')->addSelect('outcome.discount')->addSelect('outcome.total')->addSelect('outcome.id_financial')->addSelect('financial.id_debit')->addSelect('debit.debitdesc')->addSelect('credit.creditdesc')->addSelect('financial.id_credit')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('outcome.invoice')->addSelect('outcome.image')->addSelect('outcome.created_at')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('outcome.created_at', 'desc')->first());
        if(!is_null($request->file('imgoutcome'))){
            if (!is_null($oldData)) {
                if($oldData->image !== 'nophoto.png'){
                    File::delete(public_path() . '/img/outcome/' . $oldData->image);
                }
            }
            $file = $request->file('imgoutcome');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/outcome/', $name);
            $outcome['image'] = $name;
        }

        if($oldData->total != $request->total)
        {
            $debit['debitdesc'] = $total;
            $credit['creditdesc'] = $total;
            $outcome['total'] = $total;
            $outcome['price'] = $outcomes;
            $outcome['discount'] = $discount;

        }
        if($oldData->refdebit != $request->debit)
        {
            $debit['refdebit'] = $request->debit;
        }
        if($oldData->refcredit != $request->credit)
        {
            $credit['refcredit'] = $request->credit;
        }
        if($oldData->invoice != $request->nota)
        {
            $outcome['invoice'] = $request->nota;
        }
        if($oldData->description != $request->description)
        {
            $financial['description'] = $request->description;
        }

        $temp = []; $oldtemp = [];
        $oldacc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $oldData->refcredit)->orWhere('ref', '=', $oldData->refdebit)->get(), true);
        for($i=0;$i<count($oldacc);$i++){
            if($oldData->refdebit == $oldacc[$i]['ref']) {$oldtemp['debit'] = $oldacc[$i]['description'];}
            if($oldData->refcredit == $oldacc[$i]['ref']) {$oldtemp['credit'] = $oldacc[$i]['description'];}
        }
        if($request->debit > 100 && $request->debit < 200){
            accounting::where('ref', $oldData->refdebit)->update(['description' => ((int)$oldData->debitdesc) - $oldtemp['debit']]);
            accounting::where('ref', $oldData->refcredit)->update(['description' => ((int)$oldData->creditdesc) + $oldtemp['credit']]);
        } else {
            accounting::where('ref', $oldData->refdebit)->update(['description' => ((int)$oldData->debitdesc) + $oldtemp['debit']]);
            accounting::where('ref', $oldData->refcredit)->update(['description' => ((int)$oldData->creditdesc) + $oldtemp['credit']]);
        }
        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){
            if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}
            if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];}
        }
        accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] -$total)]);
        accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] - $total)]);

        Debit::where('id', $oldData->id_debit)->update($debit);
        Credit::where('id', $oldData->id_credit)->update($credit);
        Financials::where('id', $oldData->id_financial)->update($financial);
        $affected = Outcome::where('id', $id)->update($outcome);
        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Pengeluaran id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0)
        {
            return redirect('/outcome')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pengeluaran Sudah Diubah!</div>');exit;
        }
        else
        {
            return redirect('/outcome')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Pengeluaran gagal Diubah!</div>');exit;
        }
    }
    public function deleteoutcome(Request $request, $id)
    {
        $financial = Outcome::select('id_financial')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->first();

        Financials::where('id', $financial->id_financial)->update(['deleted_by' => Auth::user()->id]);
        Outcome::where('id', $id)->update(['deleted_by' => Auth::user()->id]);

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $financial->refcredit)->orWhere('ref', '=', $financial->refdebit)->get(), true);$temp = [];
        for($i=0;$i<count($acc);$i++){ if($financial->refdebit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($financial->refcredit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        if($request->debit > 100 && $request->debit < 200){
            accounting::where('ref', $financial->refdebit)->update(['description' => (( $financial->debitdesc - $temp['debit']))]);
            accounting::where('ref', $financial->refcredit)->update(['description' => (( $financial->creditdesc + $temp['credit']))]);
        } else {
            accounting::where('ref', $financial->refdebit)->update(['description' => (( $financial->debitdesc + $temp['debit']))]);
            accounting::where('ref', $financial->refcredit)->update(['description' => (( $financial->creditdesc + $temp['credit']))]);
        }
        Financials::where('id', $financial->id_financial)->delete();
        $affected = Outcome::where('id', $id)->delete();
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menghapus Data Pengeluaran id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        if($affected > 0)
        {
            return redirect('/outcome')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pengeluaran Sudah Dihapus!</div>');exit;
        }
        else
        {
            return redirect('/outcome')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Pengeluaran gagal Dihapus!</div>');exit;
        }
    }

    //Modal
    public function capital()
    {
        $title = 'Modal';
        $capitals = json_decode(Financials::select('financial.id')->addSelect('financial.description')->addSelect('debit.debitdesc')->addSelect('debit.refdebit')->addSelect('credit.creditdesc')->addSelect('credit.refcredit')->addSelect('users.name')->addSelect('financial.created_at')->join('debit', 'debit.id', 'id_debit')->join('credit', 'credit.id', 'id_credit')->join('users', 'users.id', 'financial.created_by')->where('debit.refdebit', 'like' , '3%')->orWhere('credit.refcredit', 'like' , '3%')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->get(), true);
        $ref = json_decode(accounting::select('name')->addSelect('ref')->get(), true);
        $plus = Financials::select(DB::raw('SUM(credit.creditdesc) as plus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('credit.refcredit', '=', 300)->first();
        $minus = Financials::select(DB::raw('SUM(debit.debitdesc) as minus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('debit.refdebit', '=', 301)->first();
        $total = $this->rupiah($plus->plus - $minus->minus);
        for($i=0;$i < count($capitals);$i++)
        {
            $temp = explode(' ', $capitals[$i]['created_at']);
            $time = explode('-', $temp['0']);
            $capitals[$i]['created_at'] = $temp['1'] . ', ' . $time['2'] . ' ' . $this->getMonthName($time['1']) . ' ' . $time['0'];
            for($j=0;$j < count($ref);$j++)
            {
                if($capitals[$i]['refdebit'] == $ref[$j]['ref'])
                {
                    $capitals[$i]['refdebit'] = $ref[$j]['name'];
                }
                if($capitals[$i]['refcredit'] == $ref[$j]['ref'])
                {
                    $capitals[$i]['refcredit'] = $ref[$j]['name'];
                }
            }
        }
        return view('core/capital', compact('title', 'capitals', 'ref', 'total'));
    }
    public function createCapital()
    {
        $title = 'Tambah Modal';
        $plus = Financials::select(DB::raw('SUM(credit.creditdesc) as plus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('credit.refcredit', '=', 300)->first();
        $minus = Financials::select(DB::raw('SUM(debit.debitdesc) as minus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('debit.refdebit', '=', 301)->first();
        $total = $this->rupiah($plus->plus - $minus->minus);
        $assets = accounting::select('ref')->addSelect('name')->where('ref', '<', 400)->get();
        return view('core/addcapital', compact('title', 'assets', 'total'));
    }
    public function editCapital($id)
    {
        $title = 'Ubah Modal';
        $plus = Financials::select(DB::raw('SUM(credit.creditdesc) as plus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('credit.refcredit', '=', 300)->first();
        $minus = Financials::select(DB::raw('SUM(debit.debitdesc) as minus'))->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('debit.refdebit', '=', 301)->first();
        $total = $this->rupiah($plus->plus - $minus->minus);
        $assets = accounting::select('ref')->addSelect('name')->where('ref', '<', 400)->get();
        $data = Financials::select('financial.id')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->addSelect('financial.description')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('financial.id', $id)->first();
        return view('core/editcapital', compact('title', 'assets', 'data', 'total'));
    }
    public function storecapital(Request $request)
    {
        $this->validate($request, [
            'capital' => 'required|max:128',
            'debit' => 'required',
            'credit' => 'required',
            'description' => 'required',
        ]);
        $capital = str_replace(',', '', $request->capital);
        $debit = new Debit();
        $debit->refdebit = $request->debit;
        $debit->debitdesc = $capital;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $request->credit;
        $credit->creditdesc = $capital;
        $credit->save();

        $finance = new Financials();
        $finance->id_debit = $debit->id;
        $finance->id_credit = $credit->id;
        $finance->description = $request->description;
        $finance->created_by = Auth::user()->id;
        $finance->save();

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){ if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] + $capital)]);
        accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] + $capital)]);

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menambah Modal id = '. $finance->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/capital')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Modal Sudah Ditambah!</div>');exit;
    }
    public function updatecapital(Request $request, $id)
    {
        $this->validate($request, [
            'capital' => 'required|max:128',
            'debit' => 'required',
            'credit' => 'required',
            'description' => 'required',
        ]);
        $capital = str_replace(',', '', $request->capital);
        $oldData = Financials::select('id_debit')->addSelect('id_credit')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('financial.id', $id)->first();

        $temp = []; $oldtemp = [];
        $oldacc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $oldData->refcredit)->orWhere('ref', '=', $oldData->refdebit)->get(), true);
        for($i=0;$i<count($oldacc);$i++){
            if($oldData->refdebit == $oldacc[$i]['ref']) {$oldtemp['debit'] = $oldacc[$i]['description'];}
            if($oldData->refcredit == $oldacc[$i]['ref']) {$oldtemp['credit'] = $oldacc[$i]['description'];}
        }
        accounting::where('ref', $oldData->refdebit)->update(['description' => ($oldtemp['debit'] - (int)$oldData->debitdesc)]);
        accounting::where('ref', $oldData->refcredit)->update(['description' => ($oldtemp['credit'] - (int)$oldData->creditdesc)]);

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $request->credit)->orWhere('ref', '=', $request->debit)->get(), true);
        for($i=0;$i<count($acc);$i++){
            if($request->debit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}
            if($request->credit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];}
        }
        accounting::where('ref', $request->debit)->update(['description' => ($temp['debit'] +$capital)]);
        accounting::where('ref', $request->credit)->update(['description' => ($temp['credit'] + $capital)]);

        Debit::where('id', $oldData->id_debit)->update(['refdebit' => $request->debit, 'debitdesc' => $capital]);
        Credit::where('id', $oldData->id_credit)->update(['refcredit' => $request->credit, 'creditdesc' => $capital]);
        Financials::where('id', $id)->update(['description' => $request->description, 'updated_by' => Auth::user()->id]);
        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Modal id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/capital')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Modal Sudah Diubah!</div>');exit;
    }
    public function deletecapital(Request $request, $id){
        $financial = Financials::select('id_debit')->addSelect('id_credit')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->where('financial.id', $id)->first();
        Financials::where('id', $id)->update(['deleted_by'=> Auth::user()->id]);

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', $financial->refcredit)->orWhere('ref', '=', $financial->refdebit)->get(), true);$temp = [];
        for($i=0;$i<count($acc);$i++){ if($financial->refdebit == $acc[$i]['ref']) {$temp['debit'] = $acc[$i]['description'];}if($financial->refcredit == $acc[$i]['ref']) {$temp['credit'] = $acc[$i]['description'];} }
        accounting::where('ref', $financial->refdebit)->update(['description' => (($temp['debit'] - $financial->debitdesc))]);
        accounting::where('ref', $financial->refcredit)->update(['description' => (($temp['credit'] - $financial->creditdesc))]);

        $affected = Financials::where('id', $id)->delete();
        if($affected > 0){
            historyPeople::create([
                'id_users' => Auth::user()->id,
                'title' => 'Akuntansi',
                'description' => 'Menghapus Data Modal id = '. $id,
                'icon' => 'fas fa-dollar-sign',
                'ip_address' => $request->ip()
            ]);
            return redirect('/capital')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Modal Sudah Dihapus!</div>');exit;
        } else {
            return redirect('/capital')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Modal gagal Diubah!</div>');exit;
        }
    }

    //Purchase
    public function purchase()
    {
        $title = 'Pembelian';
        $purchased = Purchase::select('purchase.id')->addSelect('vendor.name as vendor')->addSelect('materials.name as material')->addSelect('purchase.stock')->addSelect('purchase.price')->addSelect('purchase.discount')->addSelect('purchase.total')->join('vendor', 'vendor.id', 'purchase.id_vendor')->join('materials', 'materials.id', 'purchase.id_material')->whereYear('purchase.created_at', '=', date('Y'))->whereMonth('purchase.created_at', '=', date('m'))->get();
        $total = Purchase::select(DB::raw('SUM(total) as total'))->whereYear('purchase.created_at', '=', date('Y'))->whereMonth('purchase.created_at', '=', date('m'))->first();
        $total = $this->rupiah((int)$total->total);
        return view('core/purchase', compact('title', 'purchased', 'total'));
    }
    public function createpurchase()
    {
        $title = 'Tambah Catatan Pembelian';
        $vendors = Vendor::select('id')->addSelect('name')->get();
        $materials = Materials::select('id')->addSelect('name')->get();
        $ref = accounting::select('ref')->addSelect('name')->where('type', 1)->get();
        return view('core/createpurchase', compact('title', 'vendors', 'materials', 'ref'));
    }
    public function editpurchase($id)
    {
        $title = 'Ubah Catatan Pembelian';
        $purchase = Purchase::select('purchase.id')->addSelect('purchase.id_vendor')->addSelect('purchase.id_material')->addSelect('purchase.stock')->addSelect('purchase.price')->addSelect('purchase.discount')->addSelect('purchase.total')->addSelect('purchase.invoice')->addSelect('purchase.stock')->addSelect('financial.description')->addSelect('purchase.image')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->join('financial', 'financial.id', 'purchase.id_financial')->join('debit', 'financial.id_debit', 'debit.id')->join('credit', 'financial.id_credit', 'credit.id')->where('purchase.id', $id)->first();
        $vendors = Vendor::select('id')->addSelect('name')->get();
        $materials = Materials::select('id')->addSelect('name')->get();
        $ref = accounting::select('ref')->addSelect('name')->where('type', 1)->get();
        return view('core/editpurchase', compact('title', 'vendors', 'materials', 'ref', 'purchase'));
    }
    public function storepurchase(Request $request)
    {
        $this->validate($request, [
            'purchase' => 'required|max:128',
            'nota' => 'required',
            'vendor' => 'required',
            'material' => 'required',
            'stock' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'discount' => 'required',
            'description' => 'required',
        ]);
        $name = 'nophoto.png';
        if(!is_null($request->file('imgpurchase'))){
            $file = $request->file('imgpurchase');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/purchase/', $name);
        }
        $myID = Auth::user()->id;
        $purchased = str_replace(',', '', $request->purchase);
        $discount = str_replace(',', '', $request->discount);
        $total = ((int)$purchased - (int)$discount);

        $debit = new Debit();
        $debit->refdebit = $request->debit;
        $debit->debitdesc = $total;
        $debit->save();

        $credit = new Credit();
        $credit->refcredit = $request->credit;
        $credit->creditdesc = $total;
        $credit->save();

        $finance = new Financials();
        $finance->id_debit = $debit->id;
        $finance->id_credit = $credit->id;
        $finance->description = $request->description;
        $finance->created_by = $myID;
        $finance->save();

        $purchase = new Purchase();
        $purchase->id_vendor = $request->vendor;
        $purchase->id_financial = $finance->id;
        $purchase->id_material = $request->material;
        $purchase->stock = $request->stock;
        $purchase->price = $purchased;
        $purchase->discount = $discount;
        $purchase->total = $total;
        $purchase->image = $name;
        $purchase->invoice = $request->nota;
        $purchase->created_by = $myID;
        $purchase->save();

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', 500)->orWhere('ref', '=', 501)->orWhere('ref', '=', 502)->orWhere('ref', '=', 101)->orWhere('ref', '=', 117)->get(), true);
        accounting::where('ref', 500)->update(['description' => ($acc['0']['description'] + $purchased)]); // Pembelian
        //accounting::where('ref', 501)->update(['description' => ($acc['1']['description'] + $total)]); // Beban Angkut Pembelian
        accounting::where('ref', 502)->update(['description' => ($acc['2']['description'] + $discount)]); // Potongan Pembelian
        accounting::where('ref', 101)->update(['description' => ($acc['3']['description'] - $total)]); // Potongan Pembelian
        accounting::where('ref', 117)->update(['description' => ($acc['4']['description'] + $total)]); // Potongan Pembelian

        $material = Materials::select('stock')->where('id', $request->material)->first();
        Materials::where('id', $request->material)->update(['stock' => ($material->stock + $request->stock)]);
        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Membeli Bahan Mentah id = '. $purchase->id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/purchase')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pembelian Sudah Ditambah!</div>');exit;
    }
    public function updatepurchase(Request $request, $id)
    {
        $this->validate($request, [
            'purchase' => 'required|max:128',
            'nota' => 'required',
            'vendor' => 'required',
            'material' => 'required',
            'stock' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'discount' => 'required',
            'description' => 'required',
        ]);
        $oldData = Purchase::select('purchase.id')->addSelect('purchase.id_vendor')->addSelect('purchase.id_material')->addSelect('purchase.stock')->addSelect('purchase.price')->addSelect('purchase.discount')->addSelect('purchase.total')->addSelect('purchase.invoice')->addSelect('purchase.stock')->addSelect('financial.description')->addSelect('purchase.image')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('purchase.id_financial')->addSelect('financial.id_debit')->addSelect('financial.id_credit')->join('financial', 'financial.id', 'purchase.id_financial')->join('debit', 'financial.id_debit', 'debit.id')->join('credit', 'financial.id_credit', 'credit.id')->where('purchase.id', $id)->first();

        $update = [];
        if(!is_null($request->file('imgpurchase'))){
            if (!is_null($oldData)) {
                if($oldData->image !== 'nophoto.png'){
                    File::delete(public_path() . '/img/purchase/' . $oldData->image);
                }
            }
            $file = $request->file('imgpurchase');
            $name = $this->imgRandom($file->getClientOriginalName());
            $file->move(public_path() . '/img/purchase/', $name);
            $update['image'] = $name;
        }
        $myID = Auth::user()->id;
        $purchased = str_replace(',', '', $request->purchase);
        $discount = str_replace(',', '', $request->discount);
        $total = ((int)$purchased - (int)$discount);
        $finance = [];
        if($oldData->total != $total){
            $update['total'] = $total;
            if($oldData->price != $purchased){
                $update['price'] = $purchased;
            }
            if($oldData->discount != $discount){
                $update['discount'] = $discount;
            }
        }
        if($oldData->description != $request->description){
            $finance = [ 'description' => $request->description, 'updated_by' => $myID];
        }
        if($oldData->stock != $request->stock){
            $update['stock'] = $request->stock;
        }
        if($oldData->nota != $request->nota){
            $update['invoice'] = $request->nota;
        }
        if($oldData->id_material != $request->material){
            $update['id_material'] = $request->material;
        }
        if($oldData->id_vendor != $request->vendor){
            $update['id_vendor'] = $request->vendor;
        }

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', 500)->orWhere('ref', '=', 501)->orWhere('ref', '=', 502)->orWhere('ref', '=', 101)->orWhere('ref', '=', 117)->get(), true);
        accounting::where('ref', 500)->update(['description' => ($acc['0']['description'] - $oldData->price) + $purchased]);
        accounting::where('ref', 502)->update(['description' => ($acc['2']['description'] - $oldData->discount) + $discount]);
        accounting::where('ref', 101)->update(['description' => ($acc['3']['description'] - $oldData->total) + $total]); // Potongan Pembelian
        accounting::where('ref', 117)->update(['description' => ($acc['4']['description'] - $oldData->total) + $total]); // Potongan Pembelian

        $update['updated_by'] = Auth::user()->id;
        $material = Materials::select('stock')->where('id', $oldData->id_material)->first();
        Materials::where('id', $oldData->id_material)->update(['stock' => (((int)$material->stock - (int)$oldData->stock))]);

        $material = Materials::select('stock')->where('id', $request->material)->first();
        Materials::where('id', $request->material)->update(['stock' => ($material->stock + $request->stock)]);

        Purchase::where('id', $id)->update($update);
        Debit::where('id', $oldData->id_debit)->update(['refdebit' => $request->debit, 'debitdesc' =>$total ]);
        Credit::where('id', $oldData->id_credit)->update(['refcredit' => $request->credit, 'creditdesc' =>$total ]);
        Financials::where('id', $oldData->id_financial)->update($finance);

        historyPeople::create([
            'id_users' => $myID,
            'title' => 'Akuntansi',
            'description' => 'Mengubah Data Bahan Mentah id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/purchase')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pembelian Sudah Diubah!</div>');exit;
    }
    public function deletepurchase(Request $request, $id)
    {
        $oldData = Purchase::select('purchase.id')->addSelect('purchase.price')->addSelect('purchase.discount')->addSelect('purchase.id_material')->addSelect('purchase.stock')->addSelect('purchase.stock')->addSelect('purchase.id_financial')->join('financial', 'financial.id', 'purchase.id_financial')->where('purchase.id', $id)->first();
        $material = Materials::select('stock')->where('id', $oldData->id_material)->first();
        Materials::where('id', $oldData->id_material)->update(['stock' => (((int)$material->stock - (int)$oldData->stock))]);
        Financials::where('id', $oldData->id_financial)->update(['deleted_by' => Auth::user()->id]);
        Financials::where('id', $oldData->id_financial)->delete();
        Purchase::where('id', $id)->update(['deleted_by' => Auth::user()->id]);
        Purchase::where('id', $id)->delete();

        $acc = json_decode(accounting::select('description')->addSelect('ref')->where('ref', '=', 500)->orWhere('ref', '=', 501)->orWhere('ref', '=', 502)->orWhere('ref', '=', 101)->orWhere('ref', '=', 117)->get(), true);
        accounting::where('ref', 500)->update(['description' => ($acc['0']['description'] - $oldData->price)]); // Pembelian
        //accounting::where('ref', 501)->update(['description' => ($acc['1']['description'] - $total)]); // Beban Angkut Pembelian
        accounting::where('ref', 502)->update(['description' => ($acc['2']['description'] - $oldData->discount)]); // Potongan Pembelian
        accounting::where('ref', 101)->update(['description' => ($acc['3']['description'] - $oldData->total)]); // Potongan Pembelian
        accounting::where('ref', 117)->update(['description' => ($acc['4']['description'] - $oldData->total)]); // Potongan Pembelian

        historyPeople::create([
            'id_users' => Auth::user()->id,
            'title' => 'Akuntansi',
            'description' => 'Menghapus Data Pembelian id = '. $id,
            'icon' => 'fas fa-dollar-sign',
            'ip_address' => $request->ip()
        ]);
        return redirect('/purchase')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Pembelian Sudah Dihapus!</div>');exit;
    }

    //HPP
    public function hpp()
    {
        $title = 'Beban Pokok Penjualan';
        $total = Purchase::select(DB::raw('SUM(total) as total'))->whereYear('purchase.created_at', '=', date('Y'))->whereMonth('purchase.created_at', '=', date('m'))->first();
        $total = $this->rupiah((int)$total->total);
        $persediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', date('Y'))->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', date('Y'))->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $persediaan = (int)$persediaan['debit']['total'] - (int)$persediaan['credit']['total'];
        $oldpersediaan = ['debit' => Debit::select(DB::raw('SUM(debitdesc) as total'))->whereYear('financial.created_at', '=', (date('Y') -1))->where('debit.refdebit', '=', 102)->join('financial', 'debit.id', 'financial.id_debit')->where('financial.deleted_at', NULL)->first(),'credit' => Credit::select(DB::raw('SUM(creditdesc) as total'))->whereYear('financial.created_at', '=', (date('Y') -1))->where('credit.refcredit', '=', 102)->join('financial', 'credit.id', 'financial.id_credit')->where('financial.deleted_at', NULL)->first() ];
        $oldpersediaan = (int)$oldpersediaan['debit']['total'] - (int)$oldpersediaan['credit']['total'];
        $pembelian = Purchase::select(DB::raw('SUM(price) as price'))->addSelect(DB::raw('SUM(discount) as discount'))->addSelect(DB::raw('SUM(total) as total'))->first();
        return view('core/hpp', compact('title', 'persediaan', 'oldpersediaan', 'pembelian', 'total'));
    }
    public function hppstore(Request $request)
    {
        $this->validate($request, [
            'bpp' => 'required',
        ]);
        $bpp = str_replace(',', '', $request->bpp);
        $affected = accounting::where('id', 44)->update(['description' => $bpp]);
        if($affected > 0)
        {
            return redirect('/purchase/hpp')->with('status', '<div class="alert alert-success" role="alert"><strong>Sukses</strong>, Data Beban Pokok Penjualan Sudah Diubah!</div>');exit;
        } else {
            return redirect('/purchase/hpp')->with('status', '<div class="alert alert-danger" role="alert"><strong>Gagal</strong>, Data Beban Pokok Penjualan gagal Diubah!</div>');exit;
        }
    }

    //Statement
    public function statement()
    {
        $title = 'Laporan Keuangan';
        $ref = json_decode(accounting::select('ref')->addSelect('name')->get(), true);
        $finance = json_decode(Financials::select('financial.id')->addSelect('financial.description')->addSelect('debit.refdebit')->addSelect('debit.debitdesc')->addSelect('credit.refcredit')->addSelect('credit.creditdesc')->addSelect('financial.created_at')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('financial.created_at', 'asc')->get(), true);
        $total['debit'] = 0;
        $total['credit'] = 0;
        for($i=0;$i < count($finance);$i++)
        {
            $total['debit'] += $finance[$i]['debitdesc'];
            $total['credit'] += $finance[$i]['creditdesc'];
            $temp = explode(' ', $finance[$i]['created_at'], -1);
            $temp = explode('-', $temp['0']);
            $finance[$i]['created_at'] = $temp['2'];
            for($j=0;$j < count($ref);$j++)
            {
                if($ref[$j]['ref'] == $finance[$i]['refdebit']){
                    $finance[$i]['debit'] = $ref[$j]['name'];
                }
                if($ref[$j]['ref'] == $finance[$i]['refcredit']){
                    $finance[$i]['credit'] = $ref[$j]['name'];
                }
            }
        }
        return view('core/statement', compact('title', 'finance', 'ref', 'total'));
    }

    //Penjualan
    public function penjualanindex()
    {
        $title = 'Penjualan';
        $penjualan = json_decode(Orders::select('orders.id')->addSelect('users.name as reseller')->addSelect('markets.name as market')->addSelect('total_price')->addSelect('status')->addSelect('orders.created_at')->join('users', 'users.id', 'orders.id_users')->join('markets', 'markets.id', 'orders.id_markets')->whereYear('orders.created_at', '=', date('Y'))->whereMonth('orders.created_at', '=', date('m'))->where('status', '=', 4)->orWhere('status', '=', 6)->orderBy('orders.created_at', 'DESC')->get(), true);
        $total = 0;
        if(count($penjualan) > 0){
            for($i=0;$i<count($penjualan);$i++)
            {
                $total += $penjualan[$i]['total_price'];
                $time = explode(' ', $penjualan[$i]['created_at']);
                $temp = explode('-', $time['0']);
                $penjualan[$i]['created_at'] = $time['1'] . ' '.$temp['2'] . ' ' . $this->getMonthName($temp['1']) . ' ' . $temp['0'];
            }
        }
        return view('core/penjualan', compact('title', 'penjualan', 'total'));
    }
    // Tools
    private function imgRandom($img)
    {
        $img = (explode('.', $img));
        $ekstensi = $img[count($img) - 1];
        unset($img[count($img) - 1]);
        $img = implode('.', $img) . '.' . time() . '.' . $ekstensi;
        return $img;
    }
    function rupiah($angka){

        $hasil_rupiah = "Rp " . number_format($angka,2,'.',',');
        return $hasil_rupiah;

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


// //Penjualan
// public function sales()
// {
//     $title = 'Penjualan';
//     $sales = json_decode(Outcome::select('outcome.id')->addSelect('outcome.total')->addSelect('outcome.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('outcome.created_at')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('outcome.created_at', 'desc')->get(), true);
//     $ref = json_decode(accounting::select('name')->addSelect('ref')->get(), true);
//     $total = 0;
//     for($i=0;$i < count($sales);$i++)
//     {
//         $total += $sales[$i]['total'];
//         for($j=0;$j < count($ref);$j++)
//         {
//             if($sales[$i]['refdebit'] == $ref[$j]['ref'])
//             {
//                 $sales[$i]['refdebit'] = $ref[$j]['name'];
//             }
//             if($sales[$i]['refcredit'] == $ref[$j]['ref'])
//             {
//                 $sales[$i]['refcredit'] = $ref[$j]['name'];
//             }
//         }
//     }
//     $total = $this->rupiah($total);
//     return view('core/sales', compact('title', 'sales', 'total'));
// }
// public function createsales()
// {
//     $title = 'Tambah Data Penjualan';
//     $outcome = Outcome::select(DB::raw('SUM(total) as total'))->first();
//     $ref = (accounting::select('name')->addSelect('ref')->get());
//     $total = $this->rupiah($outcome->total);
//     return view('core/createoutcome', compact('title', 'ref', 'total'));
// }
// public function editsales($id)
// {
//     $title = 'Ubah Data Penjualan';
//     $outcome = (Outcome::select('outcome.id')->addSelect('outcome.price')->addSelect('outcome.discount')->addSelect('outcome.total')->addSelect('outcome.id_financial')->addSelect('debit.refdebit')->addSelect('credit.refcredit')->addSelect('financial.description')->addSelect('outcome.invoice')->addSelect('outcome.image')->addSelect('outcome.created_at')->join('financial', 'financial.id', 'outcome.id_financial')->join('debit', 'debit.id', 'financial.id_debit')->join('credit', 'credit.id', 'financial.id_credit')->whereYear('financial.created_at', '=', date('Y'))->whereMonth('financial.created_at', '=', date('m'))->orderBy('outcome.created_at', 'desc')->first());
//     $ref = (accounting::select('name')->addSelect('ref')->get());
//     return view('core/editoutcome', compact('title', 'outcome', 'ref'));
// }
