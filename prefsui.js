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

function addPref(container, prefID, level = PREF_LEVEL_DEFAULT) {
  if(prefID != -1 && document.getElementById("prefRow" + prefID) == undefined) {
    var input = document.createElement("input");
    input.type = "hidden";
    input.name = prefID;
    input.value = Math.max(Math.min(level, PREF_LEVEL_MAX), PREF_LEVEL_MIN);
    container.appendChild(input);
  }
}

function initStage1() {
  //put IDs in the prefData array to correlate the sorted and unsorted versions
  for(var i = 0; i < prefData.length; i++) {
    prefData[i].id = i;
  }
  var prefDataSorted = Array.from(prefData);
  
  //category alphabetical sort
  //commented out b/c categories should be sorted as they appear in prefdata.json
  /*prefDataSorted.sort(function(a, b) {
    var s = a.category.localeCompare(b.category);
    if(s == 0) { return -1; }
    if(s == 1) { return 1; }
    return 0;
  });*/
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
    var id = prefDataSorted[i].id;
    
    var sel = false;
    for(var n = 0; n < prefsStage1.length; n++) {
      if(prefsStage1[n].id == id) {
        sel = true;
      }
    }
    
    var iconOuter = document.createElement("div");
    iconOuter.className = "stage1 prefIconOuter";
    
    iconOuter.dataset.id = id;
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
        //commented code handled by levelPopup code
        /*prefsStage1.push({
          id: id,
          level: PREF_LEVEL_DEFAULT
        });
        this.getElementsByClassName("stage1 prefIconOverlay")[0].style.display = "block";*/
        showLevelPopup(id);
        popupAnimation();
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
    overlay.id = "prefIconOverlay" + i; //set the id for external manipulation
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
}

function submit() {
  //---NOTICE--- this is somewhat temporary code that actually fills a HTML form with input elements to mimic the behavior of the original stage2 code; it will eventually be replaced with a proper system
  var container = document.createElement("form");
  container.style.display = "none";
  container.action = "prefsui.php";
  container.method = "POST";
  for(var i = 0; i < prefsStage1.length; i++) {
    addPref(container, prefsStage1[i].id, prefsStage1[i].level);
  }
  document.body.appendChild(container);
  container.submit();
}

function setStage1Level(prefID, level) {
  for(var i = 0; i < prefsStage1.length; i++) {
    if(prefsStage1[i].id == prefID) {
      prefsStage1[i].level = level;
      return;
    }
  }
  prefsStage1.push({id: prefID, level: level});
}
function showLevelPopup(prefID) {
  var mCont = document.getElementsByClassName("mainContainer")[0];
  var pageOverlay = document.getElementById("grayOver");
  mCont.classList.add("blurAndStyle");
  pageOverlay.style.display = "block";
  var level = PREF_LEVEL_DEFAULT;
  for(var i = 0; i < prefsStage1.length; i++) {
    if(prefsStage1[i].id == prefID) {
      level = prefsStage1[i].level;
    }
  }
  
  var container = document.getElementById("levelPopup"); //TODO: remove if variable isn't used
  document.getElementById("levelPopupName").innerText = prefData[prefID].name; //TODO: remove capitalization from name?

  //dynamically generate buttons
  var buttonContainer = document.getElementById("levelPopupButtons");
  while(buttonContainer.firstChild) { buttonContainer.removeChild(buttonContainer.firstChild); }
  for(var i = PREF_LEVEL_MIN; i <= PREF_LEVEL_MAX; i++) {
    var indivContainer = document.createElement("div");
    indivContainer.className = "indivContainer";
    var shadow = document.createElement("div");
    shadow.className = "shadow";
    shadow.id = `shadow${i}`;
    var button = document.createElement("p");
    button.className = "levelPopupButton"; 
    if (i==1) {
      var emoji = "\u{1F914}";
      button.innerHTML = emoji;      
    }else if (i==2) {
      var emoji = "\u{1F603}";
      button.innerHTML = emoji;
    }else{
      var emoji = "\u{1F60D}";
      button.innerHTML = emoji;       
    }
    if(i == level) { button.className = "levelPopupButton selected"; }
    button.dataset.prefID = prefID;
    button.dataset.level = i;
    
    button.onclick = function() {
      setTimeout(hideLevelPopup, 200);
      /*for(var n = 0; n <= (PREF_LEVEL_MAX - PREF_LEVEL_MIN); n++) {
        this.parentElement.children[n].className = "levelPopupButton";
      }
      this.parentElement.children[this.dataset.level - PREF_LEVEL_MIN].className = "levelPopupButton selected";*/
      setStage1Level(this.dataset.prefID, this.dataset.level);
      document.getElementById("prefIconOverlay" + prefID).style.display = "block"; //display check mark
      
    };
    indivContainer.appendChild(shadow);
    indivContainer.appendChild(button);
    buttonContainer.appendChild(indivContainer);
  }
  container.style.display = "block";
}
function hideLevelPopup() {
  var mCont = document.getElementsByClassName("mainContainer")[0];
  var pageOverlay = document.getElementById("grayOver");
  document.getElementById("levelPopup").style.display = "none";
  mCont.classList.remove("blurAndStyle");
  pageOverlay.style.display = "none";
}

document.addEventListener("DOMContentLoaded", init);

function popupAnimation() {
  var face = document.getElementsByClassName("levelPopupButton");
  var shadows = document.getElementsByClassName("shadow");
    face[0].addEventListener("click", function() {shadows[0].style.transform = "scale(2.5)"})
    face[0].addEventListener("touchstart", function() {setTimeout(function() {shadows[0].style.transform = "scale(2.5)"},400);});
    face[0].addEventListener("touchend", function() {shadows[0].style.transform = "scale(1)"});
    
    face[1].addEventListener("click", function() {shadows[1].style.transform = "scale(2.5)"})
    face[1].addEventListener("touchstart", function() {setTimeout(function() {shadows[1].style.transform = "scale(2.5)"},400);});
    face[1].addEventListener("touchend", function() {shadows[1].style.transform = "scale(1)"});
    
    face[2].addEventListener("click", function() {shadows[2].style.transform = "scale(2.5)"})
    face[2].addEventListener("touchstart", function() {setTimeout(function() {shadows[2].style.transform = "scale(2.5)"},400);});
    face[2].addEventListener("touchend", function() {shadows[2].style.transform = "scale(1)"});
}
