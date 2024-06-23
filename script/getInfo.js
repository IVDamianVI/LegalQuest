document.getElementById("display").value = `${screen.width} x ${screen.height}`;
document.getElementById(
  "viewport"
).value = `${screen.availWidth} x ${screen.availHeight}`;
document.getElementById("colors").value = screen.colorDepth;
document.getElementById("cookies").value = navigator.cookieEnabled ? 1 : 0;
document.getElementById("java").value =
  window.navigator.javaEnabled() === true ? 1 : 0;
path = window.location.pathname;
document.getElementById("page").value = path.split("/").pop();

var fillInPage = (function () {
  var updateCityText = function (geoipResponse) {
    var cityName = geoipResponse.city.names.en || 'your city';
    var coords = geoipResponse.location.latitude + "," + geoipResponse.location.longitude || 'your location';
    document.getElementById('city').value = cityName;
    document.getElementById('coords').value = coords
  };

  var onSuccess = function (geoipResponse) {
    updateCityText(geoipResponse);
  };

  var onError = function (error) {
    document.getElementById('city').value = 'an error!  Please try again..';
    document.getElementById('coords').value = 'an error!  Please try again..';
  };

  return function () {
    if (typeof geoip2 !== 'undefined') {
      geoip2.city(onSuccess, onError);
    } else {
      document.getElementById('city').value = 'a browser that blocks GeoIP2 requests';
      document.getElementById('coords').value = 'a browser that blocks GeoIP2 requests';
    }
  };
}());

fillInPage();
