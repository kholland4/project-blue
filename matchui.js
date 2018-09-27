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
  loadf("getmatch.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      //do something with this.responseText
      //NOTE: the JSON has a lot of unecessary data. don't feel the need to use all of it.
    }
  });
}

document.addEventListener("DOMContentLoaded", init);
