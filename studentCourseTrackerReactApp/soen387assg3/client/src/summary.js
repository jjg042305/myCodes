import axios from "axios";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

function Summary() {
  const navigate = useNavigate();
  const [students, setStudents] = useState([]);
  const [courses, setCourses] = useState([]);
  const [courseResults, setCourseResults] = useState([]);
  const [studentResults, setStudentResults] = useState([]);

  const [selectedCourse, setSelectedCourse] = useState("");
  const [selectedStudent, setSelectedStudent] = useState("");

  useEffect(() => {
    axios.get("http://localhost:5001/students").then(res => setStudents(res.data));
    axios.get("http://localhost:5001/courses").then(res => setCourses(res.data));
  }, []);

  const fetchCourseGrades = () => {
    axios.get(`http://localhost:5001/courseGrades/${selectedCourse}`)
      .then(res => setCourseResults(res.data))
      .catch(err => alert("Error fetching course grades"));
  };

  const fetchStudentCourses = () => {
    axios.get(`http://localhost:5001/studentCourses/${selectedStudent}`)
      .then(res => setStudentResults(res.data))
      .catch(err => alert("Error fetching student courses"));
  };

  return (
    <div className="summaryPage">
      <h1>Information Summary</h1>

      
      <div className="summarySection">
        <h3>View All Grades of a Course</h3>
        <select value={selectedCourse} onChange={(e) => setSelectedCourse(e.target.value)}>
          <option value="">-- Select Course --</option>
          {courses.map(c => (
            <option key={c.CourseID} value={c.CourseID}>
              {c.CourseID} — {c.CourseName}
            </option>
          ))}
        </select>
        <button onClick={fetchCourseGrades}>View Grades</button>

        {courseResults.length > 0 && (
          <ul className="listResults">
            {courseResults.map(r => (
              <li key={`${r.StudentID}-${r.LetterGrade}`}>
                {r.StudentID} — {r.FirstName} {r.LastName} | Grade: {r.LetterGrade ?? "No grade yet"}
              </li>
            ))}
          </ul>
        )}
      </div>

     
      <div className="summarySection">
        <h3>View All Courses of a Student</h3>
        <select value={selectedStudent} onChange={(e) => setSelectedStudent(e.target.value)}>
          <option value="">-- Select Student --</option>
          {students.map(s => (
            <option key={s.StudentID} value={s.StudentID}>
              {s.StudentID} — {s.FirstName} {s.LastName}
            </option>
          ))}
        </select>
        <button onClick={fetchStudentCourses}>View Courses</button>

        {studentResults.length > 0 && (
          <ul className="listResults">
            {studentResults.map(r => (
              <li key={`${r.CourseID}-${r.LetterGrade}`}>
                {r.CourseID} — {r.CourseName} | Grade: {r.LetterGrade ?? "No grade yet"}
              </li>
            ))}
          </ul>
        )}
      </div>

      <button className="backBtn" onClick={() => navigate("/")}>Back to Home</button>
    </div>
  );
}

export default Summary;
