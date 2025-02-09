<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd){
    $html = [];
    // 各部ごとの予約設定を取得
    $one_part = ReserveSettings::withCount('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first(); // with→withCount
    $two_part = ReserveSettings::withCount('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_part = ReserveSettings::withCount('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

//→
    $html[] = '<div class="text-left">';

    $html[] = '<table border="0">';

    if($one_part){
      // $html[] = '<p class="day_part m-0 pt-1">1部</p>';
      $html[] = '<tr class="day_part m-0 pt-1"><td><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 1]) . '">1部</a></td>
      <td>'. $one_part->users_count . '</td></tr>';
    }

    if($two_part){
      $html[] = '<tr class="day_part m-0 pt-1"><td><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 2]) . '">2部</a></td>
      <td>'.$two_part->users_count . '</td></tr>';
    }
    if($three_part){
      $html[] = '<tr class="day_part m-0 pt-1"><td><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 3]) . '">3部</a></td>
      <td>'. $three_part->users_count . '</td></tr>';
    }

    $html[] = '</table>';

    $html[] = '</div>';
//←

    return implode("", $html); // HTML文字列を返す
  }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
