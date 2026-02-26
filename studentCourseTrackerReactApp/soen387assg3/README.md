Student Course Tracker

This application is a full-stack project that allows a prof user to view students and courses, enroll a student into a course, assign or change grades, and view information summaries such as all grades in a specific course or all courses taken by a specific student. The project uses React for the frontend, Node.js and Express for the backend, and MySQL as the database.

How to run the project:


Open a terminal and navigate to the server folder. Run the following command to create the database and insert sample values:
node setup.js
Still in the server folder, start the backend server by running:
node index.js
You should see a message confirming database connection and the server running on http://localhost:5001

Open a second terminal and navigate to the client folder. Start the React application by running:
npm start

Keep both terminals running. Open a web browser and go to:
http://localhost:3000
The website will now be fully functional.

Project structure:
client/ contains the React interface
server/ contains the API endpoints and database logic
setup.js builds the database tables and inserts example data
index.js runs the Express backend
Requirements:
Node.js must be installed
MySQL must be running locally and accessible
npm must be installed
