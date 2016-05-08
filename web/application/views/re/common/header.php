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

function checkbox_select(imgObj){
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
      $("#"+value).prop("checked", true);
      console.log("check : "+value);
    };
 function hiddenCheckbox_uncheck(value){
      $("#"+value).prop("checked", false);
      console.log("uncheck : "+value);
   };
 </script>
</head>
