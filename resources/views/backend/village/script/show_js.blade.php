<script type="text/javascript">
	var table_package, table_order;

	$(document).ready(function() { 

		table_package = $('#package-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: '{!! route('user_village.packages', $village->id) !!}',
            columns: [
                // { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'rownum', name: 'rownum', searchable: false },
                { data: 'category_name', name: 'categories.name' },
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'is_active', name: 'is_active' }
            ],
        });

	});

	$(document).ready(function() { 

		table_order = $('#order-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [[ 3, "desc" ]],
            ajax: '{!! route('user_village.orders', $village->id) !!}',
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'rownum', name: 'rownum', searchable: false },
                { data: 'code', name: 'code' },
                { data: 'created_at', name: 'created_at' },
                { data: 'package_name', name: 'package_name' },
                { data: 'total_payment', name: 'total_payment' },
                { data: 'payment_type', name: 'payment_type' },
                { data: 'payment_status', name: 'payment_status' }
            ],
        });

	});

	function reload_table_package()
    {
        table_package.ajax.reload();
    }

    function reload_table_order()
    {
        table_order.ajax.reload();
    }


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

	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgAQOKoaiYIXHi0UxM76u3B50dVJLBZKk&callback=initMap" async defer></script>