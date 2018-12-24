var messages;
var lastMessageTime = 0;
var isLoading = 0;

var ALIGN_LEFT = " alignLeft";
var ALIGN_RIGHT = " alignRight";

function init() {
  isLoading++;
  loadf("backend.php?target=" + targetUserID + "&mode=full&r=" + Math.random(), function() {
    if(this.status == 200) {
      messages = JSON.parse(this.responseText);
      if(messages.length > 0) {
        lastMessageTime = messages[messages.length - 1].time;
      }
      showMessages(messages);
      showLastMessage();
    }
    isLoading--;
  }, 4000, function() { isLoading--; /* TODO: show error */});
  
  //periodically check for new messages
  setInterval(function() {
    if(isLoading <= 0) {
      getPartial();
    }
  }, 500);
}

function getPartial() {
  isLoading++;
  loadf("backend.php?target=" + targetUserID + "&mode=since&since=" + lastMessageTime + "&r=" + Math.random(), function() {
    if(this.status == 200) {
      newMessages = JSON.parse(this.responseText);
      if(newMessages.length > 0) {
        lastMessageTime = newMessages[newMessages.length - 1].time;
      }
      messages.push.apply(messages, newMessages);
      showMessages(newMessages, false);
    }
    isLoading--;
  }, 4000, function() { isLoading--; /* TODO: show error */});
}

function showMessages(messages, clear = true) {
  var container = document.getElementById("messages");
  if(clear) {
    while(container.firstChild) { container.removeChild(container.firstChild); }
  }
  for(var i = 0; i < messages.length; i++) {
    var align = ALIGN_LEFT;
    if(messages[i].src == userID) { align = ALIGN_RIGHT; }
    showMessage(messages[i], container, align, true); //TODO only show user bubble when last message src != messages[i].src
  }
}

function showMessage(message, container, align, doUserBubble) {
  var wrapper = document.createElement("div");
  wrapper.className = "messageWrapper" + align;

  var userIcon = document.getElementsByClassName("userBubbleIcon")[0];
  userIcon.src = "../avatar.php?userid=" + message.src;

  var bubble = document.createElement("div");
  bubble.className = "messageBubble" + align;
  bubble.innerText = message.content;
  wrapper.appendChild(bubble);
  
  container.appendChild(wrapper);
}


function sendMessage() {
  var message = document.getElementById("sendMessageText").value;
  document.getElementById("sendMessageForm").reset();
  //TODO: check length
  httpPost("backend.php?target=" + targetUserID + "&mode=send", "message=" + encodeURIComponent(message), function() { getPartial(); });
  //TODO: display bubble with "sending..."?
  var textArea = document.getElementById("sendMessageText");
  textArea.style.height = "32px";
  showLastMessage();
}

function textAreaAdjust() {
  var tx = document.getElementsByTagName('textarea');
  for (var i = 0; i < tx.length; i++) {
  tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
  tx[i].addEventListener("input", OnInput, false);
  }
}
  
function OnInput() {
  this.style.height = 33 + 'px';
  this.style.height = (this.scrollHeight) + 'px';
}

document.addEventListener("DOMContentLoaded", init);


function showLastMessage() {
  var listOfMessages = document.getElementsByClassName("messageWrapper");
  listOfMessages[listOfMessages.length-1].scrollIntoView();
}
