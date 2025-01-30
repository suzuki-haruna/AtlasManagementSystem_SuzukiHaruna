@extends('layouts.sidebar')

@section('content')
<div class="w-75 calendar_reservation">
  <div class="w-100 reservation">
    <p class="reservation_title">{{ $calendar->getTitle() }}</p>
    <p>{!! $calendar->render() !!}</p>
  </div>
</div>
@endsection
