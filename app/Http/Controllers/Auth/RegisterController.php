<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\Console\Input\Input;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/article';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'invite-code'=>'nullable|string'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {


        $newUser=User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'share'=>0
        ]);
        $userId=DB::table('users')->select('id')->where(['email'=>$data['email']])->first();
        debugbar()->info($userId);
        $userId=$userId->id;
        $parentId=0;
        $path=0;
        $parentPath=0;
        $level=0;
        if(!empty($data['invite-code'])){
            $parent=DB::table('users')->select('path','id')->where(['share'=>$data['invite-code']])->first();
            if(!empty($parent)){
                $parentId=$parent->id;
                $parentPath=$parent->path;
                debugbar()->info($parentId);
                debugbar()->info($parentPath);
                $path=$parentPath.'_'.$userId;
                $array=explode('-',$path);
                $level=count($array);
            }

        }

        $shareKey=KEY+$userId;
        debugbar()->info($shareKey);
        $update=DB::table('users')->where(['email'=>$data['email']])
            ->update(['share'=>KEY+$userId,
                      'parent_id'=>$parentId,
                      'path'=>$path,
                      'parent_path'=>$parentPath,
                      'level'=>$level]);

        debugbar()->info($update);
        return $newUser;
    }
}
