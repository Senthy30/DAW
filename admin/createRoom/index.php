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

    $idSession = $_SESSION['id'];
    $sql = "SELECT isAdmin FROM permission WHERE id_user = $idSession;";
    $result = mysqli_query($conn, $sql);
    $result = $result->fetch_object();
    if($result->isAdmin == 0){
        header("Location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>

<html>

    <head>

        <title>Create room</title>

        <link rel="stylesheet" href="../../static/css/admin/createRoom/style.css">
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
                        <div class="menuButton" onclick="window.location.href = 'login/logout.php'">
                            <div class="textButton">
                                Profiles
                            </div>
                        </div>
                            
                        <div class="menuButton" onclick="window.location.href = '../index.php'">
                            <div class="textButton">
                                Home
                            </div>
                        </div>

						<div class="language">
							<img src="../../img/flagRo.png" class="flagLang">
						</div>
						
					</div>

				</div>

                <div class="typePage">
                    Create new room
                </div>

                <div class="loginMenu">
                    <form action="createRoom.php" method="POST" enctype="multipart/form-data">
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
                                <label> Image </label>
                            </div>
                            <input type="file" name="image" required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Name </label>
                            </div>
                            <input type="text" name="roomName" placeholder="Room name" required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Stars </label>
                            </div>
                            <input type="number" name="valStars" placeholder="Number of stars" min=1 max=5 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Floor </label>
                            </div>
                            <input type="number" name="valFloor" placeholder="Floor number" min=0 max=15 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Number </label>
                            </div>
                            <input type="number" name="valNumber" placeholder="Room number" min=0 max=1500 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Type </label>
                            </div>
                            <input type="number" name="valType" placeholder="Type of room" min=1 max=8 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Surface </label>
                            </div>
                            <input type="number" name="valSurface" placeholder="Surface" min=10 max=200 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Advance </label>
                            </div>
                            <input type="number" name="valAdvance" placeholder="Advance required" min=0 max=1 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Cancellation </label>
                            </div>
                            <input type="number" name="valCancellation" placeholder="Cancellation before advance" min=-1 max=31 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Food </label>
                            </div>
                            <input type="number" name="valFood" placeholder="Food included" min=0 max=1 required>
                        </div>

                        <div>
                            <div class="labelDiv">
                                <label> Price </label>
                            </div>
                            <input type="number" name="valPrice" placeholder="Price per night in RON" min=0 max=2000 required>
                        </div>

                        <div class="submitDiv">
                            <button type="submit" class="submitButton"> Create </button>
                        </div>
                    </form>
                </div>

			</div>

        </div>

    </body>

</html>