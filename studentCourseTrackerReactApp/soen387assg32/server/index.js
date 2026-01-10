const express = require('express');
const cors = require('cors');
const app = express()
const mysql = require('mysql2')


app.use(cors())
app.use(express.json())

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'Lego2015',
    database: 'StudentTracker'
});

connection.connect((err) => {
  if (err) throw err;
  console.log("MySQL Connected");
});



app.get("/students", (req, res) => {
  connection.query("SELECT * FROM Students", (err, result) => {
    if (err) throw err;
    res.json(result);
  });
});

app.get("/courses", (req, res) => {
  connection.query("SELECT * FROM Courses", (err, result) => {
    if (err) throw err;
    res.json(result);
  });
});

 app.post("/enroll", (req,res)=> {
const studentID = req.body.studentID;
const courseID = req.body.courseID;

 if (!studentID || !courseID) {
    return res.status(400).send("Student ID and Course ID are required.");
  }


  connection.query(
    `SELECT StudentID FROM Students WHERE Students.StudentID = ${studentID}`,
    (err, sidResult) => {
        console.log(`sid result is ${sidResult}`)
      if (err) throw err;

      if (sidResult.length === 0) {
        return res.status(400).send("Student ID not present in database");
      }

      connection.query(
        `SELECT CourseID FROM Courses WHERE Courses.CourseID = ${courseID}`,
        (err, cidResult) => {
            console.log(`cid result is ${cidResult}`)

          if (err) throw err;

          if (cidResult.length === 0) {
            return res.status(400).send("Course ID not present in database");
          }

          return res.status(200).send();
        }
      );
    }
  );

 });


 app.post("/newEnrollment", (req,res)=> {
const studentID = req.body.studentID;
const courseID = req.body.courseID;
connection.query(`INSERT INTO IsEnrolled (CourseID,StudentID, LetterGrade) VALUES (${courseID}, ${studentID}, NULL)`,
  (err,dbResult) => {
    if(err) {
    if (err.code === 'ER_DUP_ENTRY') {
      return res.status(400).send("This student is already enrolled in this course")

    }
  }

   return res.status(200).send("Student enrolled successfully.");

  }
);



 });



 app.get("/showEnrollments", (req,res) => {
connection.query(`SELECT CourseID, StudentID, LetterGrade  FROM isEnrolled`, 
  (err, enrollmResult) => {
   if(err) throw err;
    res.json(enrollmResult)
  }
)
})


app.post("/inputGrade", (req,res)=> {
const sid = req.body.studentID;
const cid = req.body.courseID;
let grade = req.body.grade;
grade = grade.trim();
grade = grade.toUpperCase();
const valid_grades = ['A+', 'A', 'A-','B+','B','B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'F']
if (!valid_grades.includes(grade)){
return res.status(400).send("Invalid grade format. Valid formats are : 'A+', 'A', 'A-','B+','B','B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'F'")

}

connection.query(`UPDATE IsEnrolled SET LetterGrade = '${grade}' WHERE (StudentID = ${sid} AND CourseID = ${cid})`,(err, gradeResult) => {
if (err){
  console.log(err);
throw err;
} 
return res.status(200).send("Grade updated successfully!");


})

})




app.get("/courseGrades/:courseID", (req,res) => {
  const cid = req.params.courseID;

  connection.query(
    `SELECT IsEnrolled.StudentID, IsEnrolled.LetterGrade, Students.FirstName, Students.LastName 
     FROM IsEnrolled 
     JOIN Students ON IsEnrolled.StudentID = Students.StudentID
     WHERE IsEnrolled.CourseID = ${cid}`,
    (err, result) => {
      if (err) throw err;
      res.json(result);
    }
  );
});



app.get("/studentCourses/:studentID", (req,res) => {
  const sid = req.params.studentID;

  connection.query(
    `SELECT IsEnrolled.CourseID, IsEnrolled.LetterGrade, Courses.CourseName
     FROM IsEnrolled 
     JOIN Courses ON IsEnrolled.CourseID = Courses.CourseID
     WHERE IsEnrolled.StudentID = ${sid}`,
    (err, result) => {
      if (err) throw err;
      res.json(result);
    }
  );
});


 app.listen(5001,  () => {
  console.log("Server running on http://localhost:5001");
});




