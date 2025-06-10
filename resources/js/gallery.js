function initGalleries() {
    document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
        var mainImage = gallery.querySelector('#mainImage');
        if (!mainImage) return;
        var mobileSource = mainImage.parentElement.querySelector('#mainImageSource');

        var thumbs  = gallery.querySelectorAll('.thumb');
        var prevBtn = gallery.querySelector('[data-prev]');
        var nextBtn = gallery.querySelector('[data-next]');
        var index   = 0;

        function show(i) {
            if (!thumbs[i]) return;
            thumbs[index].classList.remove('border-blue-500', 'ring-2', 'ring-blue-300', 'dark:ring-blue-600');
            thumbs[index].classList.add('border-transparent');
            index = i;
            mainImage.src = thumbs[index].dataset.src;
            if (mobileSource && thumbs[index].dataset.mobileSrc) {
                mobileSource.srcset = thumbs[index].dataset.mobileSrc;
            }
            if (thumbs[index].dataset.alt) mainImage.alt = thumbs[index].dataset.alt;
            thumbs[index].classList.remove('border-transparent');
            thumbs[index].classList.add('border-blue-500', 'ring-2', 'ring-blue-300', 'dark:ring-blue-600');
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                show(index === 0 ? thumbs.length - 1 : index - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                show(index === thumbs.length - 1 ? 0 : index + 1);
            });
        }

        Array.prototype.forEach.call(thumbs, function (t, i) {
            t.addEventListener('click', function () { show(i); });
        });
    });
}
document.addEventListener('DOMContentLoaded', initGalleries);
