$(function () {//jQuery基本セット//☆
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    $(this).toggleClass('open'); // クラスを切り替えて矢印を制御
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
    $(this).toggleClass('open'); // クラスを切り替えて矢印を制御
  });
});//jQuery基本セット
