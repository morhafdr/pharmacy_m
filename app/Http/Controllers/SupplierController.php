<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
   
    public function index()
    {
        $suppliers = Supplier::get();
        return SupplierResource::collection($suppliers);
    }

   
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'email|string',
            'phone'=>'max:13|required',
            'company'=>'max:200|required',
            'address'=>'max:200',
            'description' =>'max:200',
        ]);
        $supplier=Supplier::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'company'=>$request->company,
            'address'=>$request->address,
            'description'=>$request->description,
        ]);
        
        return new SupplierResource($supplier);
    }

    
    public function show(Supplier $supplier)
    {
        
        return new SupplierResource($supplier);
    }

 
    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request,[
            'name'=>'max:200',
            'email'=>'email|string',
            'phone'=>'max:13',
            'company'=>'max:200',
            'address'=>'max:200',
            'description' =>'max:200',
        ]);

        $supplier->update($request->all());
       
        return new SupplierResource($supplier);
    }

   
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
// return new SupplierResource($supplier);        
return response()->json(['message' => 'supplier  has beem successsfully delete']);

    }
}