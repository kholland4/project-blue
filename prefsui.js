var PREF_LEVEL_MIN;
var PREF_LEVEL_MAX;
var PREF_LEVEL_DEFAULT;
var prefData;
var prefMeta;

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
      prefMeta = prefDataRaw.meta;
      PREF_LEVEL_MIN = prefMeta.PREF_LEVEL_MIN;
      PREF_LEVEL_MAX = prefMeta.PREF_LEVEL_MAX;
      PREF_LEVEL_DEFAULT = prefMeta.PREF_LEVEL_DEFAULT;
      
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
    tdName.className = "stage2 prefs prefsListIcon";
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
    /*var slider = document.createElement("input");
    slider.type = "range";
    slider.name = prefID;
    slider.min = PREF_LEVEL_MIN;
    slider.max = PREF_LEVEL_MAX;
    slider.value = level;
    tdSlider.appendChild(slider);*/
    var slider = document.createElement("input");
    slider.type = "hidden";
    slider.name = prefID;
    slider.value = Math.max(Math.min(level, PREF_LEVEL_MAX), PREF_LEVEL_MIN);
    tdSlider.appendChild(slider);
    
    var buttonOuter = document.createElement("div");
    buttonOuter.className = "stage2 levelOuter";
    for(var i = PREF_LEVEL_MIN; i <= PREF_LEVEL_MAX; i++) {
      var button = document.createElement("img");
      button.src = "img/level" + i + ".png";
      button.className = "stage2 levelButton";
      if(i == slider.value) { button.className = "stage2 levelButton selected"; }
      button.dataset.level = i;
      button.onclick = function() {
        this.parentElement.firstChild.value = this.dataset.level;
        for(var n = 0; n <= (PREF_LEVEL_MAX - PREF_LEVEL_MIN); n++) {
          this.parentElement.children[n].className = "stage2 levelButton";
        }
        this.parentElement.children[this.dataset.level - PREF_LEVEL_MIN].className = "stage2 levelButton selected";
      };
      buttonOuter.appendChild(button);
    }
    tdSlider.appendChild(buttonOuter);
    
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
  //---header
  var backButton = document.getElementById("backButton");
  backButton.href = "javascript:history.go(-1);";
  
  var headerText = document.getElementById("headerText");
  headerText.innerText = "[placeholder text]";
  
  //---preference options
  var prefDataSorted = prefData;
  
  //category alphabetical sort
  prefDataSorted.sort(function(a, b) {
    var s = a.category.localeCompare(b.category);
    if(s == 0) { return -1; }
    if(s == 1) { return 1; }
    return 0;
  });
  //create a list of populated categories
  var categories = [];
  for(var i = 0; i < prefDataSorted.length; i++) {
    var found = false;
    for(var n = 0; n < categories.length; n++) {
      if(categories[n] == prefDataSorted[i].category) {
        found = true;
      }
    }
    if(!found) {
      categories.push(prefDataSorted[i].category);
    }
  }
  
  //name alphabetical sort
  prefDataSorted.sort(function(a, b) {
    var s = a.name.localeCompare(b.name);
    if(s == 0) { return -1; }
    if(s == 1) { return 1; }
    return 0;
  });
  
  var containerMain = document.getElementById("stage1List");
  while(containerMain.firstChild) { containerMain.removeChild(containerMain.firstChild); }
  
  //create a container for each category
  var containers = [];
  for(var i = 0; i < categories.length; i++) {
    var cO = document.createElement("div");
    cO.className = "stage1 categoryOuter";
    var cHeader = document.createElement("div");
    cHeader.className = "stage1 categoryHeader";
    cHeader.innerText = prefMeta.categories[categories[i]];
    cO.appendChild(cHeader);
    var c = document.createElement("div");
    c.className = "stage1 category";
    containers[categories[i]] = c;
    cO.appendChild(c);
    containerMain.appendChild(cO);
  }
  
  for(var i = 0; i < prefDataSorted.length; i++) {
    var container = containers[prefDataSorted[i].category];
    
    var sel = false;
    for(var n = 0; n < prefsStage1.length; n++) {
      if(prefsStage1[n].id == i) {
        sel = true;
      }
    }
    
    var iconOuter = document.createElement("div");
    iconOuter.className = "stage1 prefIconOuter";
    
    iconOuter.dataset.id = i;
    iconOuter.onclick = function() {
      var id = this.dataset.id;
      var sel = false;
      for(var n = 0; n < prefsStage1.length; n++) {
        if(prefsStage1[n].id == id) {
          sel = true;
        }
      }
      
      if(sel) {
        for(var n = 0; n < prefsStage1.length; n++) {
          if(prefsStage1[n].id == id) {
            prefsStage1.splice(n, 1);
            break;
          }
        }
        this.getElementsByClassName("stage1 prefIconOverlay")[0].style.display = "none";
      } else {
        prefsStage1.push({
          id: id,
          level: PREF_LEVEL_DEFAULT
        });
        this.getElementsByClassName("stage1 prefIconOverlay")[0].style.display = "block";
      }
    };
    
    var icon = document.createElement("img");
    icon.className = "stage1 prefIcon";
    icon.src = prefDataSorted[i].image;
    iconOuter.appendChild(icon);
    var overlay = document.createElement("img");
    overlay.className = "stage1 prefIconOverlay";
    overlay.src = "img/check.png";
    if(!sel) {
      overlay.style.display = "none";
    }
    iconOuter.appendChild(overlay);
    var caption = document.createElement("span");
    caption.className = "stage1 prefIconCaption";
    caption.innerText = prefDataSorted[i].name;
    iconOuter.appendChild(caption);
    container.appendChild(iconOuter);
  }
  for(var i = 0; i < categories.length; i++) {
    for(var n = 0; n < Math.ceil(window.innerWidth / 80); n++) { /* FIXME */
      var container = containers[categories[i]];
      
      var iconOuter = document.createElement("div");
      iconOuter.className = "stage1 prefIconPlaceholder";
      container.appendChild(iconOuter);
    }
  }
  document.getElementById("stage1").style.display = "block";
  document.getElementById("stage2").style.display = "none";
}

function initStage2() {
  //---header
  var backButton = document.getElementById("backButton");
  backButton.href = "javascript:initStage1();";
  
  var headerText = document.getElementById("headerText");
  headerText.innerText = "Levels";
  
  //---prefs list
  for(var i = 0; i < prefsStage1.length; i++) {
    addPref(prefsStage1[i].id, prefsStage1[i].level);
  }
  initPrefs();
  document.getElementById("stage1").style.display = "none";
  document.getElementById("stage2").style.display = "block";
}

document.addEventListener("DOMContentLoaded", init);
