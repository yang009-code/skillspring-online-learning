/*
 * File: main.js
 * Purpose: Small interactions used on the SkillSpring pages.
 */

document.addEventListener('DOMContentLoaded', function () {
    // Open and close the navigation menu on a small screen.
    var menuButton = document.getElementById('menuButton');
    var navigation = document.getElementById('mainNavigation');

    if (menuButton && navigation) {
        menuButton.addEventListener('click', function () {
            var isOpen = navigation.classList.toggle('open');
            menuButton.setAttribute('aria-expanded', String(isOpen));
        });
    }

    // Show an estimated price when a course option changes.
    var optionForm = document.getElementById('courseOptionForm');

    if (optionForm) {
        var basePrice = Number(optionForm.getAttribute('data-base-price'));
        var priceOutput = document.getElementById('calculatedPrice');

        function updatePrice() {
            var extraPrice = 0;
            var selectBoxes = optionForm.getElementsByTagName('select');
            var i;

            for (i = 0; i < selectBoxes.length; i++) {
                var selectedOption = selectBoxes[i].options[selectBoxes[i].selectedIndex];
                extraPrice = extraPrice + Number(selectedOption.getAttribute('data-extra-price'));
            }

            if (priceOutput) {
                priceOutput.textContent = '$' + (basePrice + extraPrice).toFixed(2);
            }
        }

        optionForm.addEventListener('change', updatePrice);
        updatePrice();
    }

    // Do a quick password check before the register form is sent.
    var registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            var password = document.getElementById('password');
            var confirmPassword = document.getElementById('confirm_password');

            if (password.value.length < 6) {
                event.preventDefault();
                alert('The password needs at least six characters.');
                password.focus();
            } else if (password.value !== confirmPassword.value) {
                event.preventDefault();
                alert('The two passwords do not match.');
                confirmPassword.focus();
            }
        });
    }

    // Draw the simple administrator report with the canvas element.
    var canvas = document.getElementById('salesChart');

    if (canvas) {
        var labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
        var values = JSON.parse(canvas.getAttribute('data-values') || '[]');
        var context = canvas.getContext('2d');
        var pageTextColor = window.getComputedStyle(document.body).color;
        var padding = 55;
        var maximum = 1;
        var a;

        for (a = 0; a < values.length; a++) {
            if (values[a] > maximum) {
                maximum = values[a];
            }
        }

        var numberOfBars = values.length;
        if (numberOfBars === 0) {
            numberOfBars = 1;
        }

        var barArea = canvas.width - padding * 2;
        var barWidth = barArea / numberOfBars;

        for (a = 0; a < values.length; a++) {
            var barHeight = values[a] / maximum * (canvas.height - padding * 2);
            var x = padding + a * barWidth + barWidth * 0.15;
            var y = canvas.height - padding - barHeight;
            var actualWidth = barWidth * 0.7;

            context.fillStyle = '#2b78b8';
            context.fillRect(x, y, actualWidth, barHeight);

            context.fillStyle = pageTextColor;
            context.textAlign = 'center';
            context.fillText(values[a], x + actualWidth / 2, y - 7);

            context.save();
            context.translate(x + actualWidth / 2, canvas.height - 15);
            context.rotate(-0.45);
            context.fillText(labels[a], 0, 0);
            context.restore();
        }
    }
});
