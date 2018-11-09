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

var mainUser = document.getElementById("mainUser");
mainUser.addEventListener("click", function() {
  window.location = "/project-blue/profileui.php";
});