<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::paginate(10);
        return EmployeeResource::collection($employee);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            'name'=>'required|max:200',
            'age'=>'required|min:1',
            'image'=>'',
            'phone'=>'required',
            'startworkdate'=>'required',
            'workingdays' =>'required',
            'gender'=>'required',
            'salary'=>'required',
   
        ]);
       $input = Employee::create([
            'name'=>$request->name,
            'age'=> $request->age,
            'phone'=> $request->phone,
            'startworkdate'=>$request->startworkdate,
            'gender'=>$request->gender,
            'workingdays' =>$request->workingdays,

            'salary'=>$request->salary,
            'image' =>$request->image,

        ]);



        return new EmployeeResource($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        return [
            new EmployeeResource($employee)
          ]; 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'max:200',
            'age'=>'min:2',
            'image'=>'',
            'phone'=>'',
            'startworkdate'=>'',
            'gender'=>'',
            'salary'=>'',
   
        ]);
        $employee = Employee::find($id);
        $employee ->update([
            'name'=>$request->name,
            'age'=> $request->age,
            'phone'=> $request->phone,
            'startworkdate'=>$request->startworkdate,
            'gender'=>$request->gender,
            'salary'=>$request->salary,
            'image' =>$request->image,

        ]);
        return [
            new EmployeeResource($employee)
          ]; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
       $employee->delete();
    }
}
