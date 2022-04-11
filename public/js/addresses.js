let inputAddress = document.querySelector('#ads_type_vehicule_address_nom');
let registerPro = document.querySelector('#register_commercant_adresses_nom');
let register = document.querySelector('#register_adresses_nom');

// algolia places, autocomplete un input lors de l'enregistrement des données (produits)
if (inputAddress !== null) {
    let place = places({
        container: inputAddress
    })
    place.on('change', e => {
        document.querySelector('#ads_type_vehicule_address_city').value = e.suggestion.city
        document.querySelector('#ads_type_vehicule_address_postcode').value = e.suggestion.postcode
        document.querySelector('#ads_type_vehicule_address_region').value = e.suggestion.administrative
        document.querySelector('#ads_type_vehicule_address_country').value = e.suggestion.country
        document.querySelector('#ads_type_vehicule_address_lat').value = e.suggestion.latlng.lat
        document.querySelector('#ads_type_vehicule_address_longi').value = e.suggestion.latlng.lng
        document.querySelector('#ads_type_vehicule_address_location').value = e.suggestion.countryCode
    })
}

// algolia places, autocomplete un input lors de l'enregistrement des données (inscription professionnelle)
if (registerPro !== null) {
    let place = places({
        container: registerPro
    })
    place.on('change', e => {
        document.querySelector('#register_commercant_adresses_lat').value = e.suggestion.latlng.lat
        document.querySelector('#register_commercant_adresses_longi').value = e.suggestion.latlng.lng
        document.querySelector('#register_commercant_adresses_city').value = e.suggestion.city
        document.querySelector('#register_commercant_adresses_postcode').value = e.suggestion.postcode
        document.querySelector('#register_commercant_adresses_region').value = e.suggestion.administrative
        document.querySelector('#register_commercant_adresses_country').value = e.suggestion.country
        document.querySelector('#register_commercant_adresses_location').value = e.suggestion.countryCode
    })
}

if (register !== null) {
    let place = places({
        container: register
    })
    place.on('change', e => {
        document.querySelector('#register_adresses_city').value = e.suggestion.city
        document.querySelector('#register_adresses_postcode').value = e.suggestion.postcode
        document.querySelector('#register_adresses_region').value = e.suggestion.administrative
        document.querySelector('#register_adresses_country').value = e.suggestion.country
        document.querySelector('#register_adresses_lat').value = e.suggestion.latlng.lat
        document.querySelector('#register_adresses_longi').value = e.suggestion.latlng.lng
        document.querySelector('#register_adresses_location').value = e.suggestion.countryCode
    })
}


const searchAddress = document.querySelector('#address')
const range = document.querySelector(".range");
const slideValue = document.querySelector(".sliderValue span");
const inputSlider = document.querySelector(".search__range");
if (searchAddress !== null) {
    // initialisation pour l'autocomplete des lieux
    let place = places({
        container: searchAddress
    })
    // auto complete pour les lieux
    place.on('change', e => {
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
    // afficher le input range
    searchAddress.addEventListener('focus', evt => {
        range.style.display = 'block'
    })
}


// input range pour la distance de recherche
if (slideValue !== null && inputSlider !== null) {
    inputSlider.oninput = (()=>{
        let value = inputSlider.value;
        slideValue.textContent = value;
        slideValue.style.left = (value/2) +"%";
        slideValue.classList.add("show");
    });
    inputSlider.onblur = (()=>{
        range.style.display = 'none'
        slideValue.classList.remove("show");
    });
}
