import axios from 'axios';
import {useState, useEffect} from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Grades from "./grades";
import Summary from "./summary";

import './App.css';

function App() {
  const navigate= useNavigate();
  const [students, setStudents] = useState([]);
  const [showStudents, setShowStudents] = useState(false);
  const[courses, setCourses] = useState([]);
  const [showCourses, setShowCourses] = useState(false);
  const [showEnrollInput, setShowEnrollInput] = useState(false);
  const [studentID, setStudentID] = useState("");
  const [courseID, setCourseID] = useState("");


  const handleViewStudents = () => {
  axios.get("http://localhost:5001/students")
  .then(res => {
    setStudents(res.data);
    setShowStudents(!showStudents);
  });
};

const handleViewCourses = () => {
  axios.get("http://localhost:5001/courses")
  .then(res => {
    setCourses(res.data);
    setShowCourses(!showCourses);
  });
};

  const startEnrolling = () => {
   setShowEnrollInput(true)
  }


  const handleEnrollSubmit= (e) => {
    e.preventDefault();

      if (!studentID || !courseID) {
    alert("Both fields must be filled.");
    return;
  }
   axios.post("http://localhost:5001/enroll", {
    studentID,
    courseID
  })
 .then(  res=>{
  return axios.post("http://localhost:5001/newEnrollment", {
  studentID,
  courseID
 }); 
}).then(res=> {
  alert(res.data)
})
.catch(err=> {
  console.log(`${err.response}`)
  alert(err.response?.data|| "Server error");
})
  
};

                  
    return (
      <Routes>
    
    <Route path="/" element={
<div className='container'>
      <h1>Student Course Tracker</h1>
    
<div className="button-container">
  <button id='students' onClick={handleViewStudents}>View list of students</button>
  <button id='courses' onClick={handleViewCourses}> View list of courses</button>
</div>
<div className='list-wrapper'>
  <div className="list-container1">
      {showStudents && (
        <ul className='list'> 
          {students.map((s) => (
            <li key={s.StudentID}>
              {s.StudentID} — {s.FirstName} {s.LastName}
            </li>
          ))}
        </ul>
      )}
  </div>


<div className="list-container2">
      {showCourses && (
        <ul className='list'>
          {courses.map((c) => (
            <li key={c.CourseID}>
              {c.CourseID} — {c.CourseName} 
            </li>
          ))}
        </ul>                                                                                                                                                                                                                                       
      )}
   </div>
</div>

<div className='enroll'>
<button id='enrollButton' onClick={startEnrolling}> Start enrolling </button>
</div>
<div className='enrollInput'>
{showEnrollInput && (

 <form onSubmit={handleEnrollSubmit}>
      <label htmlFor='studentID'> Enter the ID of the student you wish to enroll</label>
      <input type='text' id='studentID' name='studentID' value={studentID}
 onChange={(e)=> setStudentID(e.target.value)}/> <br/>
      <label htmlFor='courseID'> Enter the ID of the course you wish to enroll the student on</label>
      <input type='text' id='courseID' name='courseID' value={courseID} 
      onChange={(e) => setCourseID(e.target.value)}/> <br/> <br/>
   <button type="submit">Submit enrollment</button>

 </form>
)}
</div>


<div className='gradeButton'>
<button id='gradeButton'type="submit" onClick={()=> navigate('/grades')}>Assign grades here</button>

</div>

<div className='summaryButton'>
  <button id='summaryBtn' onClick={() => navigate('/summary')}>View Information Summary</button>
</div>

</div>}
/>

   
 <Route path="/grades" element={ <Grades />}/>
 <Route path="/summary" element={<Summary />} />
</Routes>


); }

export default App; 

