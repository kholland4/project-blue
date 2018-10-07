var users;

function loadf(url, callback) {
  var xhttp = new XMLHttpRequest();
  xhttp._callback = callback;
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      this._callback();
    }
  };
  xhttp.open("GET", url);
  xhttp.send();
}

function init() {
  loadf("getusers.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText);
      sortUsers("firstlast");
      displayUsers();
    }
  });
}

function getName(user) {
  if(user.firstname != null && user.lastname != null) {
    return user.firstname + " " + user.lastname;
  } else {
    return user.username;
  }
}

function sortUsers(mode) {
  if(mode == "firstlast") {
    users.sort(function(a, b) {
      var s;
      if(a.firstname != null && a.lastname != null) {
        s = getName(a).localeCompare(getName(b));
      } else {
        s = getName(a).localeCompare(getName(b));
      }
      if(s == 0) { return -1; }
      if(s == 1) { return 1; }
      return 0;
    });
  }
}

function displayUsers() {
  var container = document.getElementById("userList");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  //TODO: profile pics and DOB
  for(var i = 0; i < users.length; i++) {
    var outer = document.createElement("div");
    outer.className = "userOuter";
    outer.dataset.data = JSON.stringify(users[i]);
    outer.onClick = function() { showDetail(JSON.parse(this.dataset.data)); }
    var profilePic = document.createElement("img");
    profilePic.className = "userIcon";
    if(users[i].profile_photo != null) {
      profilePic.src = users[i].profile_photo; //FIXME
    } else {
      profilePic.src = "img/pp.png";
    }
    outer.appendChild(profilePic);
    var info = document.createElement("div");
    info.className = "userInfoOuter";
    
    var name = document.createElement("div");
    name.className = "userInfoName";
    name.innerText = getName(users[i]);
    info.appendChild(name);
    
    outer.appendChild(info);
    container.appendChild(outer);
  }
}

function showDetail(userData) {
  //TODO
}

document.addEventListener("DOMContentLoaded", init);
