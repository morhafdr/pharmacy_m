<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ResetCodePassword;
use  App\Mail\SendCodeResetPassword;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Confirm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as Password_role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
// use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail ;
use Illuminate\Support\Str;
class AuthController extends Controller

{
    public function UserRegister (Request $request) :JsonResponse{
    
        $validator = Validator::make($request->all(),[
            'name' =>['required' , 'max:55','string'],
            'email' =>['required' , 'email','unique:users'],
            'password'=>['required', 'confirmed', // password_confirmation
            Password_role::min(8)->
            numbers()->
            symbols()
        ],
            
            ]);
        if($validator->fails())
        {
          return response()->json('error',401);
        }
  
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::query()->create($input);
        $user->update([
    'role_id'  => 2,
        ]);
        $accessToken = $user->createToken('')->accessToken;
        
     return response()->json([
             'user' =>$user,
             'token' =>$accessToken,
         ],200); 
         }
         public function AdminRegister (Request $request) :JsonResponse{
    
          $validator = Validator::make($request->all(),[
              'name' =>['required' , 'max:55','string'],
              'email' =>['required' , 'email','unique:users'],
              'password'=>['required', 'confirmed', // password_confirmation
              Password_role::min(8)->
              numbers()->
              symbols()
          ],
              
              ]);
          if($validator->fails())
          {
            return response()->json('error',401);
          }
    
          $input = $request->all();
          $input['password'] = bcrypt($input['password']);
          $user = User::query()->create($input);
          $user->update([
      'role_id'  => 1,
          ]);
          $accessToken = $user->createToken('')->accessToken;
          
       return response()->json([
               'user' =>$user,
               'token' =>$accessToken,
           ],200); 
           }


    public function Login (Request $request) :JsonResponse {
        $request-> validate(['email' =>['required' , 'email'],
        'password'=>['required'],]);     
    
    
     if(auth()->attempt($request->only('email','password')))
     {
        $user = User::query()->find(auth()->user()['id']);
        $accessToken = $user->createToken('')->accessToken;
      return response()->json([
        'user' =>$user,
        'token' =>$accessToken,
         ],200); 
    }
   
      else
     {
       return response()->json(['error' => 'We cant find a user with that email address  
       || The provided password is incorrect.'],401);
     }


}


////forrget password
public function ForgetPassword (Request $request) {

    $data = $request->validate(['email' => 'required|email']);

    // Delete all old code that user send before.
    ResetCodePassword::where('email', $request->email)->delete();

    // Generate random code
    $data['code'] = mt_rand(100000, 999999);

    // Create a new code
    $codeData = ResetCodePassword::create($data);

    // Send email to user
    Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

    return response(['message' => trans('we send code to your email')], 200);
}
   

public function ResetPassword (Request $request) {
    $request->validate([
       'code' => 'required|string|exists:reset_code_passwords',
        'password' => 'required|min:8|confirmed',
    ]);
 //find the code 
 $passwordReset = ResetCodePassword::query()->firstWhere('code' ,$request['code']);
 //check if it is nor expired the time is one hour
 if ($passwordReset['created_at'] > now() ->addHour()){
$passwordReset->delete();
return response()->json(['message' => trans('passwords.code_is_expire')  ],422);
 }
//  else if ($passwordReset == null){

//     return response()->json(['message' => trans('the code is not true')  ],422);
//  }
// find user email
 $user = User::query()->firstWhere('email' , $passwordReset['email']);

 //update user password
 $input['password'] = bcrypt($request['password']); //

 $user -> update([
    'password' => $input['password'],
 ]); $user->save();


 // delete current code we can use DB   or use this $passwordReset->delete();

DB::table('reset_code_passwords')->where('email' ,$passwordReset['email'])->delete();
 return response()->json(['message' => 'password  has beem successsfully reset']);

 }
 public function logout(Request $request)
 {
    /**@var \App\Models\MyUserModel */
    $user = Auth:: user();
    $user->token()->revoke();  
    return response()->json(['seccess' => 'You Have Successfully Logout'],200);



     }

     public function GetEmployee()
     {
        $user = User::query()->where('role_id'  ,2 )->get();
        return $user;
       
        return response()->json([$user],200);
    
         }
    }






