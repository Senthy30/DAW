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

			<?php
				$idUser = -1;
				if(isset($_SESSION['id']) && isset($_SESSION['email']))
					$idUser = $_SESSION['id'];
				$sql = "SELECT isAdmin FROM permission WHERE id_user = '$idUser';";
				$result = mysqli_query($conn, $sql);
				$result = $result->fetch_object();

				if($idUser != -1 && $result->isAdmin == 1){ 
			?>
				<h1>
					Te iubesc cel mai mult, iubita mea!
				</h1>
			<?php
				}
			?>

		</div>

	</body>
</html>