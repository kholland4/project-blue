window.addEventListener('load', function() {
  document.querySelector("img").addEventListener("click", function() {
    document.querySelector("input").click();
     document.querySelector('input[type="file"]').addEventListener('change', function() {
      if (this.files && this.files[0]) {
          var img = document.querySelector('img');  // $('img')[0]
          var src = URL.createObjectURL(this.files[0]);
          img.src = src // set src to file url
          imageObj.src = src;
      }
  });
});
});

/*so links in safari webapp do not open new tab */
var a = document.getElementsByTagName("a");
for(var i = 0; i < a.length; i++) {
  a[i].onclick=function() {
    window.location = this.getAttribute("href");
    return false
  }
} 