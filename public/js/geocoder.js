mapboxgl.accessToken = 'pk.eyJ1Ijoia25pZmVib3NzIiwiYSI6ImNrOWlyazllcTE1NmQzZXBuZXh5MHVpM3QifQ.eNaU-QnXEb' +
    'cFzghOYUGVvA';
if (!mapboxgl.supported()) {
    alert('Your browser does not support Mapbox GL')
} else {
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        placeholder: 'Nhập địa chỉ của bạn',
        countries: 'vn',
        types: 'address,poi',
        bbox: [106.4, 10.5, 107.0, 11.2],
        proximity: [106.6975, 10.7758],
        // applies a client side filter to further limit results to those strictly within the Ho Chi Minh City region
        filter: function (item) {
            return item.context
                .map(function (i) {
                    return (
                        i.id.split('.').shift() === 'place' &&
                        (i.text === 'Ho Chi Minh City' || i.text === 'Thành phố Hồ Chí Minh' || i.text === 'TP.Hồ Chí Minh')
                    );
                })
                .reduce(function (acc, cur) {
                    return acc || cur;
                });
        },
    });
    geocoder.on('result', function (e) {
        const coords = {
            coordinates: e.result.center,
            context: {
                city: e.result.context[e.result.context.length - 3]['text'],
                province: e.result.context[e.result.context.length - 2]['text'],
                country: e.result.context[e.result.context.length - 1]['text']
            },
            short: e.result.properties.address,
            place_name: e.result.place_name,
            place_type: e.result.place_type[0]
        };
        makePostRequest(coords).then(() => window.location.reload())
    });
    async function makePostRequest(req) {
        var token = document.head.querySelector('meta[name="csrf-token"]');
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        await axios.post('/home', req);
    }
    geocoder.addTo('#geocoder')
}
