<?php 
    session_start(); 
    include "../../db_conn.php";

    function sanitize_input($input) {
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    if(!isset($_SESSION['id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])){
        header("Location: ../index.php");
        exit();
    }

    $idSession = $_SESSION['id'];
    $idRoom = 1 + ($_GET['id'] - 1) * 10;
?>

<!DOCTYPE html>

<html>

    <head>

        <title>Login</title>

        <link rel="stylesheet" href="../../static/css/profile/feedback/style.css">
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
									Profile
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
                    Feedback
                </div>

                <div class="contentFeedback">
                    <?php
                        $sql = "SELECT * FROM rents WHERE id_user = $idSession AND id_room = $idRoom;";
                        $resultQuery = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($resultQuery) == 0){
                            header("Location: ../index.php");
                            exit();
                        }
                        $resultRents = $resultQuery->fetch_object();

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
                                </div>
                            </div>

                            <div class="needLogin">
                                <div>
                                    <?php echo "From: " . $resultRents->startDate . "   To: " . $resultRents->endDate; ?>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    <label> Note </label>
                                </div>
                                <input type="number" name="note" placeholder="Note" min=0 max=10 step="0.01" required>
                            </div>

                            <div>
                                <div class="labelDiv">
                                    <label> Description </label>
                                </div>
                                <textarea name="description" rows="6" required></textarea>
                            </div>

                            <input type="number" name="idRoom" value="<?php echo $idRoom; ?>" style="display: none;">

                            <div class="submitDiv">
                                <button type="submit" class="submitButton"> Submit feedback </button>
                            </div>
                        </form>
                    </div>
                </div>

			</div>

        </div>

    </body>

</html>