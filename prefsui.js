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
      opt.className = "stage2 prefAddOuter";
      opt.dataset.id = i;
      opt.onclick = function() { addPref(this.dataset.id); this.parentElement.removeChild(this); };
      var icon = document.createElement("img");
      icon.className = "stage2 prefAddIcon";
      icon.src = prefData[i].image;
      var caption = document.createElement("span");
      caption.className = "stage2 prefAddCaption";
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
    tr.className = "stage2 prefs";
    
    //name and icon
    var tdName = document.createElement("td");
    tdName.className = "stage2 prefs";
    var iconOuter = document.createElement("div");
    iconOuter.className = "stage2 prefIconOuter";
    var icon = document.createElement("img");
    icon.className = "stage2 prefIcon";
    icon.src = prefData[prefID].image;
    var caption = document.createElement("span");
    caption.className = "stage2 prefIconCaption";
    caption.innerText = prefData[prefID].name;
    iconOuter.appendChild(icon);
    iconOuter.appendChild(caption);
    tdName.appendChild(iconOuter);
    
    //slider
    var tdSlider = document.createElement("td");
    tdSlider.className = "stage2 prefs";
    var slider = document.createElement("input");
    slider.type = "range";
    slider.name = prefID;
    slider.min = PREF_LEVEL_MIN;
    slider.max = PREF_LEVEL_MAX;
    slider.value = level;
    tdSlider.appendChild(slider);
    
    //delete button
    var tdDel = document.createElement("td");
    tdDel.className = "stage2 prefs";
    var buttonDel = document.createElement("button");
    buttonDel.className = "stage2 delPref";
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
  var prefDataSorted = prefData;
  var container = document.getElementById("stage1List");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  for(var i = 0; i < prefDataSorted.length; i++) {
    var sel = false;
    for(var n = 0; n < prefsStage1.length; n++) {
      if(prefsStage1[n].id == i) {
        sel = true;
      }
    }
    
    var iconOuter = document.createElement("div");
    iconOuter.className = "stage1 prefIconOuter";
    var icon = document.createElement("img");
    icon.className = "stage1 prefIcon";
    icon.src = prefDataSorted[i].image;
    iconOuter.appendChild(icon);
    if(sel) {
      var overlay = document.createElement("img");
      overlay.className = "stage1 prefIconOverlay";
      overlay.src = "img/select.png";
      iconOuter.appendChild(overlay);
    }
    var caption = document.createElement("span");
    caption.className = "stage1 prefIconCaption";
    caption.innerText = prefDataSorted[i].name;
    iconOuter.appendChild(caption);
    container.appendChild(iconOuter);
  }
  document.getElementById("stage1").style.display = "block";
  document.getElementById("stage2").style.display = "none";
}

function initStage2() {
  for(var i = 0; i < prefsStage1.length; i++) {
    addPref(prefsStage1[i].id, prefsStage1[i].level);
  }
  initPrefs();
  document.getElementById("stage1").style.display = "none";
  document.getElementById("stage2").style.display = "block";
}

document.addEventListener("DOMContentLoaded", init);
