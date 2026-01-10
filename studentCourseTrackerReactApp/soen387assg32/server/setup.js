const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'Lego2015',
     multipleStatements: true

});

connection.connect((err)=> {
    if (err) throw err;
    console.log('Connected!');


connection.query(`
  CREATE DATABASE IF NOT EXISTS StudentTracker;
`, (err) => {
  if (err) throw err;
  console.log("Database created or exists already.");
});

connection.query(`
  USE StudentTracker;

  CREATE TABLE IF NOT EXISTS Students (
    StudentID INT,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    PRIMARY KEY (StudentID)
  );

  CREATE TABLE IF NOT EXISTS Courses (
    CourseID INT,
    CourseName VARCHAR(255),
    PRIMARY KEY (CourseID)
  );

  CREATE TABLE IF NOT EXISTS IsEnrolled (
    CourseID INT,
    StudentID INT,
    LetterGrade VARCHAR(2),
    PRIMARY KEY (StudentID, CourseID)
  );

  INSERT INTO Students (StudentID, FirstName, LastName) VALUES
            (1, "Alex", "Brown"),
            (2, "Maya", "Singh"),
            (3, "Omar", "Demi"),
            (4, "Liam", "Smith"),
            (5, "Leo", "Khoury"),
            (6, "Boni", "Takka"),
            (7, "Sara", "Mansour"),
            (8, "Adam", "Ghosn"),
            (9, "Nora", "Walid"),
            (10, "David", "Roche"),
            (11, "Jenna", "Haddad"),
            (12, "Rami", "Ghattas"),
            (13, "Tala", "Salem"),
            (14, "Yara", "Shami"),
            (15, "Karim", "Akl"),
            (16, "George", "Haddad"),
            (17, "Farah", "Nassar"),
            (18, "Sam", "Peterson"),
            (19, "Isabelle", "Dion"),
            (20, "Jon", "Elias");

        INSERT INTO Courses (CourseID, CourseName) VALUES
            (101, "SOEN 387"),
            (102, "COMP 232"),
            (103, "COMP 348"),
            (104, "COMP 352"),
            (105, "SOEN 341"),
            (106, "SOEN 490"),
            (107, "COMP 444"),
            (108, "COMP 367"),
            (109, "COMP 472"),
            (110, "SOEN 342"),
            (111, "ENGR 213"),
            (112, "MATH 203"),
            (113, "MATH 204"),
            (114, "COMP 228"),
            (115, "SOEN 287");
`,
(err) => {
  if (err) throw err;
  console.log("Tables created and sample data inserted.");
  connection.end();
}) ;
});