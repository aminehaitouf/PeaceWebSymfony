import {Map} from "./Map.js";
import {slider} from "./slider.js";

/**
 * @property {HTMLElement} content
 * @property {HTMLFormElement} sorting
 * @property {HTMLFormElement} form
 */
export class Filter {

    static init() {
        new Filter(document.querySelector('.js-filter'))
    }

    /*
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return
        }
        this.content = element.querySelector('.js-filter-content')
        this.pagination = element.querySelector('.js-filter-pagination')
        this.map = element.querySelector('.js-filter-map')
        this.price = element.querySelector('.js-filter-price')
        this.category = element.querySelector('.js-filter-category')
        this.form = element.querySelector('.js-filter-form')
        this.bindEvents()
    }

    /**
     * Ajoute les comportements aux différents éléments
     */
    bindEvents() {
        const aClickListener = e => {
            if (e.target.tagName === 'A') {
                e.preventDefault()
                this.loadUrl(e.target.getAttribute('href'))
            }
        }
        this.pagination.addEventListener('click', aClickListener)
        this.form.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })
        this.price.addEventListener('change', e => {
            this.loadForm()
        })
        this.category.addEventListener('change', e => {
            this.loadForm()
        })
    }

    async loadForm() {
        const data = new FormData(this.form)
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()
        data.forEach((value, key) => {
            params.append(key, value)
        })
        return this.loadUrl(url.pathname + '?' + params.toString())
    }

    async loadUrl(url) {
        this.smoothScroll(url, 1000)
        this.map.innerHTML = ""
        this.showLoader()
        const ajaxUrl = url + '&ajax=1';
        const response = await fetch(ajaxUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        if (response.status >= 200 && response.status < 300) {
            const data = await response.json()
            data.ads ? this.content.innerHTML = data.ads : this.content.innerHTML = "<div style=\"text-align: center; margin-top: 10px\"><h3>Aucune annonce</h3></div>"
            this.pagination.innerHTML = data.pagination
            slider()
            history.replaceState({}, '', url)
            data.ads && Map.init()
        } else {
            console.error(response)
        }
        this.hideLoader()
    }

    showLoader() {
        const loader = document.querySelector('.loading');
        if (loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', 'false')
        loader.classList.remove('desable')
    }

    hideLoader() {
        const loader = document.querySelector('.loading')
        if (loader === null) {
            return
        }
        loader.setAttribute('aria-hidden', 'true')
        loader.classList.add('desable')
    }

    smoothScroll(target, duration) {
        var target = document.querySelector(".js-filter-form");
        var targetPosition = target.getBoundingClientRect().top;
        var startPosition = window.pageYOffset || window.scrollY;
        var distance = targetPosition - startPosition;
        var startTime = null;

        function loop(currentTime) {
            if (startTime === null) startTime = currentTime;
            var timeElapsed = currentTime - startTime;
            var run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(loop);
        }
        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }
        requestAnimationFrame(loop);
    }

}

Filter.init();