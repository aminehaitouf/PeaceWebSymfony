import {enableBodyScroll, disableBodyScroll} from "./body-scroll-lock.js";

/**
 * @property {HTMLElement} element
 * @property {string[]} images
 * @property {string} url image actuellement chargée
 */
class Lightbox {

    static init() {
        const links = Array.from(document.querySelectorAll('a[href$=".png"], a[href$=".jpg"], a[href$=".jpeg"]'));
        const gallery = links.map(link => link.getAttribute('href'));
        links.forEach(link => link.addEventListener('click', evt => {
            evt.preventDefault();
            new Lightbox(evt.currentTarget.getAttribute('href'), gallery)
        }))
    }

    /**
     * @param {string} url URL de l'image
     * @param {string[]} images chemin des images de la lightbox
     */
    constructor(url, images) {
        this.element = this.builtDOM(url, images);
        this.images = images;
        this.loadImage(url);
        this.onKeyUp = this.onKeyUp.bind(this);
        document.body.appendChild(this.element);
        disableBodyScroll(this.element)
        document.addEventListener("keyup", this.onKeyUp)
    }

    /**
     * @param {string} url
     */
    loadImage(url) {
        this.url = null;
        const image = new Image();
        const container = this.element.querySelector('.lightbox__container')
        const loader = document.createElement('div');
        loader.classList.add("lightbox__loader");
        container.innerHTML = "";
        container.appendChild(loader)
        image.onload = () => {
            container.removeChild(loader);
            container.appendChild(image);
            this.url = url;
        }
        image.src = url;
    }

    /**
     * @param {KeyboardEvent} e
     */
    onKeyUp(e) {
        if (e.key === "Escape") {
            this.close(e)
        } else if (e.key === "ArrowLeft") {
            this.next(e)
        } else if (e.key === "ArrowRight") {
            this.prev(e)
        }
    }

    /**
     * Ferme la lightbox
     * @param {MouseEvent | KeyboardEvent} e
     */
    close(e) {
        e.preventDefault();
        this.element.classList.add('fadeOut')
        enableBodyScroll(this.element)
        window.setTimeout(() => {
            this.element.parentElement.removeChild(this.element);
        }, 200)
        document.removeEventListener("keyup", this.onKeyUp)
    }

    /**
     * @param {MouseEvent | KeyboardEvent} e
     */
    next(e) {
        e.preventDefault();
        let i = this.images.findIndex(image => image === this.url)
        if (i === this.images.length - 1) {
            i = -1
        }
        this.loadImage(this.images[i + 1]);
    }

    /**
     * @param {MouseEvent | KeyboardEvent} e
     */
    prev(e) {
        e.preventDefault();
        let i = this.images.findIndex(image => image === this.url)
        if (i === 0) {
            i = this.images.length;
        }
        this.loadImage(this.images[i - 1]);
    }

    /**
     * @param {string} url
     * @param {string[]} images
     * return {HTMLElement}
     */
    builtDOM(url, images) {
        const dom = document.createElement('div');
        dom.classList.add("lightbox");
        if (images.length <= 1) {
            dom.innerHTML = `<div class="lightbox">
         <button class="lightbox__close">Fermer</button>
         <div class="lightbox__container"></div>
         </div>`
            dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
        } else {
            dom.innerHTML = `<div class="lightbox">
         <button class="lightbox__close">Fermer</button>
         <button class="lightbox__next">Suivant</button>
         <button class="lightbox__prev">Précédent</button>
         <div class="lightbox__container"></div>
         </div>`
            dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
            dom.querySelector('.lightbox__next').addEventListener('click', this.next.bind(this));
            dom.querySelector('.lightbox__prev').addEventListener('click', this.prev.bind(this));
        }

        return dom;
    }
}

Lightbox.init()