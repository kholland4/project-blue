var messages;

var ALIGN_LEFT = " alignLeft";
var ALIGN_RIGHT = " alignRight";

function init() {
  loadf("backend.php?target=" + targetUserID + "&mode=full&r=" + Math.random(), function() {
    if(this.status == 200) {
      messages = JSON.parse(this.responseText);
      showMessages();
    }
  });
}

function showMessages() {
  var container = document.getElementById("messages");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  for(var i = 0; i < messages.length; i++) {
    var align = ALIGN_LEFT;
    if(messages[i].src == userID) { align = ALIGN_RIGHT; }
    showMessage(messages[i], container, align, true); //TODO only show user bubble when last message src != messages[i].src
  }
}
function showMessage(message, container, align, doUserBubble) {
  var wrapper = document.createElement("div");
  wrapper.className = "messageWrapper" + align;
  
  if(align == ALIGN_LEFT) {
    var userBubble = document.createElement("div");
    userBubble.className = "userBubble";
    if(doUserBubble) {
      var userIcon = document.createElement("img");
      userIcon.className = "userBubbleIcon";
      userIcon.src = "../avatar.php?userid=" + message.src;
      userBubble.appendChild(userIcon);
    }
    wrapper.appendChild(userBubble);
  }
  
  var bubble = document.createElement("div");
  bubble.className = "messageBubble" + align;
  bubble.innerText = message.content;
  wrapper.appendChild(bubble);
  
  container.appendChild(wrapper);
}

document.addEventListener("DOMContentLoaded", init);
