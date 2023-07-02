<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type'=> 'employee',
            'attributes' => [

                'id' => $this->id,
                'name'=>$this->name,
                'age'=> $this->age,
                'phone'=> $this->phone,
                'startworkdate'=>$this->startworkdate,
                'workingdays'=>$this->workingdays,
                'gender'=>$this->gender,
                'salary'=>$this->salary,
                'image' =>$this->image,
            ]];
    }
}
