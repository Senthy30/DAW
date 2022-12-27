<?php 
    session_start(); 
	include "../db_conn.php";

    $dirName = "id_" . $_SESSION['id'] . "/";
    $dirPath = "../img/profiles/" . $dirName;

    if(!is_dir($dirPath))
        $dirPath = "../img/profiles/user/"; 
    
    $dirPath = $dirPath . "main.jpg";
?>

<!DOCTYPE html>

<html>
    
    <head>
    
        <title>Profile</title>

        <link rel="stylesheet" href="../static/css/profile/style.css">
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

						<div class="currency">
							RON
						</div>
						
					</div>

				</div>

			</div>

            <div class="titleDiv">
                About you <i class="arrow right"></i>
            </div>

            <div class="aboutYou">
                <div class="profileImage">
                    <div class="divImageProfile">
                        <img src=" <?php echo $dirPath; ?>">
                    </div>

                    <form action="uploadImage.php" method="POST" enctype="multipart/form-data" class="imageSubmitButtons">
                        <label for="profileImageID">
                            Browse
                        </label>
                        <input id="profileImageID" type="file" name="file">
                        
                        <label for="submitImageID">
                            Upload
                        </label>
                        <input id="submitImageID" type="submit" name="submit">
                    </form>
                </div>

                <div class="mainInformation">
                    <div class="titleInformation">
                        Main information
                    </div>

                    <div class="elementInformation">
                        <label>
                            Email
                        </label>
                        <div>
                            <?php echo $_SESSION['email']; ?>
                        </div>
                    </div>

                    <div class="elementInformation">
                        <label for="fname">
                            First name
                        </label>
                        <input id="fname" type="text" value="<?php echo $_SESSION['fname']; ?>" placeholder="First name">
                    </div>

                    <div class="elementInformation">
                        <label for="lname">
                            Last name
                        </label>
                        <input id="lname" type="text" value="<?php echo $_SESSION['lname']; ?>" placeholder="Last name">
                    </div>

                    <div class="submitButtons">
                        <button id="changeNameButton" type="submit" onclick="changeName()">Change</button>
                        <button onclick="window.location.href = '../login/logout.php'">Logout</button>
                    </div>
                </div>

                <div class="changePassword">
                    <div class="titleInformation">
                        Change password
                    </div>

                    <div class="elementInformation">
                        <label for="currentPassword">
                            Current password
                        </label>
                        <input id="currentPassword" type="password" placeholder="Password">
                    </div>

                    <div class="elementInformation">
                        <label for="newPassword">
                            New password
                        </label>
                        <input id="newPassword" type="password" placeholder="Password">
                    </div>

                    <div class="elementInformation">
                        <label for="confirmNewPassword">
                            Confirm new password
                        </label>
                        <input id="confirmNewPassword" type="password" placeholder="Password">
                    </div>

                    <div class="submitButtons">
                        <button onclick="changePassword()" type="submit">Submit</button>
                    </div>
                </div>
            </div>

            <div class="history">
                <div class="titleDiv">
                    Order history <i class="arrow right"></i>
                </div>

                <?php
                    $idSession = $_SESSION['id'];
                    $sql = "SELECT * FROM rents WHERE id_user = $idSession;";
                    $resultQuery = mysqli_query($conn, $sql);
                    while($resultRents = $resultQuery->fetch_object()){
                        $idRoom = $resultRents->id_room;
                        $sqlRoom = "SELECT * FROM rooms WHERE id = $idRoom;";
                        $result = mysqli_query($conn, $sqlRoom);
                        $result = $result->fetch_object();

                        $imagePath = "../img/rooms/id_" . $result->id . "/1.jpg";
                        $name = $result->name . " ";
                        for($i = 0; $i < $result->stars; $i++){
                            $name = $name . "⭐";
                        }
                        $floorNr = "Floor " . $result->floor . ", Nr. " . $result->number;
                        
                        $capacityRoom = 2;
                        $typeRoom = "Apartment with 1 room";
                        $typeValue = $result->type;
                        if($typeValue >= 2 && $typeValue <= 5) {
                            $typeRoom = "Apartment with " . $typeValue. " rooms"; 
                            $capacityRoom = intval($typeValue) * 2;
                        } else if($typeValue == 6)
                            $typeRoom = "Double room";
                        else if($typeRoom == 7) {
                            $typeRoom = "Triple room";
                            $capacityRoom = 3;
                        } else if($typeRoom == 8) {
                            $typeRoom = "Quadruple room";
                            $capacityRoom = 4;
                        }

                        $surface = "Surface " . $result->surface .  "m<sup>2</sup>";
                        $cancellation = "No cancellation";
                        if($result->cancellation != -1)
                            $cancellation = "Cancellation up to " . $result->cancellation ." days before";
                        $advance = "No advance is required";
                        if($result->advance == 1)
                            $advance = "Advance is required";
                        $food = "Food included";
                        if($result->food == 0)
                            $food = "No food included";
                        
                        $sqlFeedback = "SELECT AVG(note) as SumNote FROM feedbacks WHERE id_room = $idRoom";
                        $resultFeedback = mysqli_query($conn, $sqlFeedback);
                        $resultFeedback = $resultFeedback->fetch_object();
                        $rating = intval($resultFeedback->SumNote * 100) / 100;
                        if($rating == 0)
						    $rating = "No feedback";
                        
                        $startDate = new DateTime($resultRents->startDate);
                        $endDate = new DateTime($resultRents->endDate);
                        $nights = $endDate->diff($startDate)->format("%a"); 

                        $price = ($result->price * intval($nights)) . " lei";
                ?>


                <div class="adRoom">
                    <div class="imageRoom">
                        <img src="<?php echo $imagePath ?>">
                    </div>

                    <div class="contentRoom">
                        <div class="aboutRoom">
                            <div class="mainInfRoom">
                                <div class="nameRoom">
                                    <?php echo $name ?>
                                </div>

                                <div class="infRoom">
                                    <div class="firstInfRoom">
                                        <div class="sthInfRoom">
                                            <?php echo $floorNr ?>
                                        </div>

                                        <div class="sthInfRoom">
                                            <?php echo $typeRoom ?>
                                        </div>

                                        <div class="sthInfRoom">
                                            <?php echo $surface ?>
                                        </div>
                                    </div>

                                    <div class="secondInfRoom">
                                        <div class="sthInfRoom">
                                            <?php echo $cancellation ?>
                                        </div>

                                        <div class="sthInfRoom">
                                            <?php echo $advance ?>
                                        </div>

                                        <div class="sthInfRoom">
                                            <?php echo $food ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="scInfRoom">
                                <div class="ratingRoom">
                                    <div>
                                        <?php echo $rating ?>
                                    </div>
                                </div>

                                <div class="priceRoom">
                                    <div>
                                        <?php echo $price ?>
                                    </div>

                                    <div>
                                        <?php if($nights == 1) echo "1 night"; else echo $nights. " nights"; ?>, <?php echo $capacityRoom ?> persons
                                    </div>
                                </div>

                                <div class="rentRoom" onclick="window.location.href = 'feedback/index.php?id=<?php echo (intval($result->id / 10) + 1); ?>'">
                                    <div>
                                        Give feedback
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="needLogin">
                            <div>
                                <?php echo "From: " . $resultRents->startDate . "   To: " . $resultRents->endDate; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    }
                ?>

            </div>

            <div class="deleteAccount">
                <a href="">Delete account</a>
            </div>

        </div>

        <script src="../static/js/profile/script.js"></script>
    </body>

</html>

<?php 
    if(isset($_SESSION['message'])){
        echo "<script defer> alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    }
?>