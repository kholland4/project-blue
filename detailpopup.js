function showDetail(data) {
  var container = document.getElementById("detailPopupInner");
  while(container.firstChild) { container.removeChild(container.firstChild); }
  
  document.getElementById("detailPopupHeaderText").innerText = getName(data.person);
  
  //Large profile picture
  var profilePic = document.createElement("img");
  profilePic.className = "detailIcon";
  if(data.person.profile_photo != null) {
    profilePic.src = data.person.profile_photo; //FIXME
  } else {
    profilePic.src = "img/pp.png";
  }
  container.appendChild(profilePic);
  
  //Header with name and % match
  var info = document.createElement("div");
  info.className = "detailInfoOuter";
  
  var name = document.createElement("div");
  name.className = "detailInfoName";
  name.innerText = getName(data.person);
  info.appendChild(name);
  
  var extra = document.createElement("div");
  extra.className = "detailInfoExtra";
  extra.innerText = (data.score.toPrecision(2) * 100) + "% match";
  info.appendChild(extra);

  var buttonOne = document.createElement("button");
  buttonOne.className = "detailButton";
  buttonOne.innerText = "Text";
  info.appendChild(buttonOne);

  var buttonTwo = document.createElement("button");
  buttonTwo.className = "detailButton";
  buttonTwo.innerText = "Add as Friend";
  info.appendChild(buttonTwo);
  
  container.appendChild(info);
  
  //List of connections
  var outer = document.createElement("table");
  outer.className = "detailListOuter";
  //Sort by importance (FIXME: should this be sorted by weighted strength? it should. TODO [Kyle]: sort by composite level)
  //TODO: copy then sort
  data.detail.sort(function(a, b) {
    var s = b.importance - a.importance;
    if(s < 0) { return -1; }
    if(s > 0) { return 1; }
    return 0;
  });
  for(var i = 0; i < data.detail.length; i++) {
    //skip if <25% match
    if(data.detail[i].matchLevel < 0.25) {
      continue;
    }
    
    var tr = document.createElement("tr");
    tr.className = "detailListRow";
    //icon
    var tdIcon = document.createElement("td");
    tdIcon.className = "detailListIconCell";
    var icon = document.createElement("img");
    icon.className = "detailListIcon";
    icon.src = prefData[data.detail[i].pref].image;
    tdIcon.appendChild(icon);
    tr.appendChild(tdIcon);
    //name
    var tdName = document.createElement("td");
    tdName.className = "detailListNameCell";
    var name = document.createElement("div");
    name.className = "detailListName";
    name.innerText = prefData[data.detail[i].pref].name;
    tdName.appendChild(name);
    tr.appendChild(tdName);
    outer.appendChild(tr);
    //bar at bottom - I would use ::after but there's no proper way to modify that in JS
    var bar = document.createElement("tr");
    bar.className = "detailListBar";
    var targetColor = Math.floor(data.detail[i].matchLevel.toPrecision(2) * 120);
    bar.style.borderBottom = "5px solid hsl(" + targetColor + ", 100%, 50%)";
    bar.style.width = (data.detail[i].matchLevel.toPrecision(2) * 100) + "%";
    outer.appendChild(bar);
  }
  container.appendChild(outer);
  
  document.getElementById("detailPopup").style.display = "block";
}

function hideDetail() {
  document.getElementById("detailPopup").style.display = "none";
}
