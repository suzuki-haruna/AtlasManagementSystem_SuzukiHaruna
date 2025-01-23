$(function () {
  // カテゴリー検索
  $('.main_categories').click(function () { // クラスmain_categoriesがクリックされたときに実行
    var category_id = $(this).attr('category_id'); // 現在クリックされた要素(this)のcategory_id属性の値を取得してcategory_id変数に代入
    $('.category_num' + category_id).slideToggle(); // 要素に対して表示・非表示を切り替えるアニメーションを実行
    $(this).toggleClass('open'); // クラスを切り替えて矢印を制御
  });

  // いいね
  $(document).on('click', '.like_btn', function (e) { // $(DOM全体)like_btnがクリックされたら
    e.preventDefault(); // デフォルト動作を停止
    $(this).addClass('un_like_btn'); // $(like_btnがクリックされたら)新しいクラスun_like_btnを追加
    $(this).removeClass('like_btn'); // 「いいね」状態を削除
    var post_id = $(this).attr('post_id'); // 変数post_id = $(like_btnがクリックされたら)post_idの値を取得
    var count = $('.like_counts' + post_id).text();
    // 変数count：この変数には取得したテキストの値が代入される = $(クラスlike_counts + post_id).<span>テキスト内容取得</span>
    var countInt = Number(count); // 変数countInt：countの値を数値型に変換したものが代入される = (count)を数値型に変換
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // <meta name="csrf-token">タグをjQueryで選択、sidebarにあり.content属性の値(CSRFトークン)を取得。//☆
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1); //！ 1増加して更新
    }).fail(function (res) {
      console.log('fail');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });

  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
