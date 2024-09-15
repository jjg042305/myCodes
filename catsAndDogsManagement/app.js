let express = require('express');
let app = express();
const path = require('path');
const session = require('express-session');
app.use(express.static(path.join(__dirname, 'public')));
const port =5500;
app.listen(port, '0.0.0.0', () => {
  console.log(`Server is listening on http://0.0.0.0:${port}`);
});
app.use(session({
  secret: 'H3G1X6', // A secret key for signing the session ID cookie
  resave: false, // Don't save the session back to the store if it hasn't been modified
  saveUninitialized: false, // Don't create a session until something is stored
  cookie: { secure: false } // Use secure cookies (requires HTTPS)
}));
const fs = require('fs');
const availablePetsFilePath = path.join(__dirname, 'availablePets.txt'); 
const multer = require('multer');
const upload = multer(); // for parsing multipart/form-data

// set the view engine to ejs
app.set('view engine', 'ejs');
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

app.use((req, res, next) => {
    // Make the user session available to all templates
    res.locals.isLoggedIn = req.session.isLoggedIn;
    next();
});

// use res.render to load up an ejs view file
const { registerUser, verifyLogin } = require('./register');

// index page
app.get('/', function (req, res) {
  
  
  res.render('home');
});
app.get('/home', function (req, res) {
 
  res.render('home');
});


app.get('/giveaway', function (req, res) {
  const info = "Name: John Doe, Age: 28";
  const regex = /Age: (\d+)/;
  const match = info.match(regex);
  console.log(match[1]);
  console.log(match);
  let text = "The rain in SPAIN stays mainly in the plain";
let result = text.match(/ain/);
 console.log(result);
  res.render('giveaway');
});

app.post('/submitGiveaway',upload.none(), function (req, res){
  if (!req.session.user){
    console.log('No user in session');

    res.send(`<html>
    <body>
      <p>You must log in first before submitting your pet. Directing you to the login page in 5 seconds...</p>
      <script>
        setTimeout(function() {
          window.location.href = '/login';
        }, 5000); // 5000 milliseconds = 5 seconds
      </script>
    </body>
  </html>`);
  }
  else {
 const username = req.session.user.username;
 const { petType, breed, age, gender, alongC, alongD, suitable, descrip, ownerName, email } = req.body;
 console.log('POST request received');
 console.log('Headers:', req.headers);
 console.log('Body:', req.body);
// Ensure the file exists or create it if it doesn't
if (!fs.existsSync(availablePetsFilePath)) {
  fs.writeFileSync(availablePetsFilePath, ''); // Create an empty file
}
 fs.readFile(availablePetsFilePath, 'utf8', (err, data) => {
  
  let pets = [];
  if (!err && data) {
    // Split the file content into lines and parse each line
    pets = data.trim().split('\n').map(line => {
      const parts = line.split(':');
      return {
        id: parseInt(parts[0]),
        owner: parts[1],
        details: parts.slice(2)
      };
    });
  }

  // Determine the next ID
  const nextId = pets.length > 0 ? Math.max(...pets.map(pet => pet.id)) + 1 : 1;

  // Format the new entry with the next ID and form data
  const newEntry = `${nextId}:${username}:${petType}:${breed}:${age}:${gender}:${alongC}:${alongD}:${suitable}:${descrip}:${ownerName}:${email}\n`;

  // Append the new entry to the file
  fs.appendFile(availablePetsFilePath, newEntry, 'utf8', appendErr => {
    if (appendErr) {
      console.error('Failed to append new pet information', appendErr);
      return res.status(500).send('An error occurred while submitting pet information.');
    }
    res.send(`<html>
    <body>
      <p>Pet information registered successfully!. Redirecting in 5 seconds...</p>
      <script>
        setTimeout(function() {
          window.location.href = '/giveaway';
        }, 5000); // 5000 milliseconds = 5 seconds
      </script>
    </body>
  </html>`);
  });
});
}

});




app.get('/privacy', function (req, res) {
  res.render('privacy');
});
app.get('/catCare', function (req, res) {
  res.render('catCare');
});
app.get('/contact', function (req, res) {
  res.render('contact');
});
app.get('/dogCare', function (req, res) {
  res.render('dogCare');
});
app.get('/find', function (req, res) {

  res.render('find', { pets: [] });
});

app.get('/privacy', function (req, res) {
  res.render('privacy');
});
app.post('/login', function (req, res) {
  // You would replace this with your verifyLogin function logic
  const { username, password } = req.body;
  verifyLogin(username, password, function (err, user) {
    if (err) {
      res.send('Login failed'); // Or render a page with an error message
    }
    if (!user) {
      res.send(`<html>
        <body>
          <p>User doesn't exist or your password might be incorrect. Please register first or make sure you've typed in the correct password. Redirecting in 5 seconds...</p>
          <script>
            setTimeout(function() {
              window.location.href = '/';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>
        </body>
      </html>`);
    }
    else {
      // Log the user in (set a cookie, create a session, etc.)
      req.session.user= {username};
      req.session.isLoggedIn = true;  // Set login flag

      console.log("Logged in user:", req.session.user); // Check what's being set

      res.send(`<html>
        <body>
          <p>You have successfully logged in!. Redirecting in 5 seconds...</p>
          <script>
            setTimeout(function() {
              window.location.href = '/';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>
        </body>
      </html>`);

}
  });
});


app.get('/login', function (req, res) {
  res.render('login');
})




app.post('/find', function (req, res) {
    const searchCriteria = req.body;
    const petsFilePath = path.join(__dirname, 'availablePets.txt');

    fs.readFile(petsFilePath, 'utf8', (err, data) => {
        if (err) {
            console.error("Failed to read file:", err);
            return res.status(500).send('Error reading pet data.');
        }

        const pets = data.split('\n')
                         .filter(line => line.trim())
                         .map(line => {
                             const parts = line.split(':');
                             return {
                                 id: parts[0],
                                 name: parts[1],
                                 type: parts[2],
                                 breed: parts[3],
                                 age: parts[4],
                                 gender: parts[5],
                                 compatibility: {
                                     cats: parts[6],
                                     dogs: parts[7],
                                     children: parts[8]
                                 },
                                 description: parts[9],
                                 ownerName: parts[10],
                                 email: parts[11]
                             };
                         });

        const filteredPets = pets.filter(pet => {
            return (!searchCriteria.petType || pet.type.toLowerCase() === searchCriteria.petType.toLowerCase()) &&
                   (!searchCriteria.breed || pet.breed.toLowerCase().includes(searchCriteria.breed.toLowerCase())) &&
                   (!searchCriteria.age || pet.age.toLowerCase() === searchCriteria.age.toLowerCase()) &&
                   (!searchCriteria.gender || pet.gender.toLowerCase() === searchCriteria.gender.toLowerCase()) &&
                   (!searchCriteria.along || (pet.compatibility.cats.toLowerCase().includes(searchCriteria.along.toLowerCase()) ||
                                              pet.compatibility.dogs.toLowerCase().includes(searchCriteria.along.toLowerCase()) ||
                                              pet.compatibility.children.toLowerCase().includes(searchCriteria.along.toLowerCase())));
        });

        res.render('find', { pets: filteredPets });
    });
});





app.post('/register', function (req, res) {
  const { username2, password2 } = req.body;

  registerUser(username2, password2, function (err, result) {
    if (err) {
      console.error(err);
      return res.status(400).send(err.message); // Respond with the error message
    }

    // Check the result to tailor the response
    if (result && result.created) {
      res.send(`<html>
        <body>
          <p>User created successfully, you can now log in whenever you are ready. Redirecting in 5 seconds...</p>
          <script>
            setTimeout(function() {
              window.location.href = '/';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>
        </body>
      </html>`);
    } else if (result && result.added) {
      res.send(`<html>
        <body>
          <p>User added successfully, you can now log in whenever you are ready. Redirecting in 5 seconds...</p>
          <script>
            setTimeout(function() {
              window.location.href = '/';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>
        </body>
      </html`);
    } else {
      // This should theoretically never hit, but it's good to handle unexpected outcomes
      res.status(500).send('Unexpected outcome');
    }
  });
});

app.get('/logout', function(req, res) {
  req.session.destroy(function(err) {
      if (err) {
          console.error("Failed to destroy the session during logout.", err);
          return res.status(500).send('Could not log out, please try again');
      }
      res.send(`<html>
      <body>
        <p>You have been logged out! Redirecting in 5 seconds...</p>
        <script>
          setTimeout(function() {
            window.location.href = '/';
          }, 5000); // 5000 milliseconds = 5 seconds
        </script>
      </body>
    </html`);
  });
});







