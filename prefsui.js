var PREF_LEVEL_MIN;
var PREF_LEVEL_MAX;
var PREF_LEVEL_DEFAULT;
var prefData;

function init() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4) {
      if(this.status == 200) {
        var prefDataRaw = JSON.parse(this.responseText);
        prefData = prefDataRaw.data;
        PREF_LEVEL_MIN = prefDataRaw.meta.PREF_LEVEL_MIN;
        PREF_LEVEL_MAX = prefDataRaw.meta.PREF_LEVEL_MAX;
        PREF_LEVEL_DEFAULT = prefDataRaw.meta.PREF_LEVEL_DEFAULT;
        initPrefs();
      } else {
        //TODO
      }
    }
  };
  xhttp.open("GET", "prefdata.json");
  xhttp.send();
}

function initPrefs() {
  var container = document.getElementById("addMenuSelect");
  for(var i = 0; i < prefData.length; i++) {
    var opt = document.createElement("option");
    opt.value = i;
    opt.innerText = prefData[i].name;
    container.appendChild(opt);
  }
}

function addPref() {
  var prefID = parseInt(document.getElementById("addMenuSelect").value);
  document.getElementById("addMenuSelect").value = "-1";
  
  if(prefID != -1 && document.getElementById("prefRow" + prefID) == undefined) {
    var tr = document.createElement("tr");
    tr.id = "prefRow" + prefID;
    
    //name
    var tdName = document.createElement("td");
    tdName.innerText = prefData[prefID].name;
    
    //slider
    var tdSlider = document.createElement("td");
    var slider = document.createElement("input");
    slider.type = "range";
    slider.name = prefID;
    slider.min = PREF_LEVEL_MIN;
    slider.max = PREF_LEVEL_MAX;
    slider.value = PREF_LEVEL_DEFAULT;
    tdSlider.appendChild(slider);
    
    //delete button
    var tdDel = document.createElement("td");
    var buttonDel = document.createElement("button");
    buttonDel.className = "delPref";
    buttonDel.type = "button";
    buttonDel.dataset.id = prefID;
    buttonDel.onclick = function() { delPref(this.dataset.id); }
    tdDel.appendChild(buttonDel);
    
    //add to list
    tr.appendChild(tdName);
    tr.appendChild(tdSlider);
    tr.appendChild(tdDel);
    document.getElementById("prefList").appendChild(tr);
  }
}

function delPref(prefID) {
  var tr = document.getElementById("prefRow" + prefID);
  if(tr != undefined) {
    tr.parentElement.removeChild(tr);
  }
}

document.addEventListener("DOMContentLoaded", init);
