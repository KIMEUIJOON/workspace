
var STORAGE_KEY = {CARRIER : "mc_carrier", BOARD_NAME : "mc_board_name", REVIEW_NAME : "mc_review_name", NEWS_NAME : "mc_news_name", NEWS_EMAIL : "mc_news_email"};

$(document).bind("mobileinit", function(){
  $.mobile.ajaxLinksEnabled = false;
  
  //$.mobile.page.prototype.options.addBackBtn = true;
  $.mobile.page.prototype.options.backBtnText = "戻る";
});
