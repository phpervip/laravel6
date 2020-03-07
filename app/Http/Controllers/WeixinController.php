<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WeixinController extends Controller
{
    public function weixin(){
        return Socialite::with('weixin')->redirect();
    }

    public function weixinlogin(){
        $user = Socialite::driver('weixin')->user();
        dd($user);
        $check = User::where('uid',$user->id)->where('provider','qq_connect')->first();
        if(!$check){
            $customer = User::create([
                'uid'=>$user->id,
                'provider'=>'qq_connect',
                'name'=>$user->nickname,
                'email'=>'qq_connect+'.$user->id.'@example.com',
                'password'=>bcrypt(Str::random(60)),
                'avatar'=>$user->avatar
            ]);
        }else{
            $customer  =$check;
        }

        Auth::login($customer, true);
        return redirect('/');
    }
}
