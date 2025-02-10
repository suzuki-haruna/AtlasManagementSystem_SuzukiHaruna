<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar; //
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time()); // $calendar = CalendarView(最新日付を基準に)カレンダーを生成 //★

        $reserveId = ReserveSettings::with('users')->get(); // 追加/中間テーブルのデータを含む予約データを取得
        //$reserveId = ReserveSettings::with('users')->find($day->authReserveDate($day->everyDay())->first()->id);


        return view('authenticated.calendar.general.calendar', compact('calendar', 'reserveId'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // 追加
    public function delete(Request $request){ //($id){
        $reserveId = ReserveSettings::with('users')->get(); // 追加/中間テーブルのデータを含む予約データを取得


        //ReserveSettings::findOrFail($id)->delete();*/

        /*\DB::table('reserve_setting_users')
      ->where('reserve_setting_users.id', $id)
      ->delete();

      $user_id = Auth::user()->id;*/

      /*$reserveSetting = ReserveSettings::findOrFail($id);
      $reserveSetting->delete();*/

      // 指定されたIDのレコードをreserve_setting_usersテーブルから削除
    \DB::table('reserve_setting_users')->where('id', $id)->delete();

    // リクエストから必要なデータを取得
    /*$reserveId = $request->input('id');
    $reservePart = $request->input('part');

    // 条件に基づいてレコードを削除
    DB::table('reserve_setting_users')
        ->where('id', $reserveId)
        ->where('setting_part', $reservePart)
        ->delete();*/


        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);//, compact('reserveId'));
    }
}
