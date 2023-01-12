<?php
    session_start(); 
	include "../db_conn.php";
    include "../exchangeValue.php";
    getCurrency("../");

    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    if(!isset($_SESSION['id'])){
        header("Location: ../index.php");
        exit();
    }

    $isOk = (!isset($_GET['id']) || !is_numeric($_GET['id']));
    $isOk |= (!isset($_GET['startDate']) || empty(validateDate($_GET['startDate'])));
    $isOk |= (!isset($_GET['endDate']) || empty(validateDate($_GET['endDate'])));

    if($isOk){
        header("Location: ../index.php");
        exit();
    }

    $idSession = $_SESSION['id'];
    $roomID = ($_GET['id'] - 1) * 10 + 1;
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $isAvailable = true;
    $sqlDisp = "SELECT * FROM rents WHERE id_room = $roomID AND startDate <= '$startDate' AND endDate > '$startDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $sqlDisp = "SELECT * FROM rents WHERE id_room = $roomID AND startDate < '$endDate' AND endDate > '$endDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $sqlDisp = "SELECT * FROM rents WHERE id_room = $roomID AND startDate >= '$startDate' AND endDate < '$endDate';";
    $resultDisp = mysqli_query($conn, $sqlDisp);
    $isAvailable &= (mysqli_num_rows($resultDisp) == 0);

    $startDate = new DateTime($_GET['startDate']);
    $endDate = new DateTime($_GET['endDate']);
    $nights = $endDate->diff($startDate)->format("%a");

    if($isAvailable == false){
        header("Location: ../index.php");
        exit();
    }

    $sql = "SELECT id FROM visitors WHERE id_user = $idSession AND id_room = $roomID;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        $sql = "INSERT INTO visitors (id_user, id_room) VALUES ($idSession, $roomID);";
        $result = mysqli_query($conn, $sql);
    }   

    $sql = "SELECT accesses FROM rooms WHERE id = $roomID;";
    $result = mysqli_query($conn, $sql);
    $result = $result->fetch_object();
    $numberAccesses = $result->accesses + 1;

    $sql = "UPDATE rooms SET accesses = $numberAccesses WHERE id = $roomID;";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT id FROM rents WHERE id_room = $roomID;";
    $result = mysqli_query($conn, $sql);
    $numberRents = mysqli_num_rows($result);

    $sql = "SELECT id FROM feedbacks WHERE id_room = $roomID;";
    $result = mysqli_query($conn, $sql);
    $numberFeedbacks = mysqli_num_rows($result);

    $filename = "visitors.txt";
    $json = file_get_contents($filename);
    $json = json_decode($json);

    if(!isset($json->$roomID))
        $json->$roomID = new stdClass();
    $ipUser = $_SERVER['REMOTE_ADDR'];
    $json->$roomID->$ipUser = true;
    $numberVisitors = count((array)$json->$roomID);

    $json = json_encode($json);
    file_put_contents($filename, $json);

    $staticInformation = "$numberAccesses accesses &#x2022; $numberVisitors visitors &#x2022; $numberRents rents &#x2022; $numberFeedbacks feedbacks ";
?>

<!DOCTYPE html>

<html>
    
    <head>
    
        <title>Room</title>

        <link rel="stylesheet" href="../static/css/room/style.css">
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
                        <div class="menuButton" onclick="window.location.href = '../profile/index.php'">
                            <div class="textButton">
                                Profile
                            </div>
                        </div>

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
							<form class="formCurrency" method="POST" action="../changeExchangeCurrency.php">
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

			</div>

            <div class="titleDiv">
                Main information <i class="arrow right"></i>
            </div>

            <?php
                $sql = "SELECT * FROM rooms WHERE id = $roomID;";
				$result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) == 0){
                    header("Location: ../index.php");
                    exit();
                }
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

                $sqlFeedback = "SELECT AVG(note) as SumNote FROM feedbacks WHERE id_room = $result->id";
                $resultFeedback = mysqli_query($conn, $sqlFeedback);
                $resultFeedback = $resultFeedback->fetch_object();
                $rating = intval($resultFeedback->SumNote * 100) / 100;
                if($rating == 0)
                    $rating = "No feedback";

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

                            <div class="rentRoom">
                                <form action="rentRoom.php" method="POST">
                                    <input type="number" name="idRoom" style="display: none;" value="<?php echo $roomID; ?>">
                                    <input type="date" name="startDate" style="display: none;" value="<?php echo $_GET['startDate']; ?>">
                                    <input type="date" name="endDate" style="display: none;" value="<?php echo $_GET['endDate']; ?>">
                                    <button type="submit">Reserve now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="needLogin">
                        <div>
                            <?php echo $staticInformation; ?> 
                        </div>
                    </div>
                </div>
            </div>

            <?php
                $sql = "SELECT * FROM feedbacks WHERE id_room = $roomID;";
				$resultQuery = mysqli_query($conn, $sql);

                $strFeedback = "Feedback";
                if(mysqli_num_rows($resultQuery) == 0){
                    $strFeedback = "No feedback given until this point";
                }
            ?>

            <div class="titleDiv">
                <?php
                    $sql = "SELECT * FROM feedbacks WHERE id_room = $roomID;";
                    $resultQuery = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($resultQuery) == 0){
                        echo "No feedback given until this point!";
                    } else {
                        echo "Feedback <i class=\"arrow right\"></i>";
                    }
                ?>
            </div>

            <?php
                while($result = $resultQuery->fetch_object()){
                    $idUser = $result->id_user;
                    $note = $result->note;
                    $description = $result->description;

                    $sqlGetName = "SELECT fname, lname FROM users WHERE id = $idUser;";
                    $resultGetName = mysqli_query($conn, $sqlGetName);
                    $resultGetName = $resultGetName->fetch_object();
                    $name = $resultGetName->fname . " " . $resultGetName->lname;
            ?>

                <div class="feedback">
                    <div class="user">
                        <div class="profileImage">
                            <img src="../img/profiles/id_<?php echo $idUser; ?>/main.jpg">
                        </div>

                        <div class="username">
                            <?php echo $name; ?>
                        </div>
                    </div>

                    <div class="opinion">
                        <div class="note">
                            <?php echo $note; ?>⭐ / 10⭐
                        </div>

                        <div class="description">
                            <?php echo $description; ?>
                        </div>
                    </div>
                </div>

            <?php
                }
            ?>

            <div class="marginBottomDiv"> </div>
        </div>
    </body>

</html>