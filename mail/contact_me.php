<?php

$servername = "localhost";
$username = "kolliseu_yngrdyn";
$password = "fueradc18m";
$dbname = "kolliseu_pge";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	return "NOT Connected successfully";
} 

$name = strip_tags(htmlspecialchars($_POST['name']));
	$email_address = strip_tags(htmlspecialchars($_POST['email']));
	$phone = strip_tags(htmlspecialchars($_POST['phone']));
	$message = strip_tags(htmlspecialchars($_POST['message']));

$sql = "SELECT email FROM waitList where email LIKE '%$name%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "You are already registered";
	$conn->close();
	return "You are already registered";
} else {
    $sql = "INSERT INTO waitList (email)
	VALUES ('" . $_POST['name'] . "')";

	if ($conn->query($sql) === TRUE) {
		echo "Thank you! We will send you an email shortly.";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	// Check for empty fields
	if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }

	$to = "$email_address";
	$subject = "Welcome to Kolliseum";
	// Get HTML contents from file
	$htmlContent = file_get_contents("email_template.html");

	// Set content-type for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// Additional headers
	$headers .= 'From: Kolliseum<noreply@kolliseum.com>' . "\r\n";

	// Send email
	if(mail($to,$subject,$htmlContent,$headers)){
		$successMsg = 'Email has sent successfully.';
	}
	else{
		$errorMsg = 'Some problem occurred, please try again.';
	}
	        
}

$conn->close();
return true; 

?>