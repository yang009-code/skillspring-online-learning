# Installation Guide

1. Upload the `skillspring` folder to the domain's `public_html` folder.
2. Check that `public_html/skillspring/index.php` exists. There should not be a second `skillspring` folder inside it.
3. In DirectAdmin, create a new MySQL database and a database user.
4. Open phpMyAdmin and select the new database.
5. Import `sql/database.sql`.
6. Open `includes/config.php` on the server.
7. Enter the full database name, full database user name, and database password from DirectAdmin.
8. Keep `DB_HOST` as `localhost` unless the host gives a different value.
9. Keep `BASE_URL` as `/skillspring` when the folder name is `skillspring`.
10. Open the live website and test the catalogue.
11. Test registration, login, cart, checkout, My Courses, progress, and reviews.
12. Log in as the administrator and test all administrator pages.
13. Change the sample passwords before submission.

The real `includes/config.php` should not be placed in a public GitHub repository because it contains the database password. The repository can use `includes/config.example.php` instead.
