<?php 
	session_start(); 
	include "db_conn.php";

	function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

	$isOk = true;
	$nights = 1;
	if(isset($_GET['startDate']) && isset($_GET['endDate'])) {
		$isOk = (!empty(validateDate($_GET['startDate'])) && !empty(validateDate($_GET['endDate'])));
		if($isOk){
			$startDate = new DateTime($_GET['startDate']);
    		$endDate = new DateTime($_GET['endDate']);
			if($startDate > $endDate)
				$isOk = false;
    		$nights = $endDate->diff($startDate)->format("%a");

			$startDate = $_GET['startDate'];
			$endDate = $_GET['endDate'];
		}
	} else if(isset($_GET['startDate']) || isset($_GET['endDate'])){
		$isOk = false;
	} 

    if(!$isOk){
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>

		<link rel="stylesheet" href="static/css/style.css">
	</head>

	<body>

		<div class="content">

			<div class="topPage">
				<img src="img/bkImage.png" class="bkImage">

				<div class="topMenu">

					<div class="logo">
						Hotel Maria
					</div>

					<div class="optionsMenu">
						<?php 
							if(isset($_SESSION['id'])){
						?>	
							<div class="menuButton" onclick="window.location.href = 'profile/index.php'">
								<div class="textButton">
									Profile
								</div>
							</div>
						<?php 
							} else {
						?>
							<div class="menuButton" onclick="window.location.href = 'login/index.php'">
								<div class="textButton">
									Sign in
								</div>
							</div>

							<div class="menuButton" onclick="window.location.href = 'register/index.php'">
								<div class="textButton">
									Register
								</div>
							</div>
						<?php
							}
						?>

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
							<img src="img/flagRo.png" class="flagLang">
						</div>

						<div class="currency">
							RON
						</div>
						
					</div>

				</div>

				<div class="aboutYou">
					<div class="motto">
						Don’t just dream of a vacation. Just do it!
					</div>

					<div class="searchMotto">
						Search deals that fit you.
					</div>
				</div>

				<div class="searchMenu">

					<div class="checkInOut">
						<div>
							Check-in
						</div>

						<input id="startDate" type="date" name="startDate" required>
					</div>

					<div class="checkInOut">
						<div>
							Check-out
						</div>

						<input id="endDate" type="date" name="endDate" required>
					</div>

					<div class="submitInput" onclick="changePeriod()">
						<div>
							Submit
						</div>
					</div>
				</div>

			</div>

			<?php

				$sql = "SELECT * FROM rooms;";
				$resultQuery = mysqli_query($conn, $sql);
				while($result = $resultQuery->fetch_object()){
					$imagePath = "img/rooms/id_" . $result->id . "/1.jpg";
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
				
					$price = ($result->price * $nights) . " lei";
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
											<?php if($nights == 1) echo "1 night"; else echo $nights. " nights"; ?> , <?php echo $capacityRoom ?> persons
										</div>
									</div>

									<?php
										if(isset($startDate) && isset($endDate))
											$pathRoom = "room/index.php?id=" . (intval($result->id / 10) + 1) . "&startDate=" . $startDate . "&endDate=" . $endDate;
										else $pathRoom = "index.php";
									?>

									<div class="rentRoom" onclick="window.location.href = '<?php echo $pathRoom; ?>'">
										<div>
											<?php
												if(isset($startDate) && isset($endDate))
													echo "See details";
												else echo "Select a period";
											?>
										</div>
									</div>
								</div>
							</div>
							<?php 
								if(!isset($_SESSION['id'])){
							?>
								<div class="needLogin">
									<div>
										Get an exclusive discount and see availability - Sign in
									</div>
								</div>
							<?php 
								} else {
									$sql = "SELECT isAdmin FROM permission WHERE id_user = '$idUser';";
									$result = mysqli_query($conn, $sql);
									$result = $result->fetch_object();

									if($result->isAdmin){
							?>
								<div class="deleteRoom">
									<a href="">
										Delete room
									</a>
								</div>
							<?php
									}
								}
							?>
						</div>
					</div>
				<?php
				}
			?>

			<?php 
				if(isset($_SESSION['id'])){
			?>

			<div class="contact">
				Do you have any problems or you want to tell us something? <br>
				<a href="contact/index.php">Contact us</a>
			</div>

			<?php
				}
			?>

		</div>
		
		<script src="static/js/script.js"></script>
	</body>
</html>