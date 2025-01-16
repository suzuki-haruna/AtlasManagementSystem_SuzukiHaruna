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
    public function delete(Request $request){

    // リクエストから必要なデータを取得
    $reserveId = $request->input('id');
    /*$reserveDate = $request->input('setting_reserve'); // 開講日/$dataReserve
    $reservePart = $request->input('setting_part'); // 部*/

    // reserve_settings テーブルから該当する予約を検索
    /*$reserveSetting = ReserveSettings::where('setting_reserve', $reserveDate)
        ->where('setting_part', $reservePart)
        ->first();*/

    // 条件に基づいてレコードを削除
    /*\DB::table('reserve_setting_users')
        ->where('reserve_setting_id', $reserveSetting->id)
        ->where('user_id', Auth::id()) // 現在のユーザーに限定
        ->delete();*/
        $deleted = \DB::table('reserve_setting_users')
        ->where('id', $reserveId)
        ->where('user_id', Auth::id())
        ->delete();

    // response消してもよい
    // 削除成功時
    if ($deleted) {
        return response()->json([
            'success' => true,
            'message' => '予約をキャンセルしました。'
        ]);
    }

    // 削除失敗時
    return response()->json([
        'success' => false,
        'message' => '削除できませんでした。'
    ]);

    //ReserveSettings::findOrFail($request)->delete();//$id

    // ここから下。このくらい短くても良いかもしれない。
    /*$reserveId = ReserveSettings::find($request->input('id'));
    $reserveId->delete();*/

    //〇return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);

    }
}
