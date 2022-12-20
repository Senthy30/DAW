<?php session_start(); ?>

<!DOCTYPE html>

<html>

    <head>

        <title>Login</title>

    </head>

    <body>

        <?php if(isset($_SESSION['id']) && isset($_SESSION['email'])) { ?>
        
            <h1>You can't create a new account while you're log in!</h1>
        
        <?php } else { ?>
            <form action="register.php" method="POST">

                <h2>Login</h2>

                <?php if(isset($_GET['error'])) { ?>
                    <p class="error"> <?php echo $_GET['error']; ?> </p>
                <?php } ?>

                <label> Email </label> <br>
                <input type="text" name="email" placeholder="Email"> <br> 

                <label> First Name </label> <br>
                <input type="text" name="fname" placeholder="First name"> <br> 

                <label> Last Name </label> <br>
                <input type="text" name="lname" placeholder="Last name"> <br> 

                <label> Password </label> <br>
                <input type="password" name="password" placeholder="Password"> <br>
                
                <label> Confirm Password </label> <br>
                <input type="password" name="confirmpassword" placeholder="Confirm password"> <br> <br>

                <button type=submit> Register </button>

            </form>

        <?php } ?>

        <a href="../index.php">Back to main page</a>

    </body>

</html>