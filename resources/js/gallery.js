function initGalleries() {
    document.querySelectorAll('[data-gallery]').forEach(function (gallery) {
        var mainImage = gallery.querySelector('#mainImage');
        if (!mainImage) return;
        var mobileSource = mainImage.parentElement.querySelector('#mainImageSource');

        var thumbs  = gallery.querySelectorAll('.thumb');
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



        Array.prototype.forEach.call(thumbs, function (t, i) {
            t.addEventListener('click', function () { show(i); });
        });

        if (thumbs.length > 1) {
            var startX = null;
            mainImage.addEventListener('click', function () {
                show(index === thumbs.length - 1 ? 0 : index + 1);
            });
            mainImage.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
            }, { passive: true });
            mainImage.addEventListener('touchend', function (e) {
                if (startX === null) return;
                var diff = e.changedTouches[0].clientX - startX;
                if (Math.abs(diff) > 30) {
                    if (diff > 0) {
                        show(index === 0 ? thumbs.length - 1 : index - 1);
                    } else {
                        show(index === thumbs.length - 1 ? 0 : index + 1);
                    }
                }
                startX = null;
            });
        }
    });
}
document.addEventListener('DOMContentLoaded', initGalleries);
