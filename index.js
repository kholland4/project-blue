/*link menu*/
function toggleMenu(x){x.classList.toggle("change"); myFunction()}

  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// do we want to allow users to quit the manu by clicking anywhere? 
/*  window.onclick = function(e) {
    if (!e.target.matches('.menuContainer')) {
      var myDropdown = document.getElementById("myDropdown");
        if (myDropdown.classList.contains('show')) {
          myDropdown.classList.remove('show');
        }
    }
  }*/

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
  window.location = "/project-blue/profileui.php";
});

//layout animation
function change() {

  var windowHeight = window.innerHeight,
  gridTop1 = Number((windowHeight * .5).toFixed(0)),
  gridBottom1 = Number((windowHeight * .625).toFixed(0)),
  gridTop2 = Number((windowHeight * .375).toFixed(0)),
  gridBottom2 = Number((windowHeight * .75).toFixed(0)),
  gridTop3 = Number((windowHeight * .189).toFixed(0)),
  gridBottom3 = Number((windowHeight * .875).toFixed(0)),
  gridTop4 = Number((windowHeight * .015).toFixed(0)),
  gridBottom4 = windowHeight,
  list = document.getElementsByClassName("userOuter"),
  userShrink = document.getElementsByClassName("userShrink")

    for(var i = 0; i < list.length; i++) {
      var rect = list[i].getBoundingClientRect(),
      y = Number(rect.top.toFixed(0));
      if (y > gridTop1 && y < gridBottom1) {
        userShrink[i].classList.remove("userOuter1");
        userShrink[i].classList.remove("userOuter2");
        userShrink[i].classList.remove("userOuter3");
        userShrink[i].classList.remove("userOuter4");
        userShrink[i].classList.add("userOuter0");
      }
      if (y < gridTop1 && y > gridTop2 || y > gridBottom1 && y < gridBottom2) {
        userShrink[i].classList.remove("userOuter0");
        userShrink[i].classList.remove("userOuter2");
        userShrink[i].classList.remove("userOuter3");
        userShrink[i].classList.remove("userOuter4");
        userShrink[i].classList.add("userOuter1");
      }
      if (y < gridTop2 && y > gridTop3 || y > gridBottom2 && y < gridBottom3) {
        userShrink[i].classList.remove("userOuter0");
        userShrink[i].classList.remove("userOuter1");
        userShrink[i].classList.remove("userOuter3");
        userShrink[i].classList.remove("userOuter4");
        userShrink[i].classList.add("userOuter2")
      }
      if (y < gridTop3 && y > gridTop4 || y > gridBottom3 && y < gridBottom4) {
        userShrink[i].classList.remove("userOuter0");
        userShrink[i].classList.remove("userOuter1");
        userShrink[i].classList.remove("userOuter2");
        userShrink[i].classList.remove("userOuter4");
        userShrink[i].classList.add("userOuter3");
      }
      if (y < gridTop4 || y > gridBottom4) {
        userShrink[i].classList.remove("userOuter0");
        userShrink[i].classList.remove("userOuter1");
        userShrink[i].classList.remove("userOuter2");
        userShrink[i].classList.remove("userOuter3");
        userShrink[i].classList.add("userOuter4");
      }

    }
  }

function inter() {
  var myVar = setInterval(change, 100);
}
window.addEventListener("DOMContentLoaded", function() {
  window.addEventListener("touchmove", change);
  inter();
})

window.addEventListener("load", change)
window.addEventListener("load", function() {
var userList = document.getElementById("userList");
userList.style.height = userList.scrollHeight + 500 + "px" ;
})

