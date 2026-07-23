# Front-End Guide

The front end of SkillSpring was made with HTML5, CSS and JavaScript.
PHP is used to load common page sections and display data from MySQL.

## Page Structure

Most PHP pages use the same header and footer.

- `includes/header.php` contains the page head, logo and main menu.
- `includes/footer.php` contains the footer links and copyright text.
- `includes/bootstrap.php` loads the database connection and common functions.

Using shared files makes it easier to update the menu and footer.

## CSS Files

The main layout is stored in:

- `css/style.css`

This file contains:

- page spacing
- navigation styles
- course cards
- forms
- buttons
- tables
- Flexbox and Grid layouts
- responsive media queries
- hover effects and transitions

The website also has three templates:

- `css/theme-light.css`
- `css/theme-dark.css`
- `css/theme-blue.css`

The administrator can switch the active template from the Themes page.

## JavaScript Files

The main JavaScript file is:

- `js/main.js`

It is used for:

- opening and closing the mobile menu
- checking the registration form
- calculating the course price
- drawing the administrator report graph

The static information pages use:

- `js/static-theme.js`

This file loads the selected theme on static HTML pages.

## Responsive Design

The website works on desktop and mobile screens.

CSS media queries change:

- the number of course columns
- the navigation menu
- page spacing
- table scrolling
- image and video sizes

On smaller screens, the main menu is opened with the Menu button.

## Multimedia

Course images are stored in:

- `images/courses/`

The course preview videos are stored in:

- `media/`

The Learning Path page uses an HTML image map with three clickable areas.

## Updating the Front End

To change the main layout, edit `css/style.css`.

To change a template, edit the matching theme CSS file.

To change the navigation, edit `includes/header.php`.

To replace an image or video, upload the new file and keep the same filename,
or update the file path in the database.
