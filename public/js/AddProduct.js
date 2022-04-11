class AddProduct {

    static init() {
        new AddProduct()
    }

    constructor() {
        this.loadCategory();
        document.querySelector('.select-tag').addEventListener('change', (event) => {
            this.select(event)
        });
    }

    // LOCALHOST = "http://localhost:8000"
    LOCALHOST = "https://thouma.fr"

    async loadCategory() {
        fetch(this.LOCALHOST + "/api/category?metier=V%C3%A9hicule")
            .then(response => response.json())
            .then(response => {
                let category_vehicule = "";
                if (response['hydra:member'].length === 0) document.querySelector('.inputStyle__category').innerHTML = "";
                else {
                    for (const category in response['hydra:member']) {
                        let url = '/assets/images/allSvg.svg#car';
                        url = url.replace("car", response['hydra:member'][category].name);
                        category_vehicule += `
                            <div class="form-check">
                            <label class="form-check-label required">
                                <input type="radio" name="ads_category" required="required" value=` + response['hydra:member'][category].id + `>
                                <div class="category__choices">
                                    <div class="category_choices__image">
                                        <span title=` + response['hydra:member'][category].name + `">` + response['hydra:member'][category].name + `</span>
                                        <div>
                                            <svg class="category_choices__image__svg">
                                                <use xlink:href=` + url + `>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        `;
                    }
                    document.querySelector(".inputStyle__category").innerHTML = category_vehicule;
                }
            })
    }


    capitalize = (s) => {
        if (typeof s !== 'string') return s
        return s.charAt(0).toUpperCase() + s.slice(1).toLowerCase()
    }

    select(event) {
        const voiture = `
                        <div class="form-group form__group inputStyle">
                            <label for="example-search-input" class="col-form-label">Marque</label>
                            <select id="categorySelect" name="Marque" data-dropdown>
                                <option value="" disabled selected>Choisir la marque</option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group form__group inputStyle">
                            <label for="example-search-input" class="col-form-label">Modèle</label>
                            <select id="categorySelectModele" name="Modèle" data-dropdown>
                                <option value="" disabled selected>Choisir le modèle</option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Type de véhicule</label>
                            <select id="categorySelectModele" name="Type de véhicule" class="form-control">
                                <option value="" disabled selected>Choisir le type de véhicule</option>
                                <option value="Break">Break</option>
                                <option value="Berline">Berline</option>
                                <option value="Citadine">Citadine</option>
                                <option value="4x4-SUV">4x4-SUV</option>
                                <option value="Cabriolet">Cabriolet</option>
                                <option value="Coupé">Coupé</option>
                                <option value="Monospace">Monospace</option>
                                <option value="Bus et minibus">Bus et minibus</option>
                                <option value="Pick-up">Pick-up</option>
                                <option value="Société-commerciale">Société-commerciale</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Année du modèle</label>
                            <input name="Année" class="form-control" placeholder="Rentrer l'année du modèle">
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Carburant</label>
                            <select id="categorySelectModele" name="Carburant" class="form-control">
                                <option value="" disabled selected>Type de carburant</option>
                                <option value="Essence">Essence</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Electrique">Electrique</option>
                                <option value="Biocarburant">Biocarburant</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Kilométrage</label>
                            <div class="form-measure">
                                <input name="Kilométrage" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Kilométrage">
                                <div class="measure-label">km</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Puissance DIN</label>
                            <div class="form-measure">
                                <input name="Puissance DIN" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Puissance DIN">
                                <div class="measure-label">Ch</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Puissance fiscale</label>
                            <div class="form-measure">
                                <input name="Puissance fiscale" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Puissance fiscale">
                                <div class="measure-label">Cv</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Boîte de vitesse</label>
                            <select id="categorySelectModele" name="Boite de vitesse" class="form-control">
                                <option value="" disabled selected>Choisir la boîte de vitesse</option>
                                <option value="Manuelle">Manuelle</option>
                                <option value="Automatique">Automatique</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">État</label>
                            <select id="categorySelectModele" name="État" class="form-control">
                                <option value="" disabled selected>État du véhicule</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Très bon">Très bon</option>
                                <option value="Bon">Bon</option>
                                <option value="Correct">Correct</option>
                                <option value="Mauvais">Mauvais</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Nombre de place(s)</label>
                            <div class="form-measure">
                                <input name="Nombre de places" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Nombre de place">
                                <div class="measure-label">place(s)</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Nombre de portes</label>
                            <div class="form-measure">
                                <input name="Nombre de portes" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Nombre de portes">
                                <div class="measure-label">portes</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Couleur</label>
                            <select id="categorySelectModele" name="Couleur" class="form-control">
                                <option value="" disabled selected>Choisir la couleur</option>
                                <option value="Noir">Noir</option>
                                <option value="Gris">Gris</option>
                                <option value="Blanc">Blanc</option>
                                <option value="Rouge">Rouge</option>
                                <option value="Jaune">Jaune</option>
                                <option value="Vert">Vert</option>
                                <option value="Bleu">Bleu</option>
                                <option value="Marron">Marron</option>
                                <option value="Beige">Beige</option>
                                <option value="Orange">Orange</option>
                                <option value="Violet">Violet</option>
                                <option value="Bordeaux">Bordeaux</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
`
        const voitureOptions = `
                    <div class="form-group inputStyle-radio">
                        <label for="example-search-input" class="col-form-label">Crit'air</label>
                        <div class="d-flex">
                            <div class="mr-2">
                                <label>
                                    <input type="radio" id="1" name="Crit'air" value="1">
                                    <span>1</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" id="2" name="Crit'air" value="2">
                                    <span>2</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="3">
                                    <span>3</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="4">
                                    <span>4</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="5">
                                    <span>5</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="Non-classé" checked>
                                    <span>Non-classé</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <p style="border-top: 1px solid #dee2e6 !important;">
                        <span title="Cliquer sur les options que votre véhicule possède" class="options__info">Equipements et options</span>
                    </p>
                    <div class="form-group inputStyle-checkbox d-flex flex-wrap justify-content-center ">
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Toit panoramique]">
                                <span>Toit panoramique</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Toit ouvrant]">
                                <span>Toit ouvrant</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Vitre teinté]">
                                <span>Vitre teinté</span>
                            </label>
                        </div>

                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Sièges chauffants]">
                                <span>Sièges chauffants</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Aide au stationnement]">
                                <span>Aide au stationnement</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Fermeture centralisée]">
                                <span>Fermeture centralisée</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Porte papillon]">
                                <span>Porte papillon</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Ordinateur de bord]">
                                <span>Ordinateur de bord</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Voiture connectée]">
                                <span>Voiture connectée</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Prise allume-cigare]">
                                <span>Prise allume-cigare</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[GPS]">
                                <span>GPS</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Régulateur de vitesse]">
                                <span>Régulateur de vitesse</span>
                            </label>
                        </div>

                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Caméra de recul]">
                                <span>Caméra de recul</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Radar de recul]">
                                <span>Radar de recul</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Aide au freinage d'urgence]">
                                <span>Aide au freinage</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Climatisation de voiture]">
                                <span>Climatisation</span>
                            </label>
                        </div>
                        <div class="m-1">
                            <label>
                                <input type="checkbox" name="Option[Climatisation bi-zone]">
                                <span>Climatisation bi-zone</span>
                            </label>
                        </div>
                    </div>
               
`

        const moto = `
                        <div class="form-group form__group inputStyle">
                            <label for="example-search-input" class="col-form-label">Marque</label>
                            <select id="categorySelect" name="Marque" data-dropdown>
                                <option value="" disabled selected>Choisir la marque</option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group form__group inputStyle">
                            <label for="example-search-input" class="col-form-label">Modèle</label>
                            <select id="categorySelectModele" name="Modèle" data-dropdown>
                                <option value="" disabled selected>Choisir le modèle</option>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Type de moto</label>
                            <select id="categorySelectModele" name="Type de véhicule" class="form-control">
                                <option value="" disabled selected>Choisir le type de moto</option>
                                <option value="Standard">Standard</option>
                                <option value="Cruiser">Cruiser</option>
                                <option value="Sport">Sport</option>
                                <option value="Tourisme">Tourisme</option>
                                <option value="Aventure">Aventure</option>
                                <option value="Scooter">Scooter</option>
                                <option value="Quad">Quad</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Année du modèle</label>
                            <input name="Année" class="form-control" placeholder="Rentrer l'année du modèle">
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Carburant</label>
                            <select id="categorySelectModele" name="Carburant" class="form-control">
                                <option value="" disabled selected>Type de carburant</option>
                                <option value="Essence">Essence</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Electrique">Electrique</option>
                                <option value="Biocarburant">Biocarburant</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Kilométrage</label>
                            <div class="form-measure">
                                <input name="Kilométrage" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Kilométrage">
                                <div class="measure-label">km</div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Cylindrée</label>
                            <div class="form-measure">
                                <input name="Cylindrée" class="form-control input-measure flex-lg-grow-1"
                                       placeholder="Cylindrée">
                                <div class="measure-label">cm<sup>3</sup></div>
                            </div>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Boîte de vitesse</label>
                            <select id="categorySelectModele" name="Boite de vitesse" class="form-control">
                                <option value="" disabled selected>Choisir la boîte de vitesse</option>
                                <option value="Manuelle">Manuelle</option>
                                <option value="Automatique">Automatique</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">État</label>
                            <select id="categorySelectModele" name="État" class="form-control">
                                <option value="" disabled selected>État du véhicule</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Très bon">Très bon</option>
                                <option value="Bon">Bon</option>
                                <option value="Correct">Correct</option>
                                <option value="Mauvais">Mauvais</option>
                            </select>
                        </div>
                        <div class="form-group inputStyle">
                            <label for="example-search-input" class="col-form-label">Couleur</label>
                            <select id="categorySelectModele" name="Couleur" class="form-control">
                                <option value="" disabled selected>Choisir la couleur</option>
                                <option value="Noir">Noir</option>
                                <option value="Gris">Gris</option>
                                <option value="Blanc">Blanc</option>
                                <option value="Rouge">Rouge</option>
                                <option value="Jaune">Jaune</option>
                                <option value="Vert">Vert</option>
                                <option value="Bleu">Bleu</option>
                                <option value="Marron">Marron</option>
                                <option value="Beige">Beige</option>
                                <option value="Orange">Orange</option>
                                <option value="Violet">Violet</option>
                                <option value="Bordeaux">Bordeaux</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
`
        const motoOptions = `
<div class="form-group inputStyle-radio">
                        <label for="example-search-input" class="col-form-label">Crit'air</label>
                        <div class="d-flex">
                            <div class="mr-2">
                                <label>
                                    <input type="radio" id="1" name="Crit'air" value="1">
                                    <span>1</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" id="2" name="Crit'air" value="2">
                                    <span>2</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="3">
                                    <span>3</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="4">
                                    <span>4</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="5">
                                    <span>5</span>
                                </label>
                            </div>
                            <div class="mr-2">
                                <label>
                                    <input type="radio" name="Crit'air" value="Non-classé" checked>
                                    <span>Non-classé</span>
                                </label>
                            </div>
                        </div>
                    </div>
`
        const content = document.querySelector('#content');
        const loading = document.querySelector('.loading');
        const contain = document.querySelector('.contain');
        const contain2 = document.querySelector('.contain2');

        contain.innerHTML = "";
        contain2.innerHTML = "";
        content.classList.add('desable')
        loading.classList.remove('desable')
        if (parseInt(event.target.value) === 90 || parseInt(event.target.value) === 92) {
            contain.innerHTML = voiture;
            contain2.innerHTML = voitureOptions;
        } else if (parseInt(event.target.value) === 91) {
            contain.innerHTML = moto;
            contain2.innerHTML = motoOptions;
        }

        fetch(this.LOCALHOST + "/api/categories?parentId=" + event.target.value)
            .then(response => response.json())
            .then(response => {
                content.classList.remove('desable')
                loading.classList.add('desable')
                if (response['hydra:member'].length === 0) document.getElementById("categorySelect").innerHTML = "<option></option>";
                else {
                    let options = ``;
                    options = "<option value='' disabled selected>Choisir la marque</option>"
                    for (const categoryId in response['hydra:member']) {
                        options += `<option data-id=` + response['hydra:member'][categoryId].id + ` value=` + this.capitalize(response['hydra:member'][categoryId].name) + `>` + this.capitalize(response['hydra:member'][categoryId].name) + `</option>`;
                    }
                    document.getElementById("categorySelect").innerHTML = options;
                    // Get dropdowns
                    const dropdowns = document.querySelectorAll('[data-dropdown]');
                    // Check if dropdowns exist on page
                    if(dropdowns.length > 0) {
                        // Loop through dropdowns and create custom dropdown for each select element
                        dropdowns.forEach(dropdown => {
                            this.createCustomDropdown(dropdown);
                        });
                    }
                    const config = {  attributes: true };
                    const selected = document.querySelector('.dropdown__selected');

                    const observer = new MutationObserver((mutations) => {
                        this.loadModel(mutations[0].target.id)
                    });

                    observer.observe(selected, config);

                }
            })

    }

    loadModel = (id) => {
        console.log(id)
        fetch(this.LOCALHOST + "/api/categories?parentId=" + id)
            .then(response => response.json())
            .then(response => {
                let options = ``;
                options = "<option value='' disabled selected>Choisir le modèle</option>"
                for (const categoryId in response['hydra:member']) {
                    options += `<option data-id=` + response['hydra:member'][categoryId].id + ` value=` + this.capitalize(response['hydra:member'][categoryId].name) + `>` + this.capitalize(response['hydra:member'][categoryId].name) + `</option>`;
                }
                const model = document.getElementById("categorySelectModele");
                model.innerHTML = options;
                model.parentNode.removeChild(model.parentNode.childNodes[4])
                this.createCustomDropdown(model);
            })
    }

    // Create custom dropdown
    createCustomDropdown(dropdown) {
        // Get all options and convert them from nodelist to array
        const options = dropdown.querySelectorAll('option');
        const optionsArr = Array.prototype.slice.call(options);

        // Create custom dropdown element and add class dropdown to it
        // Insert it in the DOM after the select field
        const customDropdown = document.createElement('div');
        customDropdown.classList.add('dropdown');
        dropdown.insertAdjacentElement('afterend', customDropdown);

        // Create element for selected option
        // Add class to this element, text from the first option in select field and append it to custom dropdown
        const selected = document.createElement('div');
        selected.classList.add('dropdown__selected');
        selected.textContent = optionsArr[0].textContent;
        customDropdown.appendChild(selected);

        // Create element for dropdown menu, add class to it and append it to custom dropdown
        // Add click event to selected element to toggle dropdown menu
        const menu = document.createElement('div');
        menu.classList.add('dropdown__menu');
        customDropdown.appendChild(menu);
        selected.addEventListener('click', this.toggleDropdown.bind(menu));

        // Create serach input element
        // Add class, type and placeholder to this element and append it to menu element
        const search = document.createElement('input');
        search.placeholder = 'Rechercher...';
        search.type = 'text';
        search.classList.add('dropdown__menu_search');
        menu.appendChild(search);

        // Create wrapper element for menu items, add class to it and append to menu element
        const menuItemsWrapper = document.createElement('div');
        menuItemsWrapper.classList.add('dropdown__menu_items');
        menu.appendChild(menuItemsWrapper);

        // Loop through all options and create custom option for each option and append it to items wrapper element
        // Add click event for each custom option to set clicked option as selected option
        optionsArr.forEach(option => {
            const item = document.createElement('div');
            item.classList.add('dropdown__menu_item');
            item.dataset.value = option.value;
            item.dataset.id = option.dataset.id;
            item.textContent = option.textContent;
            menuItemsWrapper.appendChild(item);

            item.addEventListener('click', this.setSelected.bind(item, selected, dropdown, menu));
        });

        // Add selected class to first custom option
        menuItemsWrapper.querySelector('div').classList.add('selected');

        // Add input event to search input element to filter items
        // Add click event to document element to close custom dropdown if clicked outside of it
        // Hide original dropdown(select)
        search.addEventListener('input', this.filterItems.bind(search, optionsArr, menu));
        document.addEventListener('click', this.closeIfClickedOutside.bind(customDropdown, menu));
        dropdown.style.display = 'none';
    }

// Toggle dropdown
    toggleDropdown() {
        // Check if dropdown is opened and if it is close it, otherwise open it and focus search input
        if(this.offsetParent !== null) {
            this.style.display = 'none';
        }else {
            this.style.display = 'block';
            this.querySelector('input').focus();
        }
    }

// Set selected option
    setSelected(selected, dropdown, menu) {
        // Get value and label from clicked custom option
        const value = this.dataset.value;
        const dataId = this.dataset.id;
        const label = this.textContent;

        // Change the text on selected element
        // Change the value on select field
        selected.textContent = label;
        selected.id = dataId;
        dropdown.value = value;

        // Close the menu
        // Reset search input value
        // Remove selected class from previously selected option and show all divs if they were filtered
        // Add selected class to clicked option
        menu.style.display = 'none';
        menu.querySelector('input').value = '';
        menu.querySelectorAll('div').forEach(div => {
            if(div.classList.contains('selected')) {
                div.classList.remove('selected');
            }
            if(div.offsetParent === null) {
                div.style.display = 'block';
            }
        });
        this.classList.add('selected');
    }

// Filter items
    filterItems(itemsArr, menu) {
        // Get all custom options
        // Get the value of search input and convert it to all lowercase characters
        // Get filtered items
        // Get the indexes of filtered items
        const customOptions = menu.querySelectorAll('.dropdown__menu_items div');
        const value = this.value.toLowerCase();
        const filteredItems = itemsArr.filter(item => item.value.toLowerCase().includes(value));
        const indexesArr = filteredItems.map(item => itemsArr.indexOf(item));

        // Check if option is not inside indexes array and hide it and if it is inside indexes array and it is hidden show it
        itemsArr.forEach(option => {
            if(!indexesArr.includes(itemsArr.indexOf(option))) {
                customOptions[itemsArr.indexOf(option)].style.display = 'none';
            }else {
                if(customOptions[itemsArr.indexOf(option)].offsetParent === null) {
                    customOptions[itemsArr.indexOf(option)].style.display = 'block';
                }
            }
        });
    }

// Close dropdown if clicked outside dropdown element
    closeIfClickedOutside(menu, e) {
        if(e.target.closest('.dropdown') === null && e.target !== this && menu.offsetParent !== null) {
            menu.style.display = 'none';
        }
    }
}

AddProduct.init()