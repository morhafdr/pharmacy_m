<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Notifications\StockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('purchase')->get();
        return ProductResource::collection($products);
        
    
    }
    /**
     * Display a listing of expired resources.
     *
     * @return \Illuminate\Http\Response
     */
public function expired()
    {


            $expiredProducts = DB::table('products')
                ->join('expery_dates', 'products.id', '=', 'expery_dates.product_id')
                ->where('expery_dates.expiry_date', '<', date('Y-m-d'))
                ->select('products.product_name', 'products.price','products.paracode', 'expery_dates.expiry_date', 'expery_dates.quantity')
                ->get();


  if($expiredProducts == null)
{
    return response()->json(['message' => ' there are no expired products ']);
}
else

return $expiredProducts;

    }

    /**
     * Display a listing of out of stock resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function outstock()
    {
        $products = Product::where('quantity', '<=', 0)->get();
        $product = Product::where('quantity', '<=', 0)->first();

        if($product == null )  
        {
            return response()->json(['message' => ' there are no out of stock products ']);
        }
else {
    return $products 
        ;
          
}
    }
       
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'product_name'=>'required|max:200',
            'price'=>'required|min:1',
            'quantity'=>'required|min:1',
            'expiry_date'=>'required',
            'purchase_id'=>'required',
        ]);
        Product::create([
            'product_name'=>$request->product_name,
            'purchase_id'=> $request->purchase_id,
            'price'=> $request->price,
            'quantity'=>$request->quantity,
            'expiry_date'=>$request->expiry_date,
        ]);
      /*  $notification=array(
            'message'=>"Product has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('products')->with($notification);*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
       
        $product = Product::where('id' ,$id)->orWhere('paracode' ,$id) ->first()  ;
      
     return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product)
    {
        $this->validate($request,[

            'product_name'=>'required|max:200',
            'price'=>'required|min:1',
            'quantity'=>'required|min:1',
            'expiry_date'=>'required',
            'purchase_id'=>'required',
        ]);
        
       $product->update([
        'product_name'=>$request->product_name,
        'purchase_id'=> $request->purchase_id,
        'price'=> $request->price,
        'quantity'=>$request->quantity,
        'expiry_date'=>$request->expiry_date,
        ]);
    /*    $notification=array(
            'message'=>"Product has been updated",
            'alert-type'=>'success',
        );
        return redirect()->route('products')->with($notification);*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
      /*  $notification = array(
            'message'=>"Product has been deleted",
            'alert-type'=>'success',
        );
        return back()->with($notification);*/
    }
    public function search(Request $request)
    {
        // $product = Product::orderBy('expiry_date','desc')->first();
     $product = Product::where('product_name',$request->name)->orderBy('expiry_date','desc')->first();
     $product['quantity'] = Product::where('product_name',$request->name)->sum('quantity');
     return new  ProductResource($product);
    }
}

