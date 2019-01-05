function init() {
  loadf("getusers.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText);
      findCurrentUser(users);
      ShowEditOptions();
    }
  });
  loadf("prefdata.json", function() {
    if(this.status == 200) {
      var prefDataRaw = JSON.parse(this.responseText);
      prefData = prefDataRaw.data;
    }
  });  
}

const webAppMode =()=> {
	var a = document.getElementsByTagName("a");
	for(var i = 0; i <= a.length; i++) {     
	  a[i].onclick=function() {
	    window.location = this.getAttribute("href");
	    return false
	  }
	}
}

const findCurrentUser =(users)=> {
	const filteredUser = users.find(user => user.id === fromLinkId),
  prefsArray = Object.keys(filteredUser.prefs),
	arrayLength = (prefsArray.length < 8) ? prefsArray.length : 8;
	loopIntersts(arrayLength, prefsArray);
}

const loopIntersts =(arrayLength, prefsArray)=> {
	forRenderImages = [];
	let choose = randomNoRepeat(prefsArray);
	for( let i = 0; i < arrayLength; i++) {
			forRenderImages[i] = choose();
			displayInterests(prefData[forRenderImages[i]].image);
	}
}

const randomNoRepeat =(array)=> {
	let copy = array.slice(0);
  return function() {
    if (copy.length < 1) { copy = array.slice(0); }
    let index = Math.floor(Math.random() * copy.length);
    let item = copy[index];
    copy.splice(index, 1);
    return item;
  };
}

const displayInterests =(interestSRC)=> {
	let interestList = document.getElementById("interestList"),
	icon = document.createElement("img");
	icon.style.height = `${80}px`;	
	icon.style.width = `${80}px`;	
	icon.className = "interestList_icon";
	icon.src = interestSRC;
	interestList.appendChild(icon);
}

const ShowEditOptions =()=> {
  if(loginId === fromLinkId) {
    let editProfDiv = document.getElementById("buttonDiv")
    editProfButtn = document.createElement("button");
    editProfButtn.className = "editButt";
    editProfButtn.innerText = "Edit Profile";
    editProfDiv.appendChild(editProfButtn);

    let intText = document.getElementById("intText");
    intText.innerText = "Your Interests";

    let interestList = document.getElementById("interestList"),
    interestLink = document.createElement("a");
    interestLink.className = "interestLink";
    interestLink.innerText = "Edit...";
    interestLink.href = "prefsui.php";
    interestList.appendChild(interestLink);

    webAppMode();
  }
}

/*display interests if users are friends
const friendStatus =()=> {

}*/

window.addEventListener("load", init);