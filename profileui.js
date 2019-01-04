function init() {
  loadf("getusers.php?r=" + Math.random(), function() {
    if(this.status == 200) {
      users = JSON.parse(this.responseText);
      findCurrentUser(users);
    }
  });
  loadf("prefdata.json", function() {
    if(this.status == 200) {
      var prefDataRaw = JSON.parse(this.responseText);
      prefData = prefDataRaw.data;
    }
  });  
}

const findCurrentUser =(users)=> {
	const filteredUser = users.filter((users)=> users.id === fromLinkId),
	prefsArray = Object.keys(filteredUser[0].prefs),
	arrayLength = (prefsArray.length < 8) ? prefsArray.length : 8;
	loopIntersts(arrayLength, prefsArray);
}

const loopIntersts =(arrayLength, prefsArray)=> {
	forRenderImages = [];
	let choose = randomNoRepeat(prefsArray);
	for( let i = 0; i < arrayLength; i++) {
			forRenderImages[i] = choose();
			console.log(forRenderImages[i]);
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

window.addEventListener("load", init);