$(document).foundation();

function showMessage(messageType, message) {
  var callout = document.getElementById(messageType + "-message");
  callout.innerHTML = "<p>" + message + "</p>" + callout.innerHTML;
  callout.style.display = ""; 
}

function makeNav(){
  $("#nav-placeholder").load("nav.php");
}

function makeCallouts() {
  $("#callouts-placeholder").load("callouts.html");
}