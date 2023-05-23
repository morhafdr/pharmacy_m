<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /*
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::get();
        return SupplierResource::collection($suppliers);
    }

    /*
     * Display a form for adding the resource.
     *
     * @return \Illuminate\Http\Response
     */
 

    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /*
     * Display the specified resource.
     *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        
        return new SupplierResource($supplier);
    }

    /*
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'email|string',
            'phone'=>'max:13',
            'company'=>'max:200|required',
            'address'=>'required|max:200',
            'description' =>'max:200',
        ]);

        $supplier->update($request->all());
       
        return new SupplierResource($supplier);
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return new SupplierResource($supplier);
    }
}