<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>main</title>
<link rel="stylesheet" href="http:\\localhost\src\re\css\keitai_navi2.css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){

});

function carrier_select(imgObj){
  var imgSrc = $(imgObj).attr("src");
  var value = $(imgObj).attr("value");
  result = $(imgObj).attr("check");
  if (result == "off") {
      imgSrc = imgSrc.replace('.png','_back.png');
      $(imgObj).attr("check","on");
      hiddenCheckbox_check(value);
  }else{
      imgSrc = imgSrc.replace('_back.png','.png');
      $(imgObj).attr("check","off");
      hiddenCheckbox_uncheck(value);
  }
  console.log(imgSrc);
  $(imgObj).attr("src",imgSrc);
};

 function hiddenCheckbox_check(value){
    switch (value) {
      case "docomo":
      $("#searchPanel_docomo").prop("checked", true);
        break;
      case "au":
      $("#searchPanel_au").prop("checked", true);
        break;
        case "softbank":
      $("#searchPanel_softbank").prop("checked", true);
        break;
      case "ymobile":
      $("#searchPanel_ymobile").prop("checked", true);
        break;
    }
};
 function hiddenCheckbox_uncheck(value){
   switch (value) {
     case "docomo":
     $("#searchPanel_docomo").prop("checked", false);
       break;
     case "au":
     $("#searchPanel_au").prop("checked", false);
       break;
       case "softbank":
     $("#searchPanel_softbank").prop("checked", false);
       break;
     case "ymobile":
     $("#searchPanel_ymobile").prop("checked", false);
       break;
   }
 }
 </script>
</head>
