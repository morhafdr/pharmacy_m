<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
    class InvoiceController extends Controller
    {
    public function store(Request $request)
    {
     $request->validate
     ([
        'customer_name'=>'required|max:200',
    'products' => 'required|array',
    'products.*.product_id' => 'required',
    'products.*.quantity' => 'required|integer|min:1' ,
     ]);

     $input = Invoice::create
     ([
     'total_invoices_price' => 0 ,
     'customer_name' => $request->customer_name,
     ]);
     $data = json_decode($request->getContent(), true);
     $product = $data['products'];

     foreach($product as $p)
     {

         $pr = Product::find($p['product_id']);


     if($pr['quantity'] >= $p['quantity'] )
     {
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



    // تحديد عدد المنتجات التي تم بيعها
// $quantityToSell = 10;
 // يمكن استبدال هذا بالقيمة المناسبة

// استعلام للحصول على منتجات المعلم مرتبة حسب تاريخ الانتهاء بترتيب تصاعدي
$products = DB::table('expery_dates')
    ->where('product_id', $pr->id) // حدد هنا رقم المنتج الذي ترغب في بيعه
    ->where('quantity', '>', 0) // حدد هنا للتأكد من أن الكمية المتوفرة أكبر من الصفر
    ->orderBy('expiry_date', 'asc')
    ->get();

// التحقق من وجود منتجات متاحة للبيع
if ($products->isNotEmpty()) {
    foreach ($products as $product) {
        // التحقق مما إذا كانت الكمية المتاحة في التاريخ الحالي تكفي للبيع
        if ($product->quantity >= $p['quantity']) {
            // إذا كانت الكمية تكفي، قم بتحديثها في جدول الـ expiry_date وانتهِ من العملية
            $updatedQty = $product->quantity - $p['quantity'];
            DB::table('expery_dates')
                ->where('id', $product->id)
                ->update(['quantity' => $updatedQty]);
            break; // انتهِ من الحلقة بمجرد تحديث الكمية

        } else {
            // إذا لم تكن الكمية تكفي، قم بتحديث الكمية المباعة واستمر في التفكير في التواريخ اللاحقة
            $p['quantity'] -= $product->quantity;
            DB::table('expery_dates')
                ->where('id', $product->id)
               ->delete();
        }
    }
    // يمكنك الآن القيام بالإجراءات الأخرى اللازمة لعملية البيع هنا
}

     $total = $input->products()->sum('total_price');
     $input->update
     ([
    'total_invoices_price' => $total,
     ]);



}
 response()->json(['message' => ' invoice has been successsfully create  ']);
    // else
    //  {
    //     // $input->delete();
    //     DB::table('invoice_products')->insert
    //  ([
    // 'product_id'=>$p['product_id'],
    // 'quantity'=>$pr['quantity'],
    // 'total_price'=>$pr['quantity'] * $pr->price,
    // 'price' => $pr->price,
    // 'invoice_id'=>$input['id'],

    // ]);
    // Product::where('id' , $pr->id)->update
    // ([
    // 'quantity'=> $pr->quantity   - $pr['quantity'],
    // ]);
    //  $total = $input->products()->sum('total_price');
    //  $input->update
    //  ([
    // 'total_invoices_price' => $total,
    //  ]);

    //     return response()->json(['message' => ' there is not enough quantity  ', 'of the product' => $pr->product_name
    //     , 'Quantity Available'  => $pr->quantity]);
    // }
}
}

public function TodaySales(Request $request){
    // $total_expired_products = Purchase::whereDate('expiry_date', '=', Carbon::now())->count();
    // $latest_sales = Sales::whereDate('created_at','=',Carbon::now())->get();
    $today_sales = Invoice::whereDate('created_at','=',Carbon::now())->sum('total_invoices_price');


    return response()->json(['sales_value' => $today_sales ]);


}
public function DaySales(Request $request){
    $request->validate
    ([
        'select_the_day'=>'required|date',
    ]);
    $day_sales = Invoice::whereDate('created_at','=',$request->select_the_day)->sum('total_invoices_price');
    return response()->json(['sales_value'=> $day_sales ]);
}

// public function Profet_T(Request $request){
//     $today_sales = Invoice::whereDate('created_at','=',Carbon::now())->sum('total_invoices_price');

//     $id = Invoice::whereDate('created_at','=',Carbon::now())->get('id');

//     foreach($id as $p_id)
//     {

//         $pr = $id->products()->where('invoice_id' , $p_id['id'])->get();


//     }
//     return  $pr;
//
// }

public function BestSelling(Request $request){ 
   
 
     
    $bestSellingProducts = DB::table('invoice_products') 
        ->select('invoice_products.product_id', 'products.product_name', 'products.quantity'  
        , 'products.price', 'products.expiry_date', 
     
            DB::raw('SUM(invoice_products.quantity) as selling_quantity')) 
     
        ->join('products', 'products.id', '=', 'invoice_products.product_id') 
        ->groupBy('invoice_products.product_id', 'products.product_name', 
         'products.quantity','products.price','products.expiry_date',) 
        ->orderBy('selling_quantity', 'desc') 
        ->take(10) 
        ->get(); 
    return $bestSellingProducts; 
                    
    }
public function dailyPurchases(Request $request){
    $today_Purchases = Purchase::whereDate('created_at','=',Carbon::now())->sum('net_price');

    if (!$today_Purchases) {
        return response()->json(['message' => 'there are no purcheses'], 404);
    }
    return response()->json(['Purchases_value'=> $today_Purchases ]);

}

    }