@extends('layouts.sidebar')

@section('content')
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="add_category">
      <p class="mb-0">カテゴリー</p>
      <select class="w-100 add_border" form="postCreate" name="post_category_id"><!--name="post_category_id"-->
        @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}" style="color: #999;">

          @foreach($sub_categories->where('main_category_id', $main_category->id) as $sub_category)<!--sub_categories=subCategories/sub_category=subCategory-->
          <option value="{{ $sub_category->id }}" style="color: #000;">{{ $sub_category->sub_category }}</option>
          @endforeach

        </optgroup>
        <!-- サブカテゴリー表示 -->
        <!--</optgroup>-->
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100 add_border" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100 add_border" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>

  <!-- カテゴリー -->
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">
      <div class="add_category">

        @if($errors->first('main_category_name'))
        <span class="error_message">{{ $errors->first('main_category_name') }}</span>
        @endif

        <p class="m-0 add_category">メインカテゴリー</p>
        <input type="text" class="w-100 add_border" name="main_category_name" form="mainCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
      </div>

      <!-- サブカテゴリー追加 -->
      <div class="add_category">

        @if($errors->first('main_category_id'))
        <span class="error_message">{{ $errors->first('main_category_id') }}</span>
        @endif
        <br>
        @if($errors->first('sub_category_name'))
        <span class="error_message">{{ $errors->first('sub_category_name') }}</span>
        @endif

        <p class="m-0 add_category">サブカテゴリー</p><!-- ★ -->

        <select class="w-100 add_border" name="main_category_id" form="subCategoryRequest"><!-- select(プルダウンメニュー) --><!--name="main_category_name"-->
          <option>---</option>
          @foreach($main_categories as $main_category)
          <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
          @endforeach
        </select>

        <input type="text" class="w-100 add_border" name="sub_category_name" form="subCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
      </div>

      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
      <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">{{ csrf_field() }}</form>
    </div>
  </div>
  @endcan
  <!-- /カテゴリー -->

</div>
@endsection
