<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="registration.css">
</head>
<body>
    <div class="container"> 
        <?php
        if(isset($_POST["submit"])){
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if(empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)){
                array_push($errors,"ALL FIELDS ARE REQUIRED");
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($errors,"email is not valid");
            }
            if(strlen($password)<8){
                array_push($errors,"password must be at least 8 charecters long");
            }
            if ($password!==$passwordRepeat){
                array_push($errors,"password does not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE EMAIL = '$email'";
            $result = mysql_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);
            if(count($errors)>0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }else{
                //we will insert the data into database
                
                $sql = "INSERT INTO users (full_name,email,password) VALUES (?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                $preparestmt = mysqli_stmt_prepare($stmt,$sql);
                if ($preparestmt){
                    mysqli_stmt_bind_param($stmt,"sss",$fullName,$email,$passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo"<div class='alert alert-success'>you are registred successfully.</div>";
                }else{
                    die("something went wrong");
                }
                }

            }

        
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form.control" name="fullname" placeholder="fullname"> 
            </div>
            <div class="form-group">
                <input type="email"class="form.control"  name="email" placeholder="email"> 
            </div>
            <div class="form-group">
                <input type="password" class="form.control" name="password" placeholder="password"> 
            </div>
            <div class="form-group">
                <input type="text" class="form.control"  name="repeat_password" placeholder="repeat password"> 
            </div>
            <div class="form.btn">
                <input type="submit" class="btn btn-primary"  value="register" name="submit">
            </div>
        </form>
     </div>




</body>
</html>

