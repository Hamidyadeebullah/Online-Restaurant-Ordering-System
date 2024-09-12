<?php
require('dbconnection.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="registeration.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1 class="heading">
        <span>r</span>
        <span>e</span>
        <span>g</span>
        <span>i</span>
        <span>s</span>
        <span>t</span>
        <span>r</span>
        <span>a</span>
        <span>t</span>
        <span>i</span>
        <span>o</span>
        <span>n</span>

    </h1>
    <form action="registerationdb.php" method="post">
        <label>Firstname:</label>
        <input type="text" name="firstname" size="15" required/> <br> <br>
        
        <label>Middlename:</label>
        <input type="text" name="middlename" size="15"/> <br> <br>
        
        <label>Lastname:</label>
        <input type="text" name="lastname" size="15" required/> <br> <br>
        
        <label>Gender:</label> <br>
        <input type="radio" name="gender" value="male" id="male" required/>
        <label for="male">Male</label>
        <input type="radio" name="gender" value="female" id="female" required/>
        <label for="female">Female</label> <br> <br>
        
        <label>Phone:</label>
        <input type="text" name="phone" size="15" required/> <br> <br>
        
        <label>Address:</label> <br>
        <textarea name="address" cols="80" rows="5" required></textarea> <br> <br>
        
        <label>Email:</label>
        <input type="email" name="email" required/> <br> <br>
        
        <label>Password:</label>
        <input type="password" name="password" required> <br> <br>
        
        <label>Re-type password:</label>
        <input type="password" name="repassword" required> <br> <br>
        
        <div class="btn-container">
            <input type="submit" value="Register Now" class="btn"/>
        </div>
    </form>
</body>
</html>