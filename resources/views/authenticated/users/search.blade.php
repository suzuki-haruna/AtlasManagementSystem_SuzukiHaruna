@extends('layouts.sidebar')

@section('content')
<div class="search_content w-100 border d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="one_person">
      <div class="search_items">
        <span class="search_item">ID : </span><span class="search_items">{{ $user->id }}</span>
      </div>
      <div class="search_items"><span class="search_item">名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span class="search_user">{{ $user->over_name }}</span>
          <span class="search_user">{{ $user->under_name }}</span>
        </a>
      </div>
      <div class="search_items">
        <span class="search_item">カナ : </span>
        <span class="search_items">({{ $user->over_name_kana }}</span>
        <span class="search_items">{{ $user->under_name_kana }})</span>
      </div>
      <div class="search_items">
        @if($user->sex == 1)
        <span class="search_item">性別 : </span><span class="search_items">男</span>
        @elseif($user->sex == 2)
        <span class="search_item">性別 : </span><span class="search_items">女</span>
        @else
        <span class="search_item">性別 : </span><span class="search_items">その他</span>
        @endif
      </div>
      <div class="search_items">
        <span class="search_item">生年月日 : </span><span class="search_items">{{ $user->birth_day }}</span>
      </div>
      <div class="search_items">
        @if($user->role == 1)
        <span class="search_item">役職 : </span><span class="search_items">教師(国語)</span>
        @elseif($user->role == 2)
        <span class="search_item">役職 : </span><span class="search_items">教師(数学)</span>
        @elseif($user->role == 3)
        <span class="search_item">役職 : </span><span class="search_items">講師(英語)</span>
        @else
        <span class="search_item">役職 : </span><span class="search_items">生徒</span>
        @endif
      </div>
      <div class="search_items">
        @if($user->role == 4)
        <span class="search_item">選択科目 :</span>
          @foreach($user->subjects as $subject)
          <span class="search_items">{{ $subject->subject }}</span>
          @endforeach
        @endif
      </div>
    </div>
    @endforeach
  </div>

  <!-- 検索 -->
  <div class="search_area w-25">
    <div class="" style="margin-top: 50px;">
      <p style="color: #191970;">検索</p>
      <div>
        <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div class="search_choose">
        <lavel class="search_lavel">カテゴリ</lavel>
        <select form="userSearchRequest" name="category" class="select_word">
          <option value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div class="search_choose">
        <label class="search_lavel">並び替え</label>
        <select name="updown" form="userSearchRequest" class="select_word">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="">
        <p class="m-0 search_conditions"><span>検索条件の追加</span></p>
        <div class="search_conditions_inner">
          <div class="conditions_inner">
            <label class="search_lavel">性別</label>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
          </div>
          <div class="conditions_inner">
            <label class="search_lavel">権限</label>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <div class="selected_engineer">
            <label class="search_lavel">選択科目</label>

            @foreach($subjects as $subjects)<!-- 繰り返し(subjects を subjects として扱う) -->
            <span>{{ $subjects->subject }}</span><!-- subjects の subjectカラム表示  -->
            <input type="checkbox" name="subjects[]" value="{{ $subjects->id }}" form="userSearchRequest">
            <!-- フォーム、チェックボックス データセットsubjects[]複数の配列で送る 初期入力値subjectsのid フォームグループuserSearchRequest --><!--☆-->
            {{-- {{ in_array($subjects->id, $subjectsIds ?? []) ? 'checked' : '' }} --}}
            @endforeach

          </div>
        </div>
      </div>
      <div>
        <input type="submit" name="search_btn" value="検索" form="userSearchRequest" class="search_btn">
      </div>
      <div>
        <input type="reset" value="リセット" form="userSearchRequest" class="search_reset">
      </div>
    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
@endsection
