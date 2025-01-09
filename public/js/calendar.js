$(function () {

});

$(document).ready(function () { // $(ドキュメント全体の準備が整った時点で実行)イベントハンドラー
  // モーダルを開く
  $('.open-modal').on('click', function (e) {
    e.preventDefault();

    // データを取得
    const reserveId = $(this).data('id'); // reserve_setting_id!?
    const reserveDate = $(this).data('reserve'); // setting_reserve(日付)
    const reservePart = $(this).data('part'); // setting_part(部)

    // メッセージを更新
    $('#modal-message').html(`予約日: ${reserveDate}<br>時間: ${reservePart}<br>上記の予約をキャンセルしてもよろしいですか？`);

    // キャンセルボタンのクリック動作
    $('#cancel-button').off('click').on('click', function () {
      $('#custom-modal').hide();
    });

    // 確認ボタンのクリック動作
    $('#confirm-button').off('click').on('click', function () {
      // サーバーへ削除リクエストを送信
      $.post("<?= route('deleteParts') ?>", {
        id: reserveId,
        setting_reserve: reserveDate,
        setting_part: reservePart,
        _token: "<?= csrf_token() ?>"
      }, function (response) { // あとでresponse消す→}).done(function () {
        // 成功
        $('#custom-modal').hide();
        location.reload(); // ページをリロードして更新
      }).fail(function (xhr) {// response消す→　}).fail(function () {
        // エラー/response消す→ここからalertまで無くて良い
        let errorMessage = '失敗';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message; // サーバーからのエラーメッセージを取得
        }
        alert(errorMessage);
      });
    });

    /*$('#custom-modal').hide();
    });*/

    // モーダルを表示
    $('#custom-modal').show();
  });
});
