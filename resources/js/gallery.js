export function initGalleries() {
    document.querySelectorAll('[data-gallery]').forEach(gallery => {
        let index = 0;
        const pictures = gallery.querySelectorAll('picture');
        const thumbs   = gallery.querySelectorAll('img[data-index]');
        const prevBtn  = gallery.querySelector('[data-prev]');
        const nextBtn  = gallery.querySelector('[data-next]');

        const show = (i) => {
            pictures[index].classList.add('opacity-0','pointer-events-none');
            pictures[index].classList.remove('opacity-100','pointer-events-auto');
            thumbs[index]?.classList.remove('border-blue-500','ring-2','ring-blue-300','dark:ring-blue-600');
            thumbs[index]?.classList.add('border-transparent');
            index = i;
            pictures[index].classList.remove('opacity-0','pointer-events-none');
            pictures[index].classList.add('opacity-100','pointer-events-auto');
            thumbs[index]?.classList.remove('border-transparent');
            thumbs[index]?.classList.add('border-blue-500','ring-2','ring-blue-300','dark:ring-blue-600');
        };

        prevBtn?.addEventListener('click', () => {
            show(index === 0 ? pictures.length - 1 : index - 1);
        });

        nextBtn?.addEventListener('click', () => {
            show(index === pictures.length - 1 ? 0 : index + 1);
        });

        thumbs.forEach((t, i) => {
            t.addEventListener('click', () => show(i));
        });

        let autoplayId;
        const startAutoplay = () => {
            if (pictures.length < 2) return;
            autoplayId = setInterval(() => {
                show((index + 1) % pictures.length);
            }, 5000);
        };
        const stopAutoplay = () => clearInterval(autoplayId);

        gallery.addEventListener('mouseenter', stopAutoplay);
        gallery.addEventListener('mouseleave', startAutoplay);

        startAutoplay();
    });
}

document.addEventListener('DOMContentLoaded', initGalleries);
