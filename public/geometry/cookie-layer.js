'use strict';

(function () {

    var cookiesList = document.cookie.split(';').map(function (c) {
        return c.trim().split('=')[0];
    });

    if (cookiesList.indexOf('voh_cookie') < 0) {
        var cookieLayer = document.createElement('div');
        cookieLayer.classList.add('cookie-layer');
        cookieLayer.innerHTML = '<div class="container">' + '<div class="cookie-layer__text">' +
            '<p>This website uses cookies to optimize the design of its website and make continuous improvements. By continuing to use the website, you consent to the use of cookies.</p>' +
            '<p>Find out more about our cookies and how we use them <a href="/cookie-policy/">here</a>.</p>' +
            '</div>' + '<button class="cookie-layer__button" type="button">I Agree</button>' + '</div>';

        cookieLayer.querySelector('button').addEventListener('click', function () {
            document.cookie = 'voh_cookie = true;';
            cookieLayer.addEventListener('transitionend', function () {
                document.body.removeChild(cookieLayer);
            });
            cookieLayer.classList.add('hidden');
        });

        cookieLayer.classList.add('hidden');

        var listener = function listener() {
            var fold = 0.2 * window.innerHeight
            if (cookieLayer.classList.contains('hidden') && window.scrollY > fold) {
                cookieLayer.classList.remove('hidden');
                window.removeEventListener('scroll', listener);
            }
        };
        window.addEventListener('scroll', listener);

        document.body.appendChild(cookieLayer);
    }
})();
