var users;
var prefData;
var prefMeta;

function init() {
  loadf("getmatch.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText);
      sortUsers("firstlast");
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

//TODO [Kyle]: Add a dropdown to change the list sorting
//TODO [Kyle]: Add a search system
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
  for(var i = 0; i < users.length; i++) {
    var outer = document.createElement("div");
    outer.className = "userOuter";  
    var userInfo = document.createElement("div"); //a div of only the info not including the buttons
    userInfo.dataset.data = JSON.stringify(users[i]); //you now click in that area instead of the whole div
    userInfo.onclick = function() { showDetail(JSON.parse(this.dataset.data)); }
    userInfo.className = "userInfo";
    var profilePic = document.createElement("img");
    profilePic.className = "userIcon";
    if(users[i].person.profile_photo != null) {
      profilePic.src = users[i].person.profile_photo; //FIXME
    } else {
      profilePic.src = "img/pp.png";
    }
    userInfo.appendChild(profilePic);
    var info = document.createElement("div");
    info.className = "userInfoOuter";
    
    var name = document.createElement("div");
    name.className = "userInfoName";
    name.innerText = getName(users[i].person);
    info.appendChild(name);
    var extra = document.createElement("div");
    extra.className = "userInfoExtra";
    extra.innerText = (users[i].score.toPrecision(2)*100) + "% match";
    info.appendChild(extra);
    userInfo.appendChild(info);

    var bOuter = document.createElement("div");
    bOuter.className = "buttonContOuter";
    
    var buttonOne = document.createElement("div");
    buttonOne.className = "buttonCont";
    var friend = document.createElement("button");
    friend.className = "actionButton";
    var friendIcon = document.createElement("i");
    friendIcon.className ="fas fa-user-plus";
    friend.appendChild(friendIcon);
    buttonOne.appendChild(friend);
    bOuter.appendChild(buttonOne);
    
    var buttonTwo = document.createElement("div");
    buttonTwo.className ="buttonCont";
    var message = document.createElement("button");
    message.className="actionButton";
    var textIcon = document.createElement("i");
    textIcon.className ="fas fa-comments";
    message.appendChild(textIcon);
    buttonTwo.appendChild(message);
    bOuter.appendChild(buttonTwo);
    
    outer.appendChild(userInfo);
    outer.appendChild(bOuter);
    container.appendChild(outer);
  }
}

document.addEventListener("DOMContentLoaded", init);
