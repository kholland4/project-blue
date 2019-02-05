const initLocation =()=> {
	if (!navigator.geolocation) {alert("I'll be jitterbugged! geolocation is not supported in your browser.")}
	let geoId = navigator.geolocation.watchPosition(); 
}

const errorHandler =(err)=> {

}

const findConnectionArea =(coords)=> {
	let latitude = coords.latitude;
	let longitude = coords.longitude;
	let radius = 
} 