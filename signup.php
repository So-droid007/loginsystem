<?php

$showAlert = false;
$showError = false;

include("partials/_dbconnect.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $hash = password_hash($password,PASSWORD_DEFAULT);


    $existsSql = "SELECT * FROM `users` WHERE username= '$username'";
    $result = mysqli_query($conn,$existsSql);

    $numRows = mysqli_num_rows($result);




    if($numRows>0 && $password!=$cpassword){
        $showError = "Username already exists and password do not match";
    }


   

    else if($numRows>0){
        $showError = "Username already exists";
    }


  
    

    else{

        if(($password==$cpassword)){

            $sql = "INSERT INTO `users` (`sno`, `username`, `password`, `dt`) VALUES (NULL, '$username', '$hash', current_timestamp())";
    
            $result = mysqli_query($conn,$sql);
            if($result){
                $showAlert = true;
            }
           
    
        }
        else{
            $showError = "Password do not match";
        }
    
       
    }


    }
   
 

  




?>





<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SignUp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>


<body>
    <?php require 'partials/_nav.php';  ?>
   

    <?php

    if($showAlert){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your account has been created and you can login.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
    if($showError){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '.$showError.'.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }




?>


    <div class="container">

        <h1 class="text-center my-3">Signup to our website</h1>
        <form action="/loginsystem/signup.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" maxlength="16" id="username" name="username" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">Make sure that you typed same password.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="cpassword" id="cpassword">
            </div>
           
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>