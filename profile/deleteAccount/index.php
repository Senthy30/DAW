<?php 
    session_start(); 
    include "../../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_SESSION['id'])) {
        header("Location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>

<html>

    <head>

        <title>Delete account</title>

        <link rel="stylesheet" href="../../static/css/profile/deleteAccount/style.css">
    </head>

    <body>

        <div class="content">

            <div class="topPage">
				<img src="../../img/bkImage.png" class="bkImage">

				<div class="topMenu">

					<div class="logo">
						Hotel Maria
					</div>

					<div class="optionsMenu">
						<?php 
							if(isset($_SESSION['id'])){
						?>	
							<div class="menuButton" onclick="window.location.href = '../index.php'">
								<div class="textButton">
									Profiles
								</div>
							</div>

						<?php 
							} 
                        ?>

                        <div class="menuButton" onclick="window.location.href = '../../index.php'">
                            <div class="textButton">
                                Home
                            </div>
                        </div>

						<?php 
							$idUser = -1;
							if(isset($_SESSION['id']))
								$idUser = $_SESSION['id'];
							$sql = "SELECT isAdmin FROM permission WHERE id_user = '$idUser';";
							$result = mysqli_query($conn, $sql);
							$result = $result->fetch_object();

							if($idUser != -1 && $result->isAdmin == 1){ 
						?>
								<div class="menuButtonAdmin">
									<div class="textButtonAdmin">
										Admin
									</div>
								</div>
						<?php 
							} 
						?>

						<div class="language">
							<img src="../../img/flagRo.png" class="flagLang">
						</div>
						
					</div>

				</div>

                <div class="typePage">
                    Delete account
                </div>

                <div class="loginMenu">
                    <form action="deleteAccount.php" method="POST">
                        <div class="error">
                            <?php
                                if(isset($_GET['error'])) {
                                    $messageError = sanitize_input($_GET['error']);
                                    print_r($messageError);
                                } else {
                                    echo "&nbsp;";
                                }
                            ?>
                        </div>

                        <div class="areYouSure">
                            Are you sure you want to delete forever your account? <br> If yes, enter you password and confirm it.
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Password </label>
                            </div>
                            <input type="password" name="pass" placeholder="Password">
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Confirm password </label>
                            </div>
                            <input type="password" name="confPass" placeholder="Confirm password">
                        </div>

                        <div class="submitDiv">
                            <button type="submit" class="submitButton"> Submit </button>
                        </div>
                    </form>
                </div>

			</div>

        </div>

    </body>

</html>