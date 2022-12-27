<?php 
    session_start(); 
    include "../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }
?>

<!DOCTYPE html>

<html>

    <head>

        <title>Login</title>

        <link rel="stylesheet" href="../static/css/contact/style.css">
    </head>

    <body>

        <div class="content">

            <div class="topPage">
				<img src="../img/bkImage.png" class="bkImage">

				<div class="topMenu">

					<div class="logo">
						Hotel Maria
					</div>

					<div class="optionsMenu">
						<?php 
							if(isset($_SESSION['id'])){
						?>	
							<div class="menuButton" onclick="window.location.href = 'login/logout.php'">
								<div class="textButton">
									Profiles
								</div>
							</div>
						<?php 
							}
						?>
                                
                        <div class="menuButton" onclick="window.location.href = '../index.php'">
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
							<img src="../img/flagRo.png" class="flagLang">
						</div>
						
					</div>

				</div>

                <div class="typePage">
                    Contact
                </div>

                <div class="contentFeedback">

                    <div class="loginMenu">
                        <form action="createFeedback.php" method="POST">
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
                            
                            <div>
                                <div class="labelDiv">
                                    <label> Title </label>
                                </div>
                                <input type="text" name="title" placeholder="Title" required>
                            </div>

                            <div>
                                <div class="labelDiv">
                                    <label> Description </label>
                                </div>
                                <textarea name="description" rows="6" placeholder="Description" required></textarea>
                            </div>

                            <input type="number" name="idRoom" value="<?php echo $idRoom; ?>" style="display: none;">

                            <div class="submitDiv">
                                <button type="submit" class="submitButton"> Submit contact </button>
                            </div>
                        </form>
                    </div>
                </div>

			</div>

        </div>

    </body>

</html>