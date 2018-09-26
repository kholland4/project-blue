var PREF_LEVEL_MIN;
var PREF_LEVEL_MAX;
var PREF_LEVEL_DEFAULT;
var prefData;

var prefsStage1;

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
  loadf("prefdata.json", function() {
    if(this.status == 200) {
      var prefDataRaw = JSON.parse(this.responseText);
      prefData = prefDataRaw.data;
      PREF_LEVEL_MIN = prefDataRaw.meta.PREF_LEVEL_MIN;
      PREF_LEVEL_MAX = prefDataRaw.meta.PREF_LEVEL_MAX;
      PREF_LEVEL_DEFAULT = prefDataRaw.meta.PREF_LEVEL_DEFAULT;
      initPrefs();
      
      loadf("getprefs.php?r=" + Math.random(), function() {
        if(this.status == 200) {
          prefsStage1 = JSON.parse(this.responseText);
          initStage1();
        } else {
          //TODO
        }
      });
    } else {
      //TODO
    }
  });
}

function initPrefs() {
  var container = document.getElementById("addMenu");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  for(var i = 0; i < prefData.length; i++) {
    //var opt = document.createElement("option");
    //opt.value = i;
    //opt.innerText = prefData[i].name;
    //container.appendChild(opt);
    if(document.getElementById("prefRow" + i) == undefined) {
      var opt = document.createElement("div");
      opt.className = "prefAddOuter";
      opt.dataset.id = i;
      opt.onclick = function() { addPref(this.dataset.id); this.parentElement.removeChild(this); };
      var icon = document.createElement("img");
      icon.className = "prefAddIcon";
      icon.src = prefData[i].image;
      var caption = document.createElement("span");
      caption.className = "prefAddCaption";
      caption.innerText = prefData[i].name;
      opt.appendChild(icon);
      opt.appendChild(caption);
      container.appendChild(opt);
    }
  }
}

var addMenuState = false;
function toggleAddMenu() {
  if(addMenuState) {
    document.getElementById("addMenu").style.display = "none";
  } else {
    document.getElementById("addMenu").style.display = "block";
  }
  addMenuState = !addMenuState;
}

function addPref(prefID, level = PREF_LEVEL_DEFAULT) {
  if(prefID != -1 && document.getElementById("prefRow" + prefID) == undefined) {
    var tr = document.createElement("tr");
    tr.id = "prefRow" + prefID;
    tr.className = "prefs";
    
    //name and icon
    var tdName = document.createElement("td");
    tdName.className = "prefs";
    var iconOuter = document.createElement("div");
    iconOuter.className = "prefIconOuter";
    var icon = document.createElement("img");
    icon.className = "prefIcon";
    icon.src = prefData[prefID].image;
    var caption = document.createElement("span");
    caption.className = "prefIconCaption";
    caption.innerText = prefData[prefID].name;
    iconOuter.appendChild(icon);
    iconOuter.appendChild(caption);
    tdName.appendChild(iconOuter);
    
    //slider
    var tdSlider = document.createElement("td");
    tdSlider.className = "prefs";
    var slider = document.createElement("input");
    slider.type = "range";
    slider.name = prefID;
    slider.min = PREF_LEVEL_MIN;
    slider.max = PREF_LEVEL_MAX;
    slider.value = level;
    tdSlider.appendChild(slider);
    
    //delete button
    var tdDel = document.createElement("td");
    tdDel.className = "prefs";
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
  //this.parentElement.removeChild(this);
}

function delPref(prefID) {
  var tr = document.getElementById("prefRow" + prefID);
  if(tr != undefined) {
    tr.parentElement.removeChild(tr);
    initPrefs();
  }
}

function initStage1() {
  document.getElementById("stage1").style.display = "block";
  document.getElementById("stage2").style.display = "none";
  initStage2();
}

function initStage2() {
  document.getElementById("stage1").style.display = "none";
  document.getElementById("stage2").style.display = "block";
  for(var i = 0; i < prefsStage1.length; i++) {
    addPref(prefsStage1[i].id, prefsStage1[i].level);
  }
}

document.addEventListener("DOMContentLoaded", init);
