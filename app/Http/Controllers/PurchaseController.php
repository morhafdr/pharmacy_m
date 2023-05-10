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
       
        $purchases = Purchase::with('category')->get();
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
        DB::table('products')->insert([
            'product_name'=>$request->name,
            'price' => $request->salling_price,
            'quantity'=>$request->quantity,
            'expiry_date'=>$request->expiry_date,
            'purchase_id'=>$input['id'],
            'paracode' => $request->paracode,
        ]);

 
        return response()->json(['message' => ' purchases has been successsfully create']);}

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
   public function update(Request $request, Purchase $purchase)
   {
       $this->validate($request,[
           'name'=>'required|max:200',
           'category'=>'required',
           'price'=>'required',
           'quantity'=>'required',
           'expiry_date'=>'required',
           'supplier'=>'required',
           'image'=>'file|image|mimes:jpg,jpeg,png,gif',
       ]);
       $imageName = null;
       if($request->hasFile('image')){
           $imageName = time().'.'.$request->image->extension();
           $request->image->move(public_path('storage/purchases'), $imageName);
       }
       
       $purchase->update([
           'name'=>$request->name,
           'category_id'=>$request->category,
           'supplier_id'=>$request->supplier,
           'price'=>$request->price,
           'quantity'=>$request->quantity,
           'expiry_date'=>$request->expiry_date,
           'image'=>$imageName,
       ]);
       /*
       $notifications = array(
           'message'=>"Purchase has been updated",
           'alert-type'=>'success',
       );
       return redirect()->route('purchases')->with($notifications);*/
   }

   /*
    * Remove the specified resource from storage.
    *
    * @param  \Illuminate\Http\Request
    * @return \Illuminate\Http\Response
    */
   public function destroy(Request $request)
   {
       $purchase = Purchase::find($request->id);
       $purchase->delete();
      /* $notification =array(
           'message'=>"Purchase has been deleted",
           'alert-type'=>'success'
       );
       return back()->with($notification);*/
   }

}