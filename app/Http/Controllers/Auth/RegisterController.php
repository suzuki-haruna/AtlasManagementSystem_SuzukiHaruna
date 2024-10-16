<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

use App\Models\Users\Subjects;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function registerView()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    public function registerPost(Request $request)
    {
        // トランザクション処理
        DB::beginTransaction();
        try{

        //バリデーション
        /*$request->validate([
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|regex:/\A[ァ-ヴー]+\z/u|max:30',
            'under_name_kana' => 'required|string|regex:/\A[ァ-ヴー]+\z/u|max:30',
            'mail_address' => 'required|unique:users,mail_address|email|max:100',
            'sex' => 'required',
            'role' => 'required',
            'password' => 'confirmed|required|min:8|max:30',*/
            //'password' => 'confirmed|required|regex:/^[A-Za-z0-9]+$/u|min:8|max:30',

            //'birth_day' => 'required|date|before:'.date('Y-m-d'),
            /*'old_year' => 'required|numeric|birth_day:old_year,old_month,old_day',
            'old_month' => 'required|numeric',
            'old_day' => 'required|numeric',*/
            /*'old_year' => 'nullable|present|numeric|required_with:old_month,old_day',
            'old_month' => 'nullable|present|numeric|required_with:old_year,old_day',
            'old_day' => 'nullable|present|numeric|required_with:old_year,old_mouth',
            'birth_day' => 'nullable|date|before_or_equal:' . today()->format('Y-m-d'),*/
            //'birth_day' => 'date|before:today',　before:'.date('Y-m-d'),
            /*'old_year' => 'required',
            'old_month' => 'required',
            'old_day' => 'required',*/

        //]);

            $old_year = $request->old_year;
            $old_month = $request->old_month;
            $old_day = $request->old_day;
            $data = $old_year . '-' . $old_month . '-' . $old_day;
            $birth_day = date('Y-m-d', strtotime($data));
            $subjects = $request->subject;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);

            $user = User::findOrFail($user_get->id);
            $user->subjects()->attach($subjects);
            DB::commit(); // トランザクションで実行したSQLをすべて確定させる
            return view('auth.login.login',['msg'=>'OK']);
            }catch(\Exception $e){
            DB::rollback(); //トランザクションで実行したSQLをすべて破棄する
            return redirect()->route('loginView');
        }
    }
}
