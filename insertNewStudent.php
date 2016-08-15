<?php

if(isset($_POST['data']) and !empty($_POST['data']))
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "dbname";

	$data = $_POST['data'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $stmt = $conn->prepare("INSERT INTO Students (first_name, last_name, group_number, birthday_date, email, ip) 
    VALUES (:first_name, :last_name, :group_number, :birthday_date, :email, :ip)");
    $stmt->bindParam(':first_name', $data['first_name']);
    $stmt->bindParam(':last_name', $data['last_name']);
    $stmt->bindParam(':group_number', $data['group_number']);
    $stmt->bindParam(':birthday_date', $data['birthday_date']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':ip', $ip);    

    if(isset($_SERVER['REMOTE_ADDR']))
    	$ip = $_SERVER['REMOTE_ADDR'];
    else $ip = NULL;

    $stmt->execute();

    echo "New records created successfully";
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$conn = null;	

}
else echo "Some error.";

?>