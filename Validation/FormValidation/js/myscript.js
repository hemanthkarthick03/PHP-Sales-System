$(document).ready(function(){
  $("p").click(function(){
	  console.log("working")
    $(this).hide();
  });
$("button").click(function(){
console.log("loading deomotxt")
  $("#div1").load("./demo_test.txt", function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success")
      alert("External content loaded successfully!");
    if(statusTxt == "error")
      alert("Error: " + xhr.status + ": " + xhr.statusText);
  });
});
});
