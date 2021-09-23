<!DOCTYPE html>
<html lang="en">

<head>
    <title>AirMap | Maps SDK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script src="https://cdn.airmap.io/js/contextual-airspace/1.0.0/airmap.contextual-airspace-plugin.min.js" async=false defer=false></script> 
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.39.1/mapbox-gl.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.39.1/mapbox-gl.css" rel="stylesheet" />
    <style>
        body {
            margin:0;
            padding:0;
        }

        #map {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script>
       
        // Include your airmap api key
        var AIRMAP_API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVkZW50aWFsX2lkIjoiY3JlZGVudGlhbHxNSzZBa2RhRnY4eW5rTGlQOE01M1hVT3lSR0VHIiwiYXBwbGljYXRpb25faWQiOiJhcHBsaWNhdGlvbnxQRERZbkxQczJxazIwRENBM1gyZHdIYTNCR1BMIiwib3JnYW5pemF0aW9uX2lkIjoiZGV2ZWxvcGVyfEJQeDVBUG1JeXltVzBMRlBSUFhrQkk5SlI2USIsImlhdCI6MTYwMjc5MDcxMn0.ECYSlP4XuOA_fzVo9jL94EQQ5KxfoZr9R1-2GiHuZKc"
        
        // Include your mapbox access token
        var MAPBOX_ACCESS_TOKEN = "pk.eyJ1Ijoic21pc3MiLCJhIjoiY2tnYjhoenIwMGIzMDJxcWFreDY1Yzh2ZyJ9.EJzR7I07-X7snv08tp8JdQ"
        
        if (AIRMAP_API_KEY && MAPBOX_ACCESS_TOKEN) {
        mapboxgl.accessToken = MAPBOX_ACCESS_TOKEN
        

        // Create an instance of the map
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/airmap/cipg7gw9u000jbam53kwpal1q',
            center: [-118.496475, 34.024212],
            zoom: 10
        })

        function getRandomColor() {
          var letters = '0123456789ABCDEF';
          var color = '#';
          for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
          }
          return color;
        }

        function addRulesets(jurisdictions){
  
          let rulesets = [];
          jurisdictions.forEach(jurisdiction => {
            jurisdiction.rulesets.forEach(ruleset => {
              if (ruleset.selection_type == "pick1") {
                if (ruleset.id == "usa_part_107")
                  rulesets.push(ruleset);
              } else {
                rulesets.push(ruleset);
              }
            })
          })
          return rulesets;
          
        }

        function parseJurisdictions(){
            var layers = map.queryRenderedFeatures()

            // Iterate through layers
            var jurisdictions = layers.filter(x => x.layer.source == "jurisdictions" && x.properties.jurisdiction)
                .map(feature => JSON.parse(feature.properties.jurisdiction))

            // Remove duplicate or empty jurisdictions
            jurisdictions = jurisdictions.filter(x => x.rulesets.length > 0).filter((obj, pos, arr) => {
                return arr.map(mapObj => mapObj["uuid"]).indexOf(obj["uuid"]) === pos;
            });

            return jurisdictions;
        }
        
      
        // Add layers
        map.on('load', () => {
            
            // Add Jurisdictions
            map.addLayer({
                "id": "jurisdictions",
                "type": "fill",
                "source": {
                    type: 'vector',
                    tiles: [ 'https://api.airmap.com/tiledata/v1/base-jurisdiction/{z}/{x}/{y}' ],
                    "minzoom": 6,
                    "maxzoom": 12
                },
                "source-layer": "jurisdictions",
                "minZoom": 6,
                "maxZoom": 22
            }, 'background')

        })

        map.on('sourcedata', (data) =>{

            // Check for jurisdiction
            if(data.sourceId == 'jurisdictions' && data.isSourceLoaded){

                // Parse jurisdiction
                var jurisdictions = parseJurisdictions();
                // Add rulesets
                var rulesets = addRulesets(jurisdictions);

                rulesets.forEach(ruleset => {
                    // Add Part 107 Source
                    map.addSource(ruleset.id, {
                        type: 'vector',
                        tiles: [ 'https://api.airmap.com/tiledata/v1/'+ruleset.id+'/'+ruleset.layers.join(',')+'/{z}/{x}/{y}?apiKey=$AIRMAP_API_KEY' ],
                        "minzoom": 6,
                        "maxzoom": 12
                    })
                    
                    ruleset.layers.forEach(layer => {
                        // All Controlled Airspace
                        let airspaceLayer = {
                            "id": ruleset.id+"_"+layer,
                            "source": ruleset.id,
                            "source-layer": ruleset.id+"_"+layer,
                            "type": "fill",
                            "interactive": true,
                            "paint": {
                                "fill-opacity": 0.4,
                                "fill-color": getRandomColor()
                            }
                        }

                        map.addLayer(airspaceLayer, ruleset.id)
                    })
            
                })
            }

        })

        } else {
            console.error(
                'Missing AIRMAP_API_KEY or MAPBOX_ACCESS_TOKEN. ' +
                'These are required for developing the Maps SDK locally.\n\n'
            );
        }



    </script>
</body>

</html>
