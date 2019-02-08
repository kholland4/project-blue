var conversations;
var pastConversations;
var users;
var isLoading = 0;

var ALIGN_LEFT = " alignLeft";
var ALIGN_RIGHT = " alignRight";

function init() {
  loadf("../getusers.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText); 
      initMessages();
    }
  });  
  //periodically check for new messages
  setInterval(function() {
    if(isLoading <= 0) {
      updateMsgs();
    }
  }, 500);
}

const initMessages =()=> {
  isLoading++;
  loadf("backend.php?mode=convlist&target=-1&r=" + Math.random(), function() {
    if(this.status == 200) {
      pastConversations = JSON.parse(this.responseText);
      updateUI(pastConversations);
    }
    isLoading--;
  }, 4000, function() { isLoading--; /* TODO: show error */});
}

function updateMsgs() {
  isLoading++;
  loadf("backend.php?mode=convlist&target=-1&r=" + Math.random(), function() {
    if(this.status == 200) {      
      conversations = JSON.parse(this.responseText);
      if(JSON.stringify(pastConversations) != JSON.stringify(conversations)) {
        pastConversations = conversations;
        updateUI(conversations);
      }
    }
    isLoading--;
  }, 4000, function() { isLoading--; /* TODO: show error */});
}


function updateUI(conversations) {
  var container = document.getElementById("messages");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  
  console.log("im beign updated0");
  for(var i = 0; i < conversations.length; i++) {
    targetName = findName(conversations[i].target);
    console.log(name);
    showMessage(targetName, conversations[i].target, conversations[i].lastMessage, container);
  }
}

function findName(target) {
  let user = users.find(item => item.id === target );
  name =  `${user.firstname} ${user.lastname}`
  if (user.firstname) {
    return name;
  } else {
    return user.username;
  }
}

function showMessage(targetName, target, message, container) {
  var wrapper = document.createElement("div");
  wrapper.className = "messageWrapper";
  wrapper.dataset.target = target;
  wrapper.onclick = function() { location.href = "msg.php?target=" + this.dataset.target; };

  var userIcon = document.createElement("img");
  userIcon.className = "messageUserIcon";
  userIcon.src = "../avatar.php?userid=" + target;
  wrapper.appendChild(userIcon);

  var infobox = document.createElement("div");
  infobox.className = "messageInfobox";
  
  var name = document.createElement("div");
  name.className = "messageInfoboxName";
  name.innerText = targetName; //FIXME [Kyle]: Show real name!!!
  infobox.appendChild(name);
  
  var messageText = document.createElement("div");
  messageText.className = "messageInfoboxContent";
  messageText.innerText = message.content;
  infobox.appendChild(messageText);
  
  wrapper.appendChild(infobox);
  
  container.appendChild(wrapper);
}

document.addEventListener("DOMContentLoaded", init);
