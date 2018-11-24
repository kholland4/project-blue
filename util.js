function loadf(url, callback, timeout = null, timeoutCallback = null) {
  var xhttp = new XMLHttpRequest();
  xhttp._callback = callback;
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      this._callback();
    }
  };
  if(timeout != null) {
    xhttp.timeout = timeout;
  }
  if(timeoutCallback != null) {
    xhttp.ontimeout = timeoutCallback;
  }
  xhttp.open("GET", url);
  xhttp.send();
}

function httpPost(url, postData, callback, urlEncoded = true) {
  var xhttp = new XMLHttpRequest();
  xhttp._callback = callback;
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      this._callback();
    }
  };
  xhttp.open("POST", url);
  if(urlEncoded) {
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  }
  xhttp.send(postData);
}

//use this wrapper function in case we want to do anything fancy with navigation
function goToLink(link) {
  location.href = link;
}
