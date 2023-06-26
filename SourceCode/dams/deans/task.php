 <?php 
$conn = new mysqli("localhost", "root", "", "dams");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $deans = isset($_POST['dean']) ? $_POST['dean'] : null;
    $department = isset($_POST['department']) ? $_POST['department'] : null;
    $taskOwner = 'Office of Vice Chancellor of Academic Affairs';

    $sql = "INSERT INTO createTask (taskName, task_owner, description, dateStart, dateEnd, deans, department) VALUES ('OPCR', '$taskOwner', '$description', '$dateStart', '$dateEnd', '$deans', '$department')";
    $result = $conn->query($sql);

    if ($result) {
        header("Location: deans.php?success=TaskUploaded");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
                                           
   ?>