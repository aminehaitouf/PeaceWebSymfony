export class Map {

    static init() {
        let map = document.querySelector('.js-filter-map')
        map.innerHTML = '<div class="maps" id="map"></div>';
        if (map === null) {
            return
        }
        let icon = L.icon({
            iconUrl: 'assets/images/pictoAds/marker.svg',
        })

        let point = []
        let center = [48.864716, 2.349014]
        map = new L.map('map').setView(center, 13)
        let token = 'pk.eyJ1IjoiZGlza285MSIsImEiOiJja3NhOWduNjIyNG5qMnBwaGd2MjNxOWphIn0.9aeKYWuz_TeoFgyJtfUs7g'
        L.tileLayer(`https://api.mapbox.com/styles/v1/mapbox/light-v10/tiles/{z}/{x}/{y}?access_token=${token}`, {
            maxZoom: 18,
            minZoom: 1,
            attribution: '© <a href="https://www.mapbox.com/feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map)

        Array.from(document.querySelectorAll('.item')).forEach(function (item) {
            let marker = L.marker([parseFloat(item.dataset.lat), parseFloat(item.dataset.lng)], {icon: icon}).addTo(map)
            point.push(marker)
        })
        let featureGroup = new L.featureGroup(point)

        map.fitBounds(featureGroup.getBounds().pad(0.2))

    }

}

Map.init()