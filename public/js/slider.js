export const slider = () => {
    let imageDiv = document.querySelectorAll('.image__article');
    imageDiv.forEach(element => {
        element.firstElementChild.firstElementChild.classList.add('active');

        let items = element.querySelectorAll('.image__article img');
        let next = element.querySelector('.image__article .next');
        let previous = element.querySelector('.image__article .previous');
        let dot = element.querySelector('.image__article .dot-image');
        dot.children[0].style.opacity = 1;
        let nbSlide = items.length;
        let count = 0;

        next.addEventListener('click', (e) => {
            e.preventDefault()
            slidesNext()
        });

        previous.addEventListener('click', (e) => {
            e.preventDefault()
            slidesPrevious()
        })

        const slidesNext = () => {
            if (count === 0) {
                dot.children[count].style.opacity = 0.2;
            }else {
                dot.children[count].style.opacity = 0.2;
            }
            items[count].classList.remove('active');
            if (count < nbSlide - 1) {
                count++;
            } else {
                count = 0;
            }
            items[count].classList.add('active');
            dot.children[count].style.opacity = 1;
        }

        const slidesPrevious = () => {
            if (count === 0) {
                dot.children[count].style.opacity = 0.2;
            }else {
                dot.children[count].style.opacity = 0.2;
            }
            items[count].classList.remove('active');
            if (count > 0) {
                count--;
            } else {
                count = nbSlide - 1;
            }
            items[count].classList.add('active');
            dot.children[count].style.opacity = 1;
        }
    })
}


slider()