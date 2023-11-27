<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "3s@1bbaaefb";
$dbname = "bibliotech";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête pour obtenir des données par exemple
$sql = "SELECT * FROM livres";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
} else {
    echo "0 results";
}
$conn->close();
?>
