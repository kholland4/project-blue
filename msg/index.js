var conversations;
var isLoading = 0;

var ALIGN_LEFT = " alignLeft";
var ALIGN_RIGHT = " alignRight";

function init() {
  update();
  
  //periodically check for new messages
  setInterval(function() {
    if(isLoading <= 0) {
      update();
    }
  }, 500);
}

function update() {
  isLoading++;
  loadf("backend.php?mode=convlist&target=-1&r=" + Math.random(), function() {
    if(this.status == 200) {
      conversations = JSON.parse(this.responseText);
      updateUI();
    }
    isLoading--;
  }, 4000, function() { isLoading--; /* TODO: show error */});
}

function updateUI() {
  var container = document.getElementById("messages");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  
  for(var i = 0; i < conversations.length; i++) {
    showMessage(conversations[i].target, conversations[i].lastMessage, container);
  }
}

function showMessage(target, message, container) {
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
  name.innerText = "UID " + target; //FIXME [Kyle]: Show real name!!!
  infobox.appendChild(name);
  
  var messageText = document.createElement("div");
  messageText.className = "messageInfoboxContent";
  messageText.innerText = message.content;
  infobox.appendChild(messageText);
  
  wrapper.appendChild(infobox);
  
  container.appendChild(wrapper);
}

document.addEventListener("DOMContentLoaded", init);
