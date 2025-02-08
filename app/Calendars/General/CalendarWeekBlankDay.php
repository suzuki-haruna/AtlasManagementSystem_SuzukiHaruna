<?php
namespace App\Calendars\General;

class CalendarWeekBlankDay extends CalendarWeekDay{
  function getClassName(){
    return "day-blank"; // 表示範囲外の日
  }

  /**
   * @return
   */

   function render(){
     return '';
   }

   function selectPart($ymd){
     return '';
   }

   function getDate(){
     return '';
   }

   function cancelBtn(){
     return '';
   }

   function everyDay(){
     return '';
   }

}
