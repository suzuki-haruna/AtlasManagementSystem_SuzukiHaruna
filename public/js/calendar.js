$(function () {

});

$(document).ready(function () { // $(ドキュメント全体の準備が整った時点で実行)イベントハンドラー
  // モーダルを開く
  $('.open-modal').on('click', function (e) {
    e.preventDefault();

    // データを取得
    const reserveId = $(this).data('id');
    const reserveDate = $(this).data('reserve');
    const reservePart = $(this).data('part');

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
        _token: "<?= csrf_token() ?>"
      });

      $('#custom-modal').hide();
    });

    // モーダルを表示
    $('#custom-modal').show();
  });
});
//part: reservePart,

/*function setCancelFormAction(actionUrl) {
  document.getElementById('cancelForm').action = actionUrl;
}*/
