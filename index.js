/*so links in safari webapp do not open new tab */
var a = document.getElementsByTagName("a");
for(var i = 0; i < a.length; i++) {
  a[i].onclick=function() {
    window.location = this.getAttribute("href");
    return false
  }
}

var mainUser = document.getElementsByClassName("detailIconMain")[0];
mainUser.addEventListener("click", function() {
  window.location = "/project-blue/profileui.php?target=" + mainUser.id;
});
var chatting = document.getElementsByClassName("messagePage")[0];
chatting.addEventListener("click", function() {
  window.location = "/project-blue/msg/index.php";
});
var userL = document.getElementsByClassName("userlistPage")[0];
userL.addEventListener("click", function() {
  window.location = "/project-blue/userlist.php";
});

//variables for layout animation
var windowHeight = window.innerHeight,
list = document.getElementsByClassName("userOuter");

//layout animation
function change() {
   for(var i = 0; i < list.length; i++) {
    var rect = list[i].getBoundingClientRect(),
    y = rect.top,
    desiredHeight = windowHeight*.8,
    per = Math.abs(((y/desiredHeight)+.3));
      if (per > 1 ) {
        list[i].style.transform =`scale( ${1-(per-1)}, ${1-(per-1)} )`;	
      } else {
      	list[i].style.transform =`scale( ${per}, ${per} )`;
      }
   }
}

function inter() {
  var myVar = setInterval(change, 100);
}
window.addEventListener("DOMContentLoaded", function() {
  window.addEventListener("touchmove", change);
  window.addEventListener("scroll", change);
  inter();
})

window.addEventListener("load", change);
