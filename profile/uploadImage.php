<?php
    session_start();

    if (isset($_POST['submit']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileType = exif_imagetype($fileTmpName);
        $isOfThisType = ($fileType === IMAGETYPE_JPEG || $fileType === IMAGETYPE_PNG);

        $fileExt = explode('.', $fileName);
        $fileExt = strtolower(end($fileExt));
        $allowedExtensions = array('jpg', 'jpeg', 'png');
        $isValidExt = in_array($fileExt, $allowedExtensions);

        if ($isOfThisType && $isValidExt) {
            $dirName = "id_" . $_SESSION['id'] . "/";
            $dirPath = "../img/profiles/" . $dirName;

            if(!is_dir($dirPath))
                mkdir($dirPath, true);

            $fileDestination = $dirPath . 'main.jpg';
            move_uploaded_file($fileTmpName, $fileDestination);

            $_SESSION['message'] = "Image was uploaded!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "File needs to be a image!";
            header("Location: index.php");
            exit();
        }
    } else {
        header("Location: index.php");
        exit();
    }
   
?>