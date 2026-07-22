/*
 * File: static-theme.js
 * Purpose: Let the five static HTML pages use the last selected template.
 */

document.addEventListener('DOMContentLoaded', function () {
    var themeName = 'light';
    var cookieList = document.cookie.split(';');
    var i;

    for (i = 0; i < cookieList.length; i++) {
        var oneCookie = cookieList[i].trim();

        if (oneCookie.indexOf('site_theme=') === 0) {
            themeName = oneCookie.substring('site_theme='.length);
        }
    }

    if (themeName !== 'light' && themeName !== 'dark' && themeName !== 'blue') {
        themeName = 'light';
    }

    var themeLink = document.getElementById('themeStylesheet');

    if (themeLink) {
        themeLink.href = 'css/theme-' + themeName + '.css';
    }
});
