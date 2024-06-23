<?php 
$name = $email = $website="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
	$name=test_input($_POST["name"]);
	$course=test_input($_POST["course"]);
	$branch=test_input($_POST["branch"]);
	$email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
	
}
function test_input($data){
	$data=trim($data);
	$data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<?php
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $course;
echo "<br>";
echo $branch;
echo "<br>";
echo $email;
echo "<br>";
echo $password;
?>

<?php
// Create connection
$conn = new mysqli('localhost','root','','logindetails');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

if(isset($_POST['submit'])){
$name=$_POST["name"];
$course=$_POST["course"];
$branch=$_POST["branch"];
$email = $_POST["email"];
$password = $_POST["password"];	
$sql_query="insert into userdetails(name,course,branch,email,password) values('$name','$course','$branch','$email','$password')";

if(mysqli_query($conn,$sql_query))
{
	
	echo "success";
}
else {
	
	echo "not sucess";
 }	
}
?> 