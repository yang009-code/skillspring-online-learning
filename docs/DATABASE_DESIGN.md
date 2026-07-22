# Database Design

The project uses a relational MySQL database. Each table has a primary key. Foreign keys connect records that belong together.

| Table | Main purpose | Primary key |
|---|---|---|
| `users` | Stores registered accounts, roles, and account status | `user_id` |
| `courses` | Stores the 20 course products | `course_id` |
| `course_options` | Stores access and support choices for a course | `option_id` |
| `orders` | Stores one record for each checkout | `order_id` |
| `order_items` | Stores the courses and choices inside an order | `order_item_id` |
| `enrollments` | Gives a user access to a purchased course | `enrollment_id` |
| `lessons` | Stores the lessons for each course | `lesson_id` |
| `progress` | Stores completed lessons for each user | `progress_id` |
| `reviews` | Stores course ratings and comments | `review_id` |
| `messages` | Stores contact questions and admin responses | `message_id` |
| `site_settings` | Stores the active website template | `setting_name` |

## Main relationships

- One user can have many orders.
- One order can have many order items.
- One course can have many course options.
- One course can have many lessons.
- A user can enroll in many courses.
- A course can have many enrolled users.
- The `enrollments` table connects users and courses.
- The `progress` table connects a user to a completed lesson.

## PHP connection

`includes/db.php` creates a PDO connection. The database values come from `includes/config.php`. The connection uses `utf8mb4` so regular text and more characters can be stored correctly.

Pages that use submitted values use PDO prepared statements. The SQL command is prepared first, and the values are sent separately. This is used in registration, login, search, checkout, reviews, messages, and administrator updates.

## CRUD work

- Create: registration, contact messages, orders, reviews, and Add Course.
- Read: course catalogue, search, profile, order history, My Courses, and all administrator lists.
- Update: profile, lesson progress, reviews, users, orders, messages, themes, and Edit Course.
- Delete or deactivate: cart items are removed from the session, and course records are changed to inactive so old order data remains available.
