<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Autocomplete</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>

<div class="container">

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">

            <div class="panel panel-primary">

                <div class="panel-heading">Autocomplete</div>

                <div class="panel-body">

                    <div class="form-group">

                        {!! Form::text('search_text', null, array('placeholder' => 'Search Text','class' => 'form-control','id'=>'search_text')) !!}

                    </div>

                </div>

            </div>

        </div>
        <div class="col-md-12">
            <div id="googleMaps" style="width: 100%;height: 70vh" ></div>
        </div>
    </div>

</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF-pgv0MsgYevAgIwiKU7je6zI2U1DPp0&language=en"></script>
<script type="text/javascript">
    var map;
    var url = "{{ route('autocomplete.ajax') }}";
    var count = 10;

    $("#search_text").autocomplete({
        source: function (request, response) {
            $.get("{{ route('autocomplete.ajax') }}", {
                keyword: request.term,count:count
            }, function (data) {

                response(data);
            });
        },
        minLength: 3,
        select: function (event, ui) {
            $.get("/nearest-cities/"+ui.item.id, {
            }, function (data) {

                var mapOptions = {
                    center: new google.maps.LatLng(data.center.latitude, data.center.longitude),
                    zoom: 4
                    // mapTypeId: google.maps.MapTypeId.HYBRID
                };
                map = new google.maps.Map(document.getElementById("googleMaps"), mapOptions);

                for (let i=0;i<data.cities.length;i++){
                    marker = new google.maps.Marker({
                        position: {lat: data.cities[i].latitude, lng: data.cities[i].longitude},
                        map: map,
                        zoom: 1,
                        title: data.cities[i].name
                    });
                }
            });
        }
    });


</script>

</body>

</html>