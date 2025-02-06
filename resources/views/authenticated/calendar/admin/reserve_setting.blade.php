@extends('layouts.sidebar')
@section('content')
<div class="" style="align-items:center; justify-content:center;"><!--d-flex-->
  <div class="" style="padding:auto;">
    <div class="frame_table">
    <p class="frame_title">{{ $calendar->getTitle() }}</p>
    {!! $calendar->render() !!}
    <div class="adjust-table-btn">
      <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
    </div>
    </div>
  </div>
</div>
@endsection
