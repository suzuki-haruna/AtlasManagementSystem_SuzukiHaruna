@extends('layouts.sidebar')

@section('content')
<div class="pt-5 pb-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5 calendar_style" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>
@endsection
