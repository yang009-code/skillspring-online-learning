# Updating Website Content

## Course records

The first 20 records are in `sql/database.sql`. After the website is installed, the administrator pages can add or edit a course without editing the SQL file.

## Course images

The course covers are in `images/courses`. Use an image that has already been resized for the website. Update the `image` value in the course record when a different file name is used. Keep a useful `alt` description in the PHP page.

## Videos

The three preview files are in `media`:

- `preview1.mp4`
- `preview2.mp4`
- `preview3.mp4`

A replacement video can use the same file name, so the PHP page does not need to be changed.

## Templates

`css/style.css` contains the shared layout. The three template files are:

- `css/theme-light.css`
- `css/theme-dark.css`
- `css/theme-blue.css`

The administrator changes the active template on `admin/themes.php`. The choice is stored in `site_settings` and also copied to a cookie so the static pages can use it.

## Page text

Public page text can be edited in the matching PHP or HTML file. Shared navigation and footer links are in `includes/header.php` and `includes/footer.php`.
