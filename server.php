<?php
    session_start();

    $username = "";
    $email = "";
    $errors = array();

    // connect to the db
    $db = mysqli_connect('127.0.0.1', 'root', '', 'registration', '3306');
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }
 // echo mysqli_query($db, "SELECT * FROM users");
    
    // if the register button is clicked
    if (isset($_POST['register'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

        // ensure that form fields are filled properly
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        if (empty($password_1)) {
            array_push($errors, "Password is required");
        }
        if ($password_1 != $password_2) {
            array_push($errors, "The two password do not match");
        }
        // if there are no errors, save user to database
        if(count($errors) == 0) {
            $password = md5($password_1);  // encrypt password before saving
            $sql = "INSERT INTO users (username, email, password) 
                    VALUES('$username', '$email', '$password')";
            mysqli_query($db, $sql);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php'); // redirect to home page
        }
    }

    // log user in from login page
    if(isset($_POST['login'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        // ensure that form fields are filled properly
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        if(count($errors) === 0) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($db, $query);
            if(mysqli_num_rows($result) === 1) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php'); // redirect to home page
            } else {
                array_push($errors, "wrong username/password combination");
            }
        }
        
    }
    

    // logout
    if(isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        // go back to login page after being logged out
        header('location: login.php');
    }
?>