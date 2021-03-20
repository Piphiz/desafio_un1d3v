<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $payedValue = 'R$ '. number_format(Bill::where('bill_type', '0')->where('bill_stats', '1')->sum('bill_amount'), 2, ',', '.');
        $toPayValue = 'R$ '. number_format(Bill::where('bill_type', '0')->where('bill_stats', '0')->sum('bill_amount'), 2, ',', '.');
        $recivedValue = 'R$ '. number_format(Bill::where('bill_type', '1')->where('bill_stats', '1')->sum('bill_amount'), 2, ',', '.');
        $toReciveValue = 'R$ '. number_format(Bill::where('bill_type', '1')->where('bill_stats', '0')->sum('bill_amount'), 2, ',', '.');

        $bills = Bill::orderBy('bill_date', 'asc')->get();

        return view('bills.index', compact('bills', 'payedValue', 'toPayValue', 'recivedValue', 'toReciveValue'));

    }

    public function store(BillRequest $request)
    {
        try{
            $data = $request->all();
            $data['bill_stats'] = 0;

            $bill = new Bill();
            $bill->create($data);

            $request->session()->flash('success', 'Registro Gravado com sucesso!');
        }catch(\Exception $e){
            $request->session()->flash('error', 'Ocorreu um erro ao tentar gravas esses dados!');
        }

        return redirect()->back();
    }

    public function update(BillRequest $request, Bill $bill)
    {
        try{
            $data = $request->all();
            $data['bill_stats'] = 1;
            $bill->update($data);

            $request->session()->flash('success', 'Registro atualizado com sucesso!');
        }catch(\Exception $e){
            $request->session()->flash('error', 'Ocorreu um erro ao tentar atualizar esses dados!');
        }

        return redirect()->back();
    }

    public function destroy(Request $request, Bill $bill)
    {
        try{
            $bill->delete();

            $request->session()->flash('success', 'Registro excluido com sucesso!');
        }catch(\Exception $e){
            $request->session()->flash('error', 'Ocorreu um erro ao tentar excluir esses dados!');
        }

        return redirect()->back();
    }

}
