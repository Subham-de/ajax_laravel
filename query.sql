CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(255) NOT NULL
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    answer_type ENUM('radio', 'checkbox', 'textarea') NOT NULL,
    subject_id INT,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    option_text TEXT NOT NULL,
    question_id INT,
    correct_ans INT default 0,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correct_ans VARCHAR(255) NOT NULL,
    question_id INT,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'user') NOT NULL 
);


ALTER TABLE options
ADD COLUMN selected_ans TEXT;




-- CREATE TABLE marks (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     subject_id INT,
--     student_id INT,
--     marks_obtained INT,
-- );
