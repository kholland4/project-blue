window.addEventListener('load', function() {
  document.querySelector("img").addEventListener("click", handleImageClick);
});

function handleImageClick() {
  document.querySelector("input").click();
  document.querySelector('input[type="file"]').addEventListener('change', uploadImage);
}

var el = document.getElementById('cropPopup');
var resize = new Croppie(el, {
    viewport: { width: 256, height: 256, type: "circle" },
    boundary: { width: 300, height: 300 },
    enableOrientation: false,
    showZoomer: false,
});

function uploadImage() {
  var doneButtn = document.getElementById("done");
  if (this.files && this.files[0]) {
      var profilePic = document.getElementById("profilePic");
      var src = URL.createObjectURL(this.files[0]);
      showCropDiv();
      resize.bind({
          url: src,
      });
      doneButtn.addEventListener("click", function() {
        resize.result('base64').then(function(image) {
          if(image!="data:,"){
            profilePic.src = image;
            hideCropDiv(); 
          }
        });
      })
  }
}

function showCropDiv() {
  var cropDiv = document.getElementById("cropPopup");
  cropDiv.style.display = "block";
}
function hideCropDiv() {
  var cropDiv = document.getElementById("cropPopup");
  cropDiv.style.display = "none";
}

/*so links in safari webapp do not open new tab */
var a = document.getElementsByTagName("a");
for(var i = 0; i < a.length; i++) {
  a[i].onclick=function() {
    window.location = this.getAttribute("href");
    return false
  }
} 