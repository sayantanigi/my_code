<?php
$image = $_POST['image'];
list($type, $image) = explode(';',$image);
list(, $image) = explode(',',$image);
$image = base64_decode($image);
$image_name = time().'.png';
file_put_contents('uploads/'.$image_name, $image);
include_once 'db.php';
$insert = $db->query("INSERT uploading (file_name) VALUES ('".$image_name."')");
echo 'successfully uploaded';
?>