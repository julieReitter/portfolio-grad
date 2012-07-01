CREATE TABLE user(
user_id INT PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(15) NOT NULL,
password VARCHAR(20) NOT NULL
);

CREATE TABLE work (
work_id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(30) NOT NULL,
description VARCHAR(1000) NOT NULL,
thumbnail VARCHAR(20) NOT NULL,
goody BOOLEAN,
link varchar(200),
date DATE,
order_value INT,
);

CREATE TABLE skills (
skill_id INT PRIMARY KEY AUTO_INCREMENT,
skill_title VARCHAR(30) NOT NULL
);

CREATE TABLE work_skills (
work_id INT REFERENCES work(work_id),
skill_id INT REFERENCES skills(skill_id)
);

CREATE TABLE images (
image_id INT PRIMARY KEY AUTO_INCREMENT,
image_file VARCHAR(20) NOT NULL,
work_id INT REFERENCES work(work_id)
);


