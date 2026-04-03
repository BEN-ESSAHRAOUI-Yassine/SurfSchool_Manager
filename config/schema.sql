CREATE DATABASE IF NOT EXISTS surf_school;
USE surf_school;

CREATE TABLE USER (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','coach','student') NOT NULL,
    status ENUM('Enabled','Disabled') DEFAULT 'Enabled' NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
);

CREATE TABLE STUDENT (
    id_student INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    country VARCHAR(100) NOT NULL,
    level ENUM('Beginner','Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
    phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES USER(id_user) 
);

CREATE TABLE LESSON (
    id_lesson INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    lesson_date DATETIME NOT NULL,
    lesson_level ENUM('Beginner','Intermediate','Advanced') NOT NULL,
    coach_id INT NOT NULL,
    price DECIMAL(5,2) NOT NULL,
    capacity INT NOT NULL DEFAULT 5,
    FOREIGN KEY (coach_id) REFERENCES USER(id_user)
);

CREATE TABLE ENROLLMENT (
    id_enrollment INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    lesson_id INT NOT NULL,
    payment_status ENUM('Paid','Pending') NOT NULL  DEFAULT 'Pending',
    FOREIGN KEY (student_id) REFERENCES STUDENT(id_student),
    FOREIGN KEY (lesson_id) REFERENCES LESSON(id_lesson),
    UNIQUE (student_id, lesson_id)
);

-- Index sur FK pour optimiser les jointures
CREATE INDEX idx_student_user ON STUDENT(user_id);
CREATE INDEX idx_lesson_coach ON LESSON(coach_id);
CREATE INDEX idx_enrollment_student ON ENROLLMENT(student_id);
CREATE INDEX idx_enrollment_lesson ON ENROLLMENT(lesson_id);

-- ----------------------------
-- Users
-- ----------------------------
INSERT INTO USER (name, email, password, role) VALUES
('Admin User', 'admin@surf.com', '$2y$10$Nk2jaWseOPC9G6er/oh6MudzvMMdtLDlV.pkW/jIHZJAKXcSEQT.e', 'admin'), /*adminpass*/
('Coach Alice', 'alice@surf.com', '$2y$10$6cuA8G/O5j3dRCYhNNzMeeyk9R6mpUFhKSDspRZMG7OkiWnhfVLmO', 'coach'),/*coachpass*/
('Coach Bob', 'bob@surf.com', '$2y$10$6cuA8G/O5j3dRCYhNNzMeeyk9R6mpUFhKSDspRZMG7OkiWnhfVLmO', 'coach'),
('Coach Carol', 'carol@surf.com', '$2y$10$6cuA8G/O5j3dRCYhNNzMeeyk9R6mpUFhKSDspRZMG7OkiWnhfVLmO', 'coach'),
('Coach Dave', 'dave@surf.com', '$2y$10$6cuA8G/O5j3dRCYhNNzMeeyk9R6mpUFhKSDspRZMG7OkiWnhfVLmO', 'coach'),
('Student 1', 'student1@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),/*studentpass*/
('Student 2', 'student2@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 3', 'student3@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 4', 'student4@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 5', 'student5@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 6', 'student6@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 7', 'student7@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 8', 'student8@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 9', 'student9@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 10', 'student10@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 11', 'student11@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 12', 'student12@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 13', 'student13@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 14', 'student14@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student'),
('Student 15', 'student15@surf.com', '$2y$10$3Rf1dQqlAb3Vyn3wYiHrz.wvWvTTsvk0qW6L1gTz598sTpO7p2xgC', 'student');

-- ----------------------------
-- Students (link to users)
-- ----------------------------
INSERT INTO STUDENT (user_id, country, level, phone) VALUES
(6, 'France', 'Beginner', '0600000001'),
(7, 'USA', 'Beginner', '0600000002'),
(8, 'UK', 'Beginner', '0600000003'),
(9, 'Spain', 'Intermediate', '0600000004'),
(10, 'Portugal', 'Intermediate', '0600000005'),
(11, 'Italy', 'Intermediate', '0600000006'),
(12, 'Australia', 'Advanced', '0600000007'),
(13, 'Brazil', 'Advanced', '0600000008'),
(14, 'France', 'Beginner', '0600000009'),
(15, 'USA', 'Intermediate', '0600000010'),
(16, 'UK', 'Advanced', '0600000011'),
(17, 'Spain', 'Beginner', '0600000012'),
(18, 'Portugal', 'Intermediate', '0600000013'),
(19, 'Italy', 'Advanced', '0600000014'),
(20, 'Australia', 'Beginner', '0600000015');

-- ----------------------------
-- Lessons
-- ----------------------------
INSERT INTO LESSON (title, lesson_date, lesson_level, coach_id, price,capacity) VALUES
('First Splash & Safety', '2026-04-01 09:00:00', 'Beginner', 2, 30.00 , 5),
('The Pop-Up Master', '2026-04-02 09:00:00', 'Beginner', 2, 30.00 , 5),
('Ride the Whitewater', '2026-04-03 09:00:00', 'Beginner', 3, 30.00 , 5),
('Paddle Power', '2026-04-04 09:00:00', 'Beginner', 3, 30.00 , 5),
('Out to the Line-up', '2026-04-01 11:00:00', 'Intermediate', 4, 40.00 , 5),
('Green Wave Timing', '2026-04-02 11:00:00', 'Intermediate', 4, 40.00 , 5),
('The Bottom Turn', '2026-04-03 11:00:00', 'Intermediate', 5, 40.00 , 5),
('Trim & Speed', '2026-04-04 11:00:00', 'Intermediate', 5, 40.00 , 5),
('Positioning & Priority', '2026-04-05 11:00:00', 'Intermediate', 5, 40.00 , 5),
('Spot Analysis', '2026-04-01 14:00:00', 'Advanced', 2, 50.00 , 5),
('Vertical Surfing', '2026-04-02 14:00:00', 'Advanced', 2, 50.00 , 5),
('Advanced Maneuvers', '2026-04-04 14:00:00', 'Advanced', 3, 50.00 , 5),
('Video Coaching Analysis', '2026-04-05 14:00:00', 'Advanced', 4, 50.00 , 5);

-- ----------------------------
-- Enrollments (random simulation)
-- ----------------------------
-- Enrollment (link students to lessons)
-- ----------------------------
INSERT INTO ENROLLMENT (student_id, lesson_id, payment_status) VALUES
-- Beginner students for Beginner lessons
(1, 1, 'Paid'),
(2, 1, 'Paid'),
(3, 1, 'Pending'),
(4, 2, 'Paid'),
(5, 2, 'Pending'),
(6, 3, 'Paid'),
(7, 3, 'Paid'),
(8, 4, 'Pending'),
(9, 4, 'Paid'),
(10, 2, 'Paid'),

-- Intermediate students for Intermediate lessons
(2, 5, 'Pending'),
(5, 5, 'Paid'),
(6, 6, 'Paid'),
(10, 6, 'Pending'),
(11, 7, 'Paid'),
(12, 7, 'Paid'),
(13, 8, 'Pending'),
(14, 8, 'Paid'),
(15, 9, 'Pending'),

-- Advanced students for Advanced lessons
(12, 10, 'Paid'),
(13, 10, 'Pending'),
(14, 11, 'Paid'),
(15, 11, 'Paid'),
(6, 12, 'Pending'),
(7, 12, 'Paid'),
(8, 13, 'Paid'),
(9, 13, 'Pending');
