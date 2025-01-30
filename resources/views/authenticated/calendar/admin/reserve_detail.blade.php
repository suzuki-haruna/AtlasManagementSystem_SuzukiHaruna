@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    <p><span>{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }}</span><span class="ml-3">{{ $part }}部</span></p>
      <div class="detail_whopper">
      <table class="detail_table">
        <thead>
        <tr>
          <th class="detail_id">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>
        </thead>
<!--→-->
          <tbody class="detail_tbody">
          @foreach($reservePersons as $reservePerson)
<!--★-->
            @foreach($reservePerson->users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->over_name }}{{ $user->under_name }}</td>
              <td>リモート</td>
            </tr>
            @endforeach
          @endforeach
          </tbody>
      </table>
      </div>
  </div>
</div>
@endsection
