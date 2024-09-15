const fs = require('fs');
const path = require('path');

function verifyLogin(username, password, callback) {
  console.log(`${username}:${password}`);
  const loginFilePath = path.join(__dirname, 'login.txt');
  fs.readFile(loginFilePath, 'utf8', (err, data) => {
    if (err) {
      return callback(err, null);
    }
    
    const lines = data.trim().split('\n');
    console.log(lines);
    const isValid = lines.some(line => {
      const [fileUsername, filePassword] = line.split(':');
      return fileUsername === username && filePassword === password;
    });

    callback(null, isValid);
  });
}

function registerUser(username, password, callback) {
    const loginFilePath = path.join(__dirname, 'login.txt');
    const newUserLine = `${username}:${password}\n`; // Ensure the newline is at the end
  
    fs.readFile(loginFilePath, 'utf8', (err, data) => {
      if (err) {
        // If there's an error reading the file, perhaps the file does not exist
        // So we create a new one with this user and indicate that the user was created
        return fs.writeFile(loginFilePath, newUserLine, 'utf8', (writeErr) => {
          if (writeErr) {
            callback(writeErr);
          } else {
            callback(null, { created: true }); // Indicate user was created
          }
        });
      }
  
      if (data.includes(`${username}:`)) {
        // User already exists
        return callback(new Error('User already exists'));
      }
  
      // Append the new user to the file and indicate that the user was added
      fs.appendFile(loginFilePath, newUserLine, 'utf8', (appendErr) => {
        if (appendErr) {
          callback(appendErr);
        } else {
          callback(null, { added: true }); // Indicate user was added
        }
      });
    });
  }
  

// // Usage
// verifyLogin('user1', 'pass1', (err, isValid) => {
//   if (err) {
//     console.error(err);
//   } else if (isValid) {
//     console.log('Login successful');
//   } else {
//     console.log('Login failed');
//   }
// });

// // Registering a user
// registerUser('newuser', 'newpass', (err) => {
//   if (err) {
//     console.error(err);
//   } else {
//     console.log('User registered successfully');
//   }
// });


module.exports = {registerUser, verifyLogin};
