@section('js')
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
  tinymce.init({
    selector: "textarea",theme: "modern",
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
   image_advtab: true ,
   
   
   filemanager_title:"Responsive Filemanager" ,
   }/library/rm/tinymce/plugins/responsivefilemanager/plugin.min.js"}
 });
  
 function initMap() {
    var latStr = "{{ $village->village_detail->lat }}";
    var lngStr = "{{ $village->village_detail->lng }}";
    var latCoor = parseFloat(latStr.replace(',', '.'));
    var lngCoor = parseFloat(lngStr.replace(',', '.'));
    var myLatLng = { lat: latCoor, lng: lngCoor }
    // var myLatLng = {lat: -8.614762, lng: 115.193850};
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      zoom: 10
    });

    var marker = new google.maps.Marker({
      map: map,
      title: 'Hello World!',
      position: new google.maps.LatLng(latCoor, lngCoor)
    });

    var input = document.getElementById('village_address');
    var searchBox = new google.maps.places.SearchBox(input);

    map.addListener('click', function(e){
        marker.setPosition(new google.maps.LatLng(e.latLng.lat(),e.latLng.lng()));
        $('#lat').val(e.latLng.lat());
        $('#lng').val(e.latLng.lng());
    });

    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }


        marker.setPosition(new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng()));
        $('#lat').val(place.geometry.location.lat());
        $('#lng').val(place.geometry.location.lng());

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
  }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgAQOKoaiYIXHi0UxM76u3B50dVJLBZKk&libraries=places&callback=initMap" async defer></script>
@stop
