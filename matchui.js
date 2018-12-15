var users;
var prefData;
var prefMeta;

function init() {
  loadf("getmatch.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText);
      sortUsers("score");
      displayUsers();
    }
  });
  loadf("prefdata.json", function() {
    if(this.status == 200) {
      var prefDataRaw = JSON.parse(this.responseText);
      prefData = prefDataRaw.data;
      prefMeta = prefDataRaw.meta;
      PREF_LEVEL_MIN = prefMeta.PREF_LEVEL_MIN;
      PREF_LEVEL_MAX = prefMeta.PREF_LEVEL_MAX;
      PREF_LEVEL_DEFAULT = prefMeta.PREF_LEVEL_DEFAULT;
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
      if(a.person.firstname != null && a.person.lastname != null) {
        s = getName(a.person).localeCompare(getName(b.person));
      } else {
        s = getName(a.person).localeCompare(getName(b.person));
      }
      if(s == 0) { return -1; }
      if(s == 1) { return 1; }
      return 0;
    });
  } else if(mode == "score") {
    users.sort(function(a, b) {
      if(a.score < b.score) { return -1; }
      if(a.score > b.score) { return 1; }
      return 0;
    });
  }
}

function displayUsers() {
  var container = document.getElementById("userList");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  //TODO: profile pics and DOB
  var matchCount = 0;
  for(var i = 0; i < users.length; i++) {
    //skip if <50% match
    /*if(users[i].score < 0.5) {
      continue;
    }*/
    
    var outer = document.createElement("div");
    outer.className = "userOuter";
    outer.dataset.data = JSON.stringify(users[i]);
    outer.onclick = function() { showDetail(JSON.parse(this.dataset.data)); }
    var userShrink = document.createElement("div");
    userShrink.className = "userShrink";
    var profilePic = document.createElement("img");
    profilePic.className = "userIcon";
    if(users[i].person.profile_photo != null) {
      profilePic.src = users[i].person.profile_photo; //FIXME
    } else {
      profilePic.src = "img/pp.png";
    }
    var targetColor = Math.floor(users[i].score.toPrecision(2) * 120);
    profilePic.style.border = "1.5px solid hsl(" + targetColor + ", 100%, 50%)";
    userShrink.appendChild(profilePic);
    var info = document.createElement("div");
    info.className = "userInfoOuter";
    
    var name = document.createElement("div");
    name.className = "userInfoName";
    name.innerText = getName(users[i].person);
    info.appendChild(name);
    
    userShrink.appendChild(info);
    outer.appendChild(userShrink);
    container.appendChild(outer);
    
    matchCount++;
  }
  
  if(matchCount == 0) {
    var outer = document.createElement("div");
    outer.className = "qqOuter";
    
    var img = document.createElement("img");
    img.className = "qqImg";
    img.src = "img/questions.png";
    outer.appendChild(img);
    var caption = document.createElement("span");
    caption.className = "qqCaption";
    caption.innerText = "You have unique interests in this area!";
    outer.appendChild(caption);
    
    container.appendChild(outer);
  } else {
    finalLayout();
  }
}

document.addEventListener("DOMContentLoaded", init);

function finalLayout () {
  var windowHeight = window.innerHeight,
  list = document.getElementsByClassName("userOuter");
  var lastUser = list[list.length - 1],
  lastUserBott = lastUser.getBoundingClientRect().bottom,
  firstUserTop = list[0].getBoundingClientRect().top,
  uLHeight = lastUserBott - firstUserTop,
  userList = document.getElementById("userList");
  userList.style.height = uLHeight + windowHeight + "px";
  list[list.length-1].scrollIntoView({ block: 'center',  behavior: 'smooth' });
}

