<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
    class InvoiceController extends Controller
    {
    public function store(Request $request)
    {
     $request->validate
     ([
    'products' => 'required|array',
    'products.*.product_id' => 'required',
    'products.*.quantity' => 'required|integer|min:1',
     ]);
    
     $input = Invoice::create
     ([
     'total_invoices_price' => 0 ,
     ]);
     $data = json_decode($request->getContent(), true);
     $product = $data['products'];

     foreach($product as $p)        
     {
     $pr = Product::find($p['product_id']);
     if($pr['quantity'] >= $p['quantity'] ){
     DB::table('invoice_products')->insert
     ([
    'product_id'=>$p['product_id'],
    'quantity'=>$p['quantity'],
    'total_price'=>$p['quantity'] * $pr->price,
    'price' => $pr->price,
    'invoice_id'=>$input['id'],
        
    ]);
    Product::where('id' , $pr->id)->update
    ([
    'quantity'=> $pr->quantity   - $p['quantity'],
    ]);
     $total = $input->products()->sum('total_price');
     $input->update
     ([
    'total_invoices_price' => $total,
     ]);
     
          
    return response()->json(['message' => ' invoice has been successsfully create'
    ]);
}
    else 
     {
        $input->delete();
        return response()->json(['message' => ' there is not enough quantity  ', 'of the product' => $pr->product_name
    ]);
    }
}


}
}
