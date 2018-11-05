var messages;

function init() {
  loadf("backend.php?target=" + targetUserID + "&mode=full&r=" + Math.random(), function() {
    if(this.status == 200) {
      messages = JSON.parse(this.responseText);
      console.log(messages);
    }
  });
}

document.addEventListener("DOMContentLoaded", init);
