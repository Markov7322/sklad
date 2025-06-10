function initGalleries() {
    document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
        var index    = 0;
        var pictures = gallery.querySelectorAll('picture');
        var thumbs   = gallery.querySelectorAll('img[data-index]');
        var prevBtn  = gallery.querySelector('[data-prev]');
        var nextBtn  = gallery.querySelector('[data-next]');

        function show(i) {
            pictures[index].classList.add('opacity-0', 'pointer-events-none');
            pictures[index].classList.remove('opacity-100', 'pointer-events-auto');
            if (thumbs[index]) {
                thumbs[index].classList.remove('border-blue-500', 'ring-2', 'ring-blue-300', 'dark:ring-blue-600');
                thumbs[index].classList.add('border-transparent');
            }
            index = i;
            pictures[index].classList.remove('opacity-0', 'pointer-events-none');
            pictures[index].classList.add('opacity-100', 'pointer-events-auto');
            if (thumbs[index]) {
                thumbs[index].classList.remove('border-transparent');
                thumbs[index].classList.add('border-blue-500', 'ring-2', 'ring-blue-300', 'dark:ring-blue-600');
            }
        };

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                show(index === 0 ? pictures.length - 1 : index - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                show(index === pictures.length - 1 ? 0 : index + 1);
            });
        }

        Array.prototype.forEach.call(thumbs, function (t, i) {
            t.addEventListener('click', function () { show(i); });
        });

    });
}
document.addEventListener('DOMContentLoaded', initGalleries);
