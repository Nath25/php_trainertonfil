<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<?php
//Supprimer une image
if(isset($_POST['delete'])){
    if (file_exists ( "upload/".$_POST['image'] )){
        unlink("upload/".$_POST['image']);
        header('location: index.php');
    }
}
?>

<?php

$dossier = "upload/";
if(!is_dir($dossier)){
   mkdir($dossier);
}
$uploadOk = 1;

$target_dir = "upload/";
if(isset($_POST['submit'])){
    if(count($_FILES['upload']['name']) > 0){
        $errors     = array();
        //Pour chaque fichier
        for($i=0; $i<count($_FILES['upload']['name']); $i++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            $target_file = $target_dir . basename($_FILES['upload']["name"][$i]);

            if($tmpFilePath != ""){
                // Est-ce que le fichier existe ?
                if (file_exists($target_file)) {
                    echo "Le fichier existe déjà";
                    $uploadOk = 0;

                }

                //Test du poids de l'image
                if ($_FILES["upload"]["size"][$i] > 1048576 || ($_FILES["upload"]["size"] == 0)) {
                    $errors[]= "Votre fichier est trop lourd";
                    $uploadOk = 0;
                }
                //Test de l'extension de l'image
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif") {
                    $errors[]= "Seuls les fichers jpg, png et gif sont acceptés.";
                    $uploadOk = 0;
                }

                if (count($errors)==0){
                    //Renommer image
                    $index=time();
                    $shortname = "image"."$index"."$i".".".$imageFileType;

                    //save the url and the file
                    $filePath = $target_dir.$shortname ;
                    $uploadOk = 1;
                    //Upload the file into the temp dir
                    (move_uploaded_file($tmpFilePath, $filePath));
                }
                else {
                    foreach ($errors as $error) {
                        echo $error;
                    }
                }


                }
              }
        }
    }
    $files = array_diff(scandir($target_dir),array('..', '.'));


    include 'form.html';
    if (isset($files)) {
          if(is_array($files)){
              foreach ($files as $file){
                  echo'<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                  	<div class="thumbnail">
                  		<img src=upload/'.$file.' >
                  		<div class="caption">
                  			<h3>'.$file.'</h3>

                  			<form action="" method="post" role="form">
                  					<input type="hidden"  name="image" value='.$file.' >
                  					<input type="submit" class="btn-danger" name="delete" value="delete">
                  			</form>
                  		</div>
                  	</div>
                  </div>';
              }
          }
      }
?>
