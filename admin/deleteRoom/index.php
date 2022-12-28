<?php 
    session_start(); 
    include "../../db_conn.php";
    include "../../exchangeValue.php";
    getCurrency("../../");

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_SESSION['id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])){
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

    $idRoom = ($_GET['id'] - 1) * 10 + 1;
    $sql = "SELECT id FROM rooms WHERE id = $idRoom;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>

<html>

    <head>

        <title>Delete room</title>

        <link rel="stylesheet" href="../../static/css/admin/deleteRoom/style.css">
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
                        <div class="menuButton" onclick="window.location.href = '../index.php'">
                            <div class="textButton">
                                Profile
                            </div>
                        </div>
                                
                        <div class="menuButton" onclick="window.location.href = '../../index.php'">
                            <div class="textButton">
                                Home
                            </div>
                        </div>

                        <div class="menuButtonAdmin">
                            <div class="textButtonAdmin">
                                Admin
                            </div>
                        </div>

						<div class="language">
							<img src="../../img/flagRo.png" class="flagLang">
						</div>

                        <div class="currency">
							<form class="formCurrency" method="POST" action="../../changeExchangeCurrency.php">
								<select class="selectCurrency" id="currentcyID" name="valCurrency" onchange='this.form.submit()'>
									<option value="" disabled selected hidden><?php echo $_SESSION['currency']; ?></option>
									<option value="RON">RON</option>
									<option value="EUR">EUR</option>
									<option value="GBP">GBP</option>
									<option value="USD">USD</option>
								</select>
							</form>
						</div>
						
					</div>

				</div>

                <div class="typePage">
                    Delete room
                </div>

                <div class="contentFeedback">
                    <?php
                        $sqlRoom = "SELECT * FROM rooms WHERE id = $idRoom;";
                        $result = mysqli_query($conn, $sqlRoom);
                        $result = $result->fetch_object();

                        $imagePath = "../../img/rooms/id_" . $result->id . "/1.jpg";
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

                        $nights = 1; 
                        $price = (intval(($result->price * $nights) * $exchangeRate)) . " $typeCurrency";
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="loginMenu">
                        <form action="deleteRoom.php" method="POST">
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
                                Are you sure you want to delete this room forever? <br> If yes, enter you password and confirm it.
                            </div>

                            <div>
                                <div class="labelDiv">
                                    <label> Password </label>
                                </div>
                                <input type="password" name="pass" placeholder="Password">
                            </div>
                            
                            <input type="number" name="idRoom" value="<?php echo $idRoom; ?>" style="display: none;">

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

        </div>

    </body>

</html>