<?php
session_start();

include_once "db_config.php";
if(isset($_FILES["file"]["type"]))
{
$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $_FILES["file"]["name"]);
$file_extension = end($temporary);
if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
) && ($_FILES["file"]["size"] < 500000)//Approx. 100kb files can be uploaded.
&& in_array($file_extension, $validextensions)) {
if ($_FILES["file"]["error"] > 0)
{
echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
}
else
{
if (file_exists("upload/" . $_FILES["file"]["name"])) {
echo $_FILES["file"]["name"] . " <span id=''><b>already exists.</b></span> ";
}
else
{
$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
$targetPath = "upload/".$_SESSION['g_UR_id'].'.'.$file_extension; // Target path where file is to be stored
$imagename=$_SESSION['g_UR_id'].'.'.$file_extension;

$done= move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

if($done){
$query="Update Users set UR_image='".$imagename."' where UR_id='".$_SESSION['g_UR_id']."'";
mysql_query($query);

}

echo "<div style='margin-bottom: -17px;' class='alert alert-success'><span class='glyphicon glyphicon-ok-circle' style='color: rgba(60, 118, 61, 0.88);' ></span> Picture Updated Succesfully!!</div><br/>";
}
}
}
else
{
echo "<span id='invalid'>***Invalid file Size or Type***<span>";
}
}
?>