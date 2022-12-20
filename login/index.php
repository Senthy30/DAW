<?php session_start(); ?>

<!DOCTYPE html>

<html>

    <head>

        <title>Login</title>

    </head>

    <body>

        <?php if(isset($_SESSION['id']) && isset($_SESSION['email'])) { ?>
        
            <h1>You're already log in</h1>
        
        <?php } else { ?>
            <form action="login.php" method="POST">

                <h2>Login</h2>

                <?php if(isset($_GET['error'])) { ?>
                    <p class="error"> <?php echo $_GET['error']; ?> </p>
                <?php } ?>

                <label> Email </label> <br>
                <input type="text" name="email" placeholder="Email"> <br> 
                
                <label> Password </label> <br>
                <input type="password" name="password" placeholder="Password"> <br> <br>

                <button type=submit> Login </button>

            </form>

        <?php } ?>

        <a href="../index.php">Back to main page</a>

    </body>

</html>