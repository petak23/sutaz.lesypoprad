function initMap() 
{
  var map = new google.maps.Map(document.getElementById('map1'), {
    zoom: 12,
    center: {lat: 49.021956, lng: 20.285658},
    mapTypeId: 'roadmap'
  });

  // Define the LatLng coordinates for the polygon's path.
  var forestCoords = [
    {lat:49.0270353, lng:20.2575445},
    {lat:49.0240243, lng:20.2596474},
    {lat:49.0218011, lng:20.2601624},
    {lat:49.0206754, lng:20.2578449},
    {lat:49.0223358, lng:20.2546262},
    {lat:49.0227861, lng:20.2523088},
    {lat:49.0217729, lng:20.2494336},
    {lat:49.0187334, lng:20.2472878},
    {lat:49.0146242, lng:20.2471161},
    {lat:49.0133294, lng:20.246129},
    {lat:49.0108524, lng:20.2460861},
    {lat:49.0086849, lng:20.2456999},
    {lat:49.0058135, lng:20.242374},
    {lat:49.0075588, lng:20.2503777},
    {lat:49.0068551, lng:20.2604628},
    {lat:49.0067425, lng:20.2653551},
    {lat:49.0059542, lng:20.2682734},
    {lat:49.0056164, lng:20.2718782},
    {lat:49.0058698, lng:20.2804613},
    {lat:49.0048281, lng:20.2867699},
    {lat:49.0036459, lng:20.289774},
    {lat:49.003477, lng:20.2949667},
    {lat:49.0043778, lng:20.2956533},
    {lat:49.0040962, lng:20.3013181},
    {lat:49.0076013, lng:20.3000095},
    {lat:49.010768, lng:20.2993012},
    {lat:49.0132168, lng:20.2971983},
    {lat:49.0134983, lng:20.2925634},
    {lat:49.0171573, lng:20.2934217},
    {lat:49.0248122, lng:20.2929497},
    {lat:49.0259942, lng:20.291276},
    {lat:49.0263881, lng:20.2858686},
    {lat:49.0261067, lng:20.2830792},
    {lat:49.0262755, lng:20.2784014},
    {lat:49.0280202, lng:20.2762556},
    {lat:49.0273028, lng:20.2715779},
    {lat:49.0285127, lng:20.2636385},
    {lat:49.0280836, lng:20.2592182},
    {lat:49.0270353, lng:20.2575445}
  ];
  // Construct the polygon.
  var urbanForest = new google.maps.Polygon({
    paths: forestCoords,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35
  });
  urbanForest.setMap(map);
}