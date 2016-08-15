CREATE TABLE Students (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(64) NOT NULL,
    last_name VARCHAR(64) NOT NULL,
	group_number VARCHAR(16),
    birthday_date DATE, 
    email VARCHAR(64),
	ip VARCHAR(64),
	registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    average_mark DECIMAL(5,2),
    PRIMARY KEY (id), 
    INDEX(group_number,average_mark),
	INDEX(average_mark), 	
	INDEX(group_number),
	UNIQUE(first_name, last_name, birthday_date)

)
ENGINE INNODB;


CREATE TABLE Qualifications (
	id INT NOT NULL AUTO_INCREMENT,
	id_student INT NOT NULL UNIQUE,
	description VARCHAR(1024),
	PRIMARY KEY (id),
	FOREIGN KEY (id_student) REFERENCES Students(id)	
) 
ENGINE INNODB;


CREATE TABLE Subjects (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL UNIQUE,
    PRIMARY KEY (id)
)
ENGINE INNODB;


CREATE TABLE Marks (
	id INT NOT NULL AUTO_INCREMENT,
	id_student INT NOT NULL, 
	id_subject INT NOT NULL,
	mark DECIMAL(5,2) NOT NULL, 
	semester_number DECIMAL(3,0) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (id_student, id_subject, semester_number),
	FOREIGN KEY (id_student) REFERENCES Students(id),
	FOREIGN KEY (id_subject) REFERENCES Subjects(id)	
) 
ENGINE INNODB;


DELIMITER $$
CREATE TRIGGER `InsertMark` AFTER INSERT ON `marks`
    FOR EACH ROW BEGIN
        SET @id_student = NEW.`id_student`;
        SET @total_marks = (SELECT COUNT(*) FROM `marks` WHERE `id_student` = @id_student);
        SET @average_mark = (SELECT SUM(`mark`) / @total_marks FROM `marks` WHERE `id_student` = @id_student);

        UPDATE `students` SET `average_mark` = @average_mark WHERE `id` = @id_student;
    END;
$$