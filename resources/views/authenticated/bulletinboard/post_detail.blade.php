@extends('layouts.sidebar')
@section('content')
<div class="vh-100 d-flex">
  <div class="w-50 mt-5">
    <div class="m-3 detail_container">
      <div class="p-3 detail_post">
        @if($errors->first('post_title'))
        <span class="error_message">{{ $errors->first('post_title') }}</span>
        @endif
        @if($errors->first('post_body'))
        <span class="error_message">{{ $errors->first('post_body') }}</span>
        @endif
        <div class="detail_inner_head">

          @foreach($post->subCategories as $subCategory)
          <div class="tag">{{ $subCategory->sub_category }}</div>
          @endforeach

          @if ($post->user_id == Auth::user()->id)
          <div style="background: #FFF;">
            <span class="edit-modal-open btn btn-primary" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
            <a href="{{ route('post.delete', ['id' => $post->id]) }}" onclick="return confirm('この投稿を削除します。よろしいでしょうか？')" class="btn btn-danger">削除</a>
          </div>
          @endif
        </div>

        <div class="contributor d-flex">
          <p class="detail_user">
            <span>{{ $post->user->over_name }}</span>
            <span>{{ $post->user->under_name }}</span>
            さん
          </p>
          <span class="ml-5">{{ $post->created_at }}</span>
        </div>
        <div class="detsail_post_title">{{ $post->post_title }}</div>
        <div class="mt-3 detsail_post">{{ $post->post }}</div>
      </div>
      <div class="p-3 detail_comment">
        <div class="comment_container" style="background: #FFF;">
          <span style="background: #FFF;">コメント</span>
          @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
              <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
              <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
            </p>
            <p>{{ $comment->comment }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="w-50 p-3">
    <div class="comment_container m-5">
      <div class="comment_area p-3">
        @if($errors->first('comment'))
        <span class="error_message">{{ $errors->first('comment') }}</span>
        @endif
        <p class="m-0" style="background: #FFF">コメントする</p>
        <textarea class="w-100" name="comment" form="commentRequest"></textarea>
        <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
        <div class="comment_input"><input type="submit" class="btn btn-primary" form="commentRequest" value="投稿"></div>
        <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
      </div>
    </div>
  </div>
</div>

<!-- モーダルの中身 -->
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('post.edit') }}" method="post">
      <div class="w-100" style="background: #FFF;">
        <div class="modal-inner-title w-50 m-auto">

          <input type="text" name="post_title" placeholder="タイトル" class="w-100 post_modal">
        </div>
        <div class="modal-inner-body w-50 m-auto pt-3 pb-3" style="background: #FFF;">

          <textarea placeholder="投稿内容" name="post_body" class="w-100 post_modal"></textarea>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex" style="background: #FFF;">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>
@endsection
