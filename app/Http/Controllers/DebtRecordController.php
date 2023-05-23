<?php

namespace App\Http\Controllers;

use App\Models\DebtRecord;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DebtRecordController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request,[
            // 'invoice_id'=>'required',
            'customer_name'=>'required|max:200',
            'debt_value'=>'required',
        ]);
        $cus= Invoice::query()->where('customer_name' , $request->customer_name)->latest()->first();
        
        DebtRecord::create
        ([
            'customer_name'=>$request->customer_name,
            'invoice_id'=> $cus->id,
            'debt_value'=> $request['debt_value'],
            'debt_date'=> Carbon::now(),

        ]);
        return response()->json(['message' => '  a-debt has been successsfully create'
    ]);
}

}