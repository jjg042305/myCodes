import axios from 'axios';
import { useState } from "react";
import { useNavigate } from "react-router-dom";

function Grades() {
  const [enrollments, setEnrollments] = useState([]);
  const [showEnrollments, setShowEnrollments] = useState(false);
  const [grades, setGrades] = useState({});
 const navigate = useNavigate();


  const retrieveEnrollments = () => {
    axios
      .get("http://localhost:5001/showEnrollments")
      .then((res) => {
        setEnrollments(res.data);
        setShowEnrollments(true);
      })
      .catch((err) => {
        alert(err.response?.data || "Server error");
      });
  };

  const inputGrade = (event, studentID, courseID) => {
    event.preventDefault();

    const key = `${studentID}-${courseID}`;
    const gradeValue = grades[key]; 

    axios
      .post("http://localhost:5001/inputGrade", {
        grade: gradeValue,
        studentID,
        courseID,
      })
      .then((res) => {
        alert(res.data);
        retrieveEnrollments(); 
        setGrades((prev) => ({ ...prev, [key]: "" }));
      })
      .catch((err) => {
        alert(err.response?.data || "Server error");
      });
  };

  return (
    <div>
      <h1>Grades Page</h1>
      <div className="enrollmentSelection">
        <p>Select the enrollment you wish to assign a grade to</p>
        <button id="showEnrolls" type="button" onClick={retrieveEnrollments}>
          Show all enrollments (StudentID -- CourseID)
        </button>

        {showEnrollments && (
          <ul className="listEnroll">
            {enrollments.map((e) => {
              const key = `${e.StudentID}-${e.CourseID}`;
              return (
                <li key={key}>
                  {e.StudentID} — {e.CourseID} | Current Grade:{" "}
                  {e.LetterGrade ? e.LetterGrade : "No grade yet"}

                  <form onSubmit={(event) => inputGrade(event, e.StudentID, e.CourseID)}>
                    <label htmlFor="grade"> Enter or change grade </label>

                    <input
                      type="text"
                      id="grade"
                      name="grade"
                      value={grades[key] || ""}
                      onChange={(ev) =>
                        setGrades({
                          ...grades,
                          [key]: ev.target.value,
                        })
                      }
                    />

                    <button type="submit">Save</button>
                  </form>
                </li>
              );
            })}
          </ul>
        )}

    <button className="backBtn" onClick={() => navigate("/")}>Return to Home</button>

      </div>
    </div>
  );
}

export default Grades;
