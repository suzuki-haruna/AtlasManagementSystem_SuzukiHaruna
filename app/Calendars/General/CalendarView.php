<?php
namespace App\Calendars\General;

use Carbon\Carbon; // 日付や時刻の操作
use Auth;

use App\Models\Calendars\ReserveSettings; // 追加

class CalendarView{

  private $carbon; // アクセス制限を表す修飾子、クラス内部でのみ使用されるプロパティ、クラス外部から直接$carbonを操作することを防ぐ。
  function __construct($date){ // 初期化処理($dateという値を受け取る)
    $this->carbon = new Carbon($date); // $dateをベースにCarbonオブジェクトが生成される
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月'); // 格納されたCarbonインスタンスの日付をフォーマットして返す
  }

  function render(){
    $html = []; // $htmlという名前の変数を空の配列として初期化する/[]空の配列(Array)
    $html[] = '<div class="calendar text-center">'; // 配列$htmlに新しい要素を追加
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks(); // $weeks = getWeeksメソッド(下にあり)を呼び出す。カレンダーの週データを取得。
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">'; // tr一週間分の行程
      /* getClassName App\Calendars～モデルに6つあり
      Admin→CalendarWeek/CalendarWeekBlankDay/CalendarWeekDay
      General→CalendarWeek/CalendarWeekBlankDay/CalendarWeekDay
      public無し */

      $days = $week->getDays(); // $days = 指定された週に書かれたすべての日を取得
      /* getDays App\Calendars～モデルに2つあり
      Admin→CalendarWeek/General→CalendarWeek */
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01"); // $startDay = 現在のCarbonインスタンスをコピーして、新しいインスタンスCarbonを作成（元のインスタンスCarbonは変更されない）->("Y-m-01")指定された形式でその月の1日の日付を取得。基準。
        $toDay = $this->carbon->copy()->format("Y-m-d"); // ("Y-m-d")現在の日付の取得

        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){ // if①($startDay月の最初の日付 <= $day->everyDay指定されたdayが戻ってくる特定の日付 && $toDay最新の日付 >= $day->everyDayカレンダーのガイドラインを参照)
          $html[] = '<td class="calendar-td past-day">'; // 追加past-day

          // 追加
          /*}else if($this->carbon->copy()->startOfDay()->isPast($day->everyDay())) { // Carbonに保存されているインスタンスの複製を作成->時間を無視して日付のみで比較->($day->everyDay())が現在の日付より前であるかをチェック
          }else if($this->carbon->copy()->startOfDay()->isToday($day->everyDay()) || $this->carbon->copy()->startOfDay()->isPast($day->everyDay())) {
          $html[] = '<td class="calendar-td past-date">';*/

        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">'; // ②$dayオブジェクトからgetClassNameカスタムクラス名を取得。その日に特定のスタイルやクラスを付ける。
        }
        $html[] = $day->render(); // $dayオブジェクトのrender()メソッド。その結果を$html[]配列に追加。

        if(in_array($day->everyDay(), $day->authReserveDay())){ // if①予約された日付：第1引数が配列内に存在する場合にtrueを返す($day->everyDay日付, $day->authReserveDay認証された予約日 のリストを取得＝$day->everyDayの返す日付が$day->authReserveDayのリストに含まれている場合、この条件はtrueを返す。
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part; // $reservePart = $day->authReserveDate($day->everyDay)指定された日付に関連する予約情報を取得->取得されたコレクションのうち最初のレコードを返す->最初のレコードからsetting_part属性の値を取得
          $reserveId = ReserveSettings::with('users')->get(); // 追加/中間テーブルのデータを含む予約データを取得
          $dataReserve = $day->everyDay();

          // 追加分岐
          if($day->everyDay()){
            if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){

            if($reservePart == 1){
            $reservePart = "1部参加";
            }else if($reservePart == 2){
            $reservePart = "2部参加";
            }else if($reservePart == 3){
            $reservePart = "3部参加";
            }
            /*$reservePartText = match ($reservePart) {
            1 => "1部参加",
            2 => "2部参加",
            3 => "3部参加",
            default => "受付終了",
            };*/

            /*else{ // 追加
            $reservePart = "受付終了";
            }*/ // ;いらない!?*/

            }else{
            if($reservePart == 1){
            $reservePart = "リモ1部";
            }else if($reservePart == 2){
            $reservePart = "リモ2部";
            }else if($reservePart == 3){
            $reservePart = "リモ3部";
            }
            }
          }

          // 過去
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">'. $reservePart .'</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">'; // 隠しフィールド：フィールド名getPart[] value=""設定する必要あるかも（'. $reservePart .'）とか formグループ"reserveParts"

          // 未来
          }else{
            //$html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'">'. $reservePart .'</button>';
            //$html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            //ddd($reservePart);

            //〇$html[] = '<a href="' . route('deleteParts', ['id' => $reserveId->first()->id]) . '" class="btn btn-danger p-0 w-75" style="font-size:12px" onclick="return confirm(\'予約日：' . $reserveId->first()->setting_reserve . '\n 時間：'. $reservePart .'\n 上記の予約をキャンセルしてもよろしいですか？\')">'. $reservePart .'</a>';
            /*$html[] = '<a href="' . route('deleteParts', ['id' => $reserveId->first()->id]) . '" class="open-modal btn btn-danger p-0 w-75" style="font-size:12px"
            data-id="' . $reserveId->first()->id . '"
            data-reserve="' . $reserveId->first()->setting_reserve . '"
            data-part="' . $reservePart . '">
            '. $reservePart .'</a>';*/
            // a href="#"!?/class="open-modalはjs用
            $html[] = '<a href="#" class="open-modal btn btn-danger p-0 w-75" style="font-size:12px"
            data-id="' . $reserveId->first()->id . '"
            data-reserve="' . $dataReserve . '"
            data-part="' . $reservePart . '">
            '. $reservePart .'</a>';

            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            //deleteParts/data-reserve="' . $reserveId->first()->setting_reserve . '"
            //ddd($reserveId);

            // モーダル：予約キャンセル
            $html[] = '<div id="custom-modal" class="modal"><div class="modal-content"><p id="modal-message"></p>
            <button id="cancel-button" class="btn btn-secondary">閉じる</button>
            <button id="confirm-button" class="btn btn-danger">キャンセル</button></div></div>';
            // class="modal"はCSS用
          }
        }
//★
        // 参加しなかった日//追加
        /*else{
          if ($day->everyDay() < $this->carbon->copy()->format("Y-m-d")) {…}
          //if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
          }//*/
        /*if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
        $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
        }*/
        /*$isPastOrToday = $startDay <= $day->everyDay() && $toDay >= $day->everyDay();
        if ($isPastOrToday) {
        $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
        }*/

          // 未来
          else{
          $html[] = $day->selectPart($day->everyDay());
          }//}

        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }
  // メソッド→getWeeks/getClassName/getDays/everyDay/authReserveDay/authReserveDate
  //ddd($reservePart);


  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
