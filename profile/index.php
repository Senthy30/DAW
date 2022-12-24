<?php 
    session_start(); 
	include "../db_conn.php";

    if(isset($_SESSION['message'])){
        echo "<script> alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    }
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
                    <img src="../img/profiles/id_1/1.jpg">
                    <button type="submit">Upload</button>
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
                        <label>
                            First name
                        </label>
                        <input id="fname" type="text" value="<?php echo $_SESSION['fname']; ?>" placeholder="First name">
                    </div>

                    <div class="elementInformation">
                        <label>
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
                        <label>
                            Current password
                        </label>
                        <input id="currentPassword" type="password" placeholder="Password">
                    </div>

                    <div class="elementInformation">
                        <label>
                            New password
                        </label>
                        <input id="newPassword" type="password" placeholder="Password">
                    </div>

                    <div class="elementInformation">
                        <label>
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


                <div class="adRoom">
                    <div class="imageRoom">
                        <img src="../img/rooms/id_1/1.jpg">
                    </div>

                    <div class="contentRoom">
                        <div class="aboutRoom">
                            <div class="mainInfRoom">
                                <div class="nameRoom">
                                    Luxury ⭐⭐⭐⭐⭐
                                </div>

                                <div class="infRoom">
                                    <div class="firstInfRoom">
                                        <div class="sthInfRoom">
                                            Floor 4, Nr. 402
                                        </div>

                                        <div class="sthInfRoom">
                                            Apartment with 2 rooms
                                        </div>

                                        <div class="sthInfRoom">
                                            Surface 40m<sup>2</sup>
                                        </div>
                                    </div>

                                    <div class="secondInfRoom">
                                        <div class="sthInfRoom">
                                            Cancellation up to 5 days before
                                        </div>

                                        <div class="sthInfRoom">
                                            No advance is required
                                        </div>

                                        <div class="sthInfRoom">
                                            Food included
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="scInfRoom">
                                <div class="ratingRoom">
                                    <div>
                                        8.9
                                    </div>
                                </div>

                                <div class="priceRoom">
                                    <div>
                                        1850 lei
                                    </div>

                                    <div>
                                        3 nights, 4 persons
                                    </div>
                                </div>

                                <div class="rentRoom">
                                    <div>
                                        Give Feedback
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="deleteAccount">
                <a href="">Delete account</a>
            </div>

        </div>

        <?php 
            if(isset($_SESSION['message'])){
                echo "<script> alert('" . $_SESSION['message'] . "');</script>";
                unset($_SESSION['message']);
            }
        ?>

        <script src="../static/js/profile/script.js"></script>
    </body>

</html>