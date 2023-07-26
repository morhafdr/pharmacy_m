<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResource;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller

{
    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $purchases = Purchase::paginate(10);
        return PurchaseResource::collection($purchases);
    
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:200',
            'category'=>'required',
            'net_price'=>'required|min:1',
            'salling_price'=>'required|min:1',
            'quantity'=>'required|min:1',
            'expiry_date'=>'required',
            'supplier'=>'required',
            // 'image'=>'file|image|mimes:jpg,jpeg,png,gif',
        ]);
        $imageName = null;
        if($request->hasFile('image')){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('storage/purchases'), $imageName);
        }

$num = Purchase::query()->where('name' , $request->name)->where('expiry_date' , $request->expiry_date)->first();
        if( $num == null)
 {       
      $input =  Purchase::create([
            'name'=>$request->name,
            'category_id'=>$request->category,
            'supplier_id'=>$request->supplier,
            'net_price'=>$request->net_price,
            'salling_price'=>$request->salling_price,
            'quantity'=>$request->quantity,
            'expiry_date'=>$request->expiry_date,
            'image'=>$imageName,
            'paracode' => $request->paracode,
        ]);
        // $id = Purchase::query()->where('name' , $request->name)->
        // where('category_id' , $request->category)
        // ->where('expiry_date' , $request->expiry_date)->first();


        $product = Product::create ([
            'product_name'=>$request->name,
            'price' => $request->salling_price,
            'quantity'=>$request->quantity,
            'purchase_id'=>$input['id'],
            'paracode' => $request->paracode,
        ]);

        $Date = ExperyDate::create ([
            'product_id'=>$product['id'],
            'expiry_date'=>$request->expiry_date,

        ]);
        // DB::table('products')->insert([
        //     'product_name'=>$request->name,
        //     'price' => $request->salling_price,
        //     'quantity'=>$request->quantity,
        //     'purchase_id'=>$input['id'],
        //     'paracode' => $request->paracode,
        // ]);

        // $notifications = array(
        //     'message'=>"Purchase has been added",
        //     'alert-type'=>'success',
        // );
        // return response()->json($notifications);
        // return response()->json(['message' => ' purchases has been successsfully create']);
        return [
            new PurchaseResource($input)
          ]; 
    }

        else {
            // $newqantiaty = $num ['quantity'] + $request->quantity ;
            Purchase::create([
                'name'=>$request->name,
                'category_id'=>$request->category,
                'supplier_id'=>$request->supplier,
                'net_price'=>$request->net_price,
                'salling_price'=>$request->salling_price,
                'quantity'=>$request->quantity,
                'expiry_date'=>$request->expiry_date,
                'paracode' => $request->paracode,
                'image'=>$imageName,
            ]);
            DB::table('products')->where('product_name' ,$num['name'])
            ->where('expiry_date' , $num['expiry_date'])->update([
               
                'quantity'=> $num ['quantity'] + $request->quantity,
            ]);

            return response()->json(['message' => 'quantity  has beem successsfully update']);

        }
    }
    public function show($id)
    {
        $purchase = Purchase::find($id);
        return [
            new PurchaseResource($purchase)
          ]; 

    }
    /*
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Purchase $purchase
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
       $this->validate($request,[
        'name'=>'max:200',
        'category'=>'',
        'net_price'=>'min:1',
        'salling_price'=>'min:1',
        'quantity'=>'min:1',
        'expiry_date'=>'',
        'supplier'=>'',
       ]);
       $imageName = null;
       if($request->hasFile('image')){
           $imageName = time().'.'.$request->image->extension();
           $request->image->move(public_path('storage/purchases'), $imageName);
       }
       $purchase = Purchase::find($id);
       $purchase->update([
           'name'=>$request->name,
           'category_id'=>$request->category,
           'supplier_id'=>$request->supplier,
           'net_price'=>$request->net_price,
           'salling_price'=>$request->salling_price,
           'quantity'=>$request->quantity,
           'expiry_date'=>$request->expiry_date,
           'image'=>$imageName,
       ]);
       return [
        new PurchaseResource($purchase)
      ]; 
      
   }

   public function destroy(Request $request)
   {
       $purchase = Purchase::find($request->id);
       $purchase->delete();
     
   }

}