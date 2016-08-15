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

    //get all students info
    $stmt = $conn->prepare("SELECT s.*, q.description 
                                FROM Students s LEFT JOIN qualifications q ON q.id_student = s.id"); 
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $students = $stmt->fetchAll();


    //get all students marks info
    $stmt = $conn->prepare("SELECT m.id_student, s.name, m.mark, m.semester_number 
                                FROM marks m INNER JOIN subjects s ON m.id_subject = s.id ORDER BY m.semester_number"); 
    $stmt->execute();    
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $marks = $stmt->fetchAll();    


    $dataArray = array($students, $marks);
    echo json_encode($dataArray);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
}
else echo "Some error.";

?>