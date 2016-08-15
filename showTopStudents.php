<?php

if(isset($_POST['show']) and !empty($_POST['show']) and $_POST['show'] == true)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbname";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //get all top-10 students info
    $stmt = $conn->prepare("SELECT s.*, q.description 
                                FROM Students s LEFT JOIN qualifications q ON q.id_student = s.id ORDER BY s.average_mark DESC LIMIT 10"); 
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    
    echo json_encode($stmt->fetchAll());
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
}
else echo "Some error.";

?>