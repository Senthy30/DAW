<?php session_start(); include "db_conn.php";?>

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
							if(isset($_SESSION['id']) && isset($_SESSION['email'])){
						?>	
							<div class="menuButton" onclick="window.location.href = 'login/logout.php'">
								<div class="textButton">
									Profiles
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
							if(isset($_SESSION['id']) && isset($_SESSION['email']))
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
					<div class="typeInput">
						<input type="text" placeholder="Type of room">
					</div>

					<div class="checkInOut">
						<div>
							Check-in
						</div>

						<input type="date">
					</div>

					<div class="checkInOut">
						<div>
							Check-out
						</div>

						<input type="date">
					</div>

					<div class="numberInput">
						<div>
							People
						</div>

						<input type="number" min="0">
					</div>

					<div class="submitInput">
						<div>
							Submit
						</div>
					</div>
				</div>

			</div>

			<div class="adRoom">
				<div class="imageRoom">
					<img src="img/room.jpg">
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
									Reserve now
								</div>
							</div>
						</div>
					</div>

					<div class="needLogin">
						<div>
							Get an exclusive discount and see availability - Sign in
						</div>
					</div>
				</div>
			</div>

		</div>

	</body>
</html>