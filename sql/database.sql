-- SkillSpring Online Learning database
-- Select the assigned DirectAdmin database before importing this file.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Remove old project tables when the database is rebuilt.
DROP TABLE IF EXISTS progress;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS lessons;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS course_options;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS site_settings;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- Registered users and administrator accounts.
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    status ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Main online course catalogue.
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(150) NOT NULL,
    category VARCHAR(80) NOT NULL,
    instructor VARCHAR(100) NOT NULL,
    base_price DECIMAL(8,2) NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    short_description VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (course_name),
    INDEX (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Access and support choices for each course.
CREATE TABLE course_options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    option_type ENUM('access', 'support') NOT NULL,
    option_name VARCHAR(80) NOT NULL,
    extra_price DECIMAL(8,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One order is created after the demonstration checkout.
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(120) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending', 'Completed', 'Cancelled', 'Refunded')
        NOT NULL DEFAULT 'Completed',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Courses and selected choices belonging to an order.
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    course_id INT NOT NULL,
    access_plan VARCHAR(80) NOT NULL,
    support_plan VARCHAR(80) NOT NULL,
    unit_price DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Course access created after a successful checkout.
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    order_id INT NOT NULL,
    access_plan VARCHAR(80) NOT NULL,
    enrollment_date DATE NOT NULL,
    expiry_date DATE DEFAULT NULL,
    UNIQUE KEY unique_user_course (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Lesson title, order, description, and local video file.
CREATE TABLE lessons (
    lesson_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lesson_title VARCHAR(150) NOT NULL,
    lesson_description TEXT NOT NULL,
    video_file VARCHAR(255) NOT NULL,
    lesson_order INT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Completed lessons for each user.
CREATE TABLE progress (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    completed TINYINT(1) NOT NULL DEFAULT 0,
    completed_at DATETIME DEFAULT NULL,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- One rating and comment per user and course.
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_review (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact messages and administrator replies.
CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    admin_response TEXT DEFAULT NULL,
    status ENUM('New', 'Answered') NOT NULL DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- A small table for the current site-wide template.
CREATE TABLE site_settings (
    setting_name VARCHAR(50) PRIMARY KEY,
    setting_value VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample user accounts.
INSERT INTO users(full_name,email,username,password,role,status) VALUES('SkillSpring Administrator','admin@skillspring.test','admin','$2y$12$.8/DOWFZmpHfSxH2qW8doe2coH//2oUNZO/UCZQ8/7OhITBH4.EUy','admin','active'),('Demo Student','student@skillspring.test','student','$2y$12$Kii4GIu.7mqTAu.Kp8lbCOGSm/0.Nncpc8CJKd43GxYeCY9Wu03Cy','user','active');

-- Default website template.
INSERT INTO site_settings VALUES('active_theme','light');

-- Twenty course records used by the catalogue.
INSERT INTO courses(course_name,category,instructor,base_price,difficulty,short_description,description,image,status) VALUES
('HTML Fundamentals','Web Development','Jordan Lee',24.99,'Beginner','Learn semantic HTML5, page structure, headings, links, lists, images, and accessible markup.','Learn semantic HTML5, page structure, headings, links, lists, images, and accessible markup. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course01.png','active'),
('CSS Fundamentals','Web Development','Jordan Lee',27.99,'Beginner','Style web pages with selectors, the box model, typography, colour, spacing, and reusable classes.','Style web pages with selectors, the box model, typography, colour, spacing, and reusable classes. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course02.png','active'),
('Responsive Web Design','Web Development','Maya Chen',34.99,'Intermediate','Build layouts that adapt to desktop and mobile screens with Flexbox, Grid, and media queries.','Build layouts that adapt to desktop and mobile screens with Flexbox, Grid, and media queries. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course03.png','active'),
('JavaScript Basics','Programming','Noah Patel',39.99,'Beginner','Practice variables, conditions, loops, arrays, functions, and basic browser-side programming.','Practice variables, conditions, loops, arrays, functions, and basic browser-side programming. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course04.png','active'),
('JavaScript DOM','Programming','Noah Patel',42.99,'Intermediate','Use the Document Object Model, events, and forms to create interactive web pages.','Use the Document Object Model, events, and forms to create interactive web pages. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course05.png','active'),
('PHP Fundamentals','Server-Side','Olivia Smith',44.99,'Beginner','Create dynamic pages with PHP variables, arrays, functions, forms, and includes.','Create dynamic pages with PHP variables, arrays, functions, forms, and includes. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course06.png','active'),
('PHP Forms','Server-Side','Olivia Smith',46.99,'Intermediate','Process GET and POST form data, validate user input, and display helpful messages.','Process GET and POST form data, validate user input, and display helpful messages. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course07.png','active'),
('MySQL Fundamentals','Database','Ethan Wang',41.99,'Beginner','Learn tables, primary keys, SQL queries, filtering, sorting, and data updates.','Learn tables, primary keys, SQL queries, filtering, sorting, and data updates. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course08.png','active'),
('PHP and MySQL','Database','Ethan Wang',54.99,'Intermediate','Connect PHP pages to MySQL with PDO and prepared statements.','Connect PHP pages to MySQL with PDO and prepared statements. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course09.png','active'),
('C Programming Basics','Programming','Ava Brown',37.99,'Beginner','Learn variables, selection, loops, arrays, functions, strings, and pointers.','Learn variables, selection, loops, arrays, functions, strings, and pointers. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course10.png','active'),
('Python Basics','Programming','Liam Martin',36.99,'Beginner','Explore Python syntax, decisions, loops, collections, and functions.','Explore Python syntax, decisions, loops, collections, and functions. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course11.png','active'),
('Java Basics','Programming','Sophia Kim',38.99,'Beginner','Study Java variables, control structures, methods, and classes.','Study Java variables, control structures, methods, and classes. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course12.png','active'),
('Excel Fundamentals','Productivity','Lucas Green',29.99,'Beginner','Organize worksheets, use formulas, format data, and create charts.','Organize worksheets, use formulas, format data, and create charts. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course13.png','active'),
('Data Visualization','Productivity','Lucas Green',43.99,'Intermediate','Turn data into clear tables, charts, and visual explanations.','Turn data into clear tables, charts, and visual explanations. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course14.png','active'),
('Academic Writing','Career Skills','Emily Davis',31.99,'Beginner','Plan paragraphs, develop arguments, revise drafts, and use academic style.','Plan paragraphs, develop arguments, revise drafts, and use academic style. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course15.png','active'),
('Presentation Skills','Career Skills','Emily Davis',32.99,'Beginner','Create organized slides, speak clearly, and communicate with confidence.','Create organized slides, speak clearly, and communicate with confidence. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course16.png','active'),
('Resume Writing','Career Skills','Daniel Wilson',28.99,'Beginner','Create a focused resume with effective sections and action verbs.','Create a focused resume with effective sections and action verbs. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course17.png','active'),
('Interview Preparation','Career Skills','Daniel Wilson',35.99,'Intermediate','Prepare interview answers and practice professional communication.','Prepare interview answers and practice professional communication. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course18.png','active'),
('Time Management','Student Success','Grace Taylor',25.99,'Beginner','Use weekly planning, priorities, and realistic study schedules.','Use weekly planning, priorities, and realistic study schedules. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course19.png','active'),
('Study Skills','Student Success','Grace Taylor',26.99,'Beginner','Improve note-taking, reading, review routines, and exam preparation.','Improve note-taking, reading, review routines, and exam preparation. This course includes two demonstration lessons and selectable access and support plans.','images/courses/course20.png','active');

-- Every course receives three access options and three support options.
INSERT INTO course_options(course_id,option_type,option_name,extra_price) VALUES
(1,'access','30-Day Access',0.00),
(1,'access','90-Day Access',15.00),
(1,'access','Lifetime Access',35.00),
(1,'support','Self-Study',0.00),
(1,'support','Email Support',10.00),
(1,'support','Instructor Support',25.00),
(2,'access','30-Day Access',0.00),
(2,'access','90-Day Access',15.00),
(2,'access','Lifetime Access',35.00),
(2,'support','Self-Study',0.00),
(2,'support','Email Support',10.00),
(2,'support','Instructor Support',25.00),
(3,'access','30-Day Access',0.00),
(3,'access','90-Day Access',15.00),
(3,'access','Lifetime Access',35.00),
(3,'support','Self-Study',0.00),
(3,'support','Email Support',10.00),
(3,'support','Instructor Support',25.00),
(4,'access','30-Day Access',0.00),
(4,'access','90-Day Access',15.00),
(4,'access','Lifetime Access',35.00),
(4,'support','Self-Study',0.00),
(4,'support','Email Support',10.00),
(4,'support','Instructor Support',25.00),
(5,'access','30-Day Access',0.00),
(5,'access','90-Day Access',15.00),
(5,'access','Lifetime Access',35.00),
(5,'support','Self-Study',0.00),
(5,'support','Email Support',10.00),
(5,'support','Instructor Support',25.00),
(6,'access','30-Day Access',0.00),
(6,'access','90-Day Access',15.00),
(6,'access','Lifetime Access',35.00),
(6,'support','Self-Study',0.00),
(6,'support','Email Support',10.00),
(6,'support','Instructor Support',25.00),
(7,'access','30-Day Access',0.00),
(7,'access','90-Day Access',15.00),
(7,'access','Lifetime Access',35.00),
(7,'support','Self-Study',0.00),
(7,'support','Email Support',10.00),
(7,'support','Instructor Support',25.00),
(8,'access','30-Day Access',0.00),
(8,'access','90-Day Access',15.00),
(8,'access','Lifetime Access',35.00),
(8,'support','Self-Study',0.00),
(8,'support','Email Support',10.00),
(8,'support','Instructor Support',25.00),
(9,'access','30-Day Access',0.00),
(9,'access','90-Day Access',15.00),
(9,'access','Lifetime Access',35.00),
(9,'support','Self-Study',0.00),
(9,'support','Email Support',10.00),
(9,'support','Instructor Support',25.00),
(10,'access','30-Day Access',0.00),
(10,'access','90-Day Access',15.00),
(10,'access','Lifetime Access',35.00),
(10,'support','Self-Study',0.00),
(10,'support','Email Support',10.00),
(10,'support','Instructor Support',25.00),
(11,'access','30-Day Access',0.00),
(11,'access','90-Day Access',15.00),
(11,'access','Lifetime Access',35.00),
(11,'support','Self-Study',0.00),
(11,'support','Email Support',10.00),
(11,'support','Instructor Support',25.00),
(12,'access','30-Day Access',0.00),
(12,'access','90-Day Access',15.00),
(12,'access','Lifetime Access',35.00),
(12,'support','Self-Study',0.00),
(12,'support','Email Support',10.00),
(12,'support','Instructor Support',25.00),
(13,'access','30-Day Access',0.00),
(13,'access','90-Day Access',15.00),
(13,'access','Lifetime Access',35.00),
(13,'support','Self-Study',0.00),
(13,'support','Email Support',10.00),
(13,'support','Instructor Support',25.00),
(14,'access','30-Day Access',0.00),
(14,'access','90-Day Access',15.00),
(14,'access','Lifetime Access',35.00),
(14,'support','Self-Study',0.00),
(14,'support','Email Support',10.00),
(14,'support','Instructor Support',25.00),
(15,'access','30-Day Access',0.00),
(15,'access','90-Day Access',15.00),
(15,'access','Lifetime Access',35.00),
(15,'support','Self-Study',0.00),
(15,'support','Email Support',10.00),
(15,'support','Instructor Support',25.00),
(16,'access','30-Day Access',0.00),
(16,'access','90-Day Access',15.00),
(16,'access','Lifetime Access',35.00),
(16,'support','Self-Study',0.00),
(16,'support','Email Support',10.00),
(16,'support','Instructor Support',25.00),
(17,'access','30-Day Access',0.00),
(17,'access','90-Day Access',15.00),
(17,'access','Lifetime Access',35.00),
(17,'support','Self-Study',0.00),
(17,'support','Email Support',10.00),
(17,'support','Instructor Support',25.00),
(18,'access','30-Day Access',0.00),
(18,'access','90-Day Access',15.00),
(18,'access','Lifetime Access',35.00),
(18,'support','Self-Study',0.00),
(18,'support','Email Support',10.00),
(18,'support','Instructor Support',25.00),
(19,'access','30-Day Access',0.00),
(19,'access','90-Day Access',15.00),
(19,'access','Lifetime Access',35.00),
(19,'support','Self-Study',0.00),
(19,'support','Email Support',10.00),
(19,'support','Instructor Support',25.00),
(20,'access','30-Day Access',0.00),
(20,'access','90-Day Access',15.00),
(20,'access','Lifetime Access',35.00),
(20,'support','Self-Study',0.00),
(20,'support','Email Support',10.00),
(20,'support','Instructor Support',25.00);

-- Two demonstration lessons are added for every course.
INSERT INTO lessons(course_id,lesson_title,lesson_description,video_file,lesson_order) VALUES
(1,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(1,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(2,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(2,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(3,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(3,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(4,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(4,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(5,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(5,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(6,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(6,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(7,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(7,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(8,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(8,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(9,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(9,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(10,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(10,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(11,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(11,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(12,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(12,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(13,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(13,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(14,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(14,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(15,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(15,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(16,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(16,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(17,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(17,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2),
(18,'Course Orientation','Review the goals and learning expectations.','media/preview3.mp4',1),
(18,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview1.mp4',2),
(19,'Course Orientation','Review the goals and learning expectations.','media/preview1.mp4',1),
(19,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview2.mp4',2),
(20,'Course Orientation','Review the goals and learning expectations.','media/preview2.mp4',1),
(20,'Practice and Reflection','Apply the introductory ideas and record progress.','media/preview3.mp4',2);

-- One sample contact message for the administrator page.
INSERT INTO messages(user_id,full_name,email,subject,message,status) VALUES(NULL,'Alex Morgan','alex@example.com','Access Plan','Can I change plans before checkout?','New');
