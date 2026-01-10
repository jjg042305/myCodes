const mysql = require('mysql2');
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'mypassword'

});

connection.connect((err)=> {
    if (err) throw err;
    console.log('Connected!')
});

connection.query(`CREATE TABLE Students (
    StudentID int,
    FirstName varchar(255),
    LastName varchar(255),
    PRIMARY KEY (StudentID)
    );
    
    CREATE TABLE Courses (
    CourseID int,
    CourseName varchar(255),
    PRIMARY KEY (CourseID)
    
    );
    
    CREATE TABLE IsEnrolled (
    CourseID int,
    StudentID int,
    LetterGrade varchar(1)
    PRIMARY KEY (StudentID, CourseID));`, (err)=> {
        if (err) throw err;
    }
)
