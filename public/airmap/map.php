<!doctype html>
<html>
    <head>
        <title>Mapbox-gl-js Contextual Airspace Plugin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <script src="https://cdn.airmap.io/js/contextual-airspace/v1.3/airmap.contextual-airspace-plugin.min.js" async=false defer=false></script>       
        <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.js"></script>
        <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.44.0/mapbox-gl.css" rel="stylesheet" />
        <style>
            body { margin: 0; padding: 0; }
            .map {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                right: 0;
            }
        </style>
    </head>
    <body>

        <div id="map" class="map"></div>
        <script>
            const AIRMAP_API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVkZW50aWFsX2lkIjoiY3JlZGVudGlhbHxNSzZBa2RhRnY4eW5rTGlQOE01M1hVT3lSR0VHIiwiYXBwbGljYXRpb25faWQiOiJhcHBsaWNhdGlvbnxQRERZbkxQczJxazIwRENBM1gyZHdIYTNCR1BMIiwib3JnYW5pemF0aW9uX2lkIjoiZGV2ZWxvcGVyfEJQeDVBUG1JeXltVzBMRlBSUFhrQkk5SlI2USIsImlhdCI6MTYwMjc5MDcxMn0.ECYSlP4XuOA_fzVo9jL94EQQ5KxfoZr9R1-2GiHuZKc'
            const MAPBOX_ACCESS_TOKEN = 'pk.eyJ1Ijoic21pc3MiLCJhIjoiY2tnYjhoenIwMGIzMDJxcWFreDY1Yzh2ZyJ9.EJzR7I07-X7snv08tp8JdQ'
            if (AIRMAP_API_KEY && MAPBOX_ACCESS_TOKEN) {
                mapboxgl.accessToken = MAPBOX_ACCESS_TOKEN
                const map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/streets-v8',
                    center: [-5.547099500000002, 7.5468545],
                    zoom: 6
                })
                const config = {
                    "airmap": {
                        "api_key": AIRMAP_API_KEY
                    },
                    "auth0": {
                        "client_id": "18de4f12-26b8-4ebf-bce8-19d9e62c0e92",
                        "callback_url": "NULL"
                    },
                    "mapbox": {
                        "access_token": MAPBOX_ACCESS_TOKEN
                    }
                }
                const options = {
                    preferredRulesets: [
                        'usa_part_107',
                        'deu_rules_waiver'
                    ],

                    enableRecommendedRulesets: true,
                    theme: 'light'
                    /* refer to the docs for a comprehensive list of options */
                }
                const plugin = new this.AirMap.ContextualAirspacePlugin(config, options);
                map.addControl(plugin, 'top-left')

                // Example for how ruleset changes are surfaced to the consuming application.
                plugin.on('jurisdictionChange', (data) => console.log('jurisdictionChange', data))
                plugin.on('airspaceLayerClick', (data) => console.log('airspaceLayerClick', data))
                
                // Example for how the consuming app can call the plugin for jurisdictions or selected rulesets.
                setTimeout(() => {
                    console.log({
                        jurisdictions: plugin.getJurisdictions(),
                        selectedRulelsets: plugin.getSelectedRulesets()
                    })
                }, 5000)
            } else {
                console.error(
                    'Missing AIRMAP_API_KEY or MAPBOX_ACCESS_TOKEN. ' +
                    'These are required for developing locally.\n\n' +
                    'Please save these values in localStorage by entering the following in your browser console:\n\n' +
                    'localStorage.setItem(\'AIRMAP_API_KEY\', \'<your_key>\');\n' +
                    'localStorage.setItem(\'MAPBOX_ACCESS_TOKEN\', \'<your_token>\');\n\n'
                );
            }
        </script>
    </body>
</html>
