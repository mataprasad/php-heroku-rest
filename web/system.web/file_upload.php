<?php

function SAVE_UPLOADED_FILE($input_file_name, $target_file, $MAX_FILE_SIZE = 500000) {
//    $target_dir = "uploads/";
//    $target_file = $target_dir . basename($_FILES[$input_file_name]["name"]);
    $uploadOk = 1;
    $ext = pathinfo($_FILES[$input_file_name]["name"], PATHINFO_EXTENSION);
    $target_file = $target_file . "." . $ext;
    $target_file = strtolower($target_file);
    /*    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
      // Check if image file is a actual image or fake image
      if (isset($_POST["submit"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if ($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
      } else {
      echo "File is not an image.";
      $uploadOk = 0;
      }
      }

     */
// Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
        return "ERROR: Sorry, file already exists.";
    }
// Check file size
    if ($_FILES[$input_file_name]["size"] > $MAX_FILE_SIZE) {
        $uploadOk = 0;
        return "ERROR: Sorry, your file is too large.";
    }
    /*
      // Allow certain file formats
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
      }

     */
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return "ERROR: Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$input_file_name]["tmp_name"], $target_file)) {
            //echo "SUCCESS: The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            return pathinfo($target_file, PATHINFO_FILENAME);
        } else {
            return "ERROR: Sorry, there was an error uploading your file.";
        }
    }
}

