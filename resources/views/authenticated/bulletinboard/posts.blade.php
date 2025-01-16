@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p class="post_user"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}" class="posts">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">

          @foreach($post->subCategories as $subCategory)
          {{-- サブカテゴリー <p>{{ $subCategory->sub_category }}</p> --}}
          @if($subCategory->mainCategory)
          <div class="tag">{{ $subCategory->mainCategory->main_category }}</div><!-- メインカテゴリー -->
          @endif
          @endforeach

          <div class="post_icon">
            <div class="mr-5">
              <i class="fa fa-comment" style="color: #999;"></i><span>{{ $post->commentCounts($post->id) }}</span>
            </div>
            <!-- いいね -->
            <div class="like_icon">
              @if(Auth::user()->is_Like($post->id)) <!-- もし(ログインユーザー()->is_Like(post->id)) -- User.phpにis_Likeメソッドあり -->
              <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
              <!-- この投稿を「いいね」していないとき -->
              @else
              <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
              @endif
              <span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span>
            </div>
          </div>

      </div>
    </div>
    @endforeach
  </div>

  <div class="other_area w-25">
    <div class="m-4">
      <div class=""><a href="{{ route('post.input') }}" class="post_link">投稿</a></div>
      <div class="">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">
      </div>
      <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">

      <!-- 検索：カテゴリー -->
        <!--あとで使うかも/CSSてきとーとりあえずコピペ-->
        <!--<p class="m-0 search_conditions"><span>カテゴリー検索</span></p>
        <div class="search_conditions_inner">
          <div>
            <label>性別</label>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
          </div>
          </div>-->
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}</span><!--main_categories js-->
          <ul>
          @foreach($sub_categories->where('main_category_id', $category->id) as $sub_category)
          <li>
            <button type="submit" name="category_word" value="{{ $sub_category->id }}" form="postSearchRequest" class="category_btn">
            {{ $sub_category->sub_category }}</button>
          </li>
          @endforeach
          </ul>
        </li>
        @endforeach
      </ul>

    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
