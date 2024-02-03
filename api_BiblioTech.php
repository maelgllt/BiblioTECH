<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function connectToDatabase($dbname, $username, $password){
    $servername = "localhost";

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Définir l'encodage des caractères
    $conn->set_charset("utf8");

    return $conn;
}

function getData($table, $connection){
    // Endpoint pour obtenir tous les éléments de la table spécifiée
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT * FROM " . $table . ";";
        $result = $connection->query($sql);

        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array());
        }
    }
}

function signup($connection, $data) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $username = $data['username'];
        $password = $data['password'];
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $email = $data['email'];
        $telephone = $data['telephone'];
        $adresse = $data['adresse'];

        // Valider le mot de passe
        if (!isPasswordValid($password)) {
            echo json_encode(array('error' => 'Le mot de passe doit contenir au moins 8 caractères avec une lettre majuscule, une lettre minuscule et un caractère spécial.'));
            return;
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Utilisation de requêtes préparées pour éviter les attaques par injection SQL
        $stmt = $connection->prepare("INSERT INTO utilisateurs (nom, mdp_hache, prenom, username, email, num_tel, adresse) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssss", $nom, $hashedPassword, $prenom, $username, $email, $telephone, $adresse);

        if ($stmt->execute()) {
            echo json_encode(array('message' => 'Inscription réussie'));
        } else {
            echo json_encode(array('error' => 'Erreur lors de l\'inscription'));
        }

        $stmt->close();
    }
}

// Fonction pour valider le mot de passe
function isPasswordValid($password) {
    // Au moins 8 caractères, une majuscule, une minuscule, un caractère spécial
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/', $password);
}

function login($connection, $data){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $username = $data['username'];
        $password = $data['password'];

        // Récupérer le mot de passe haché et le nom d'utilisateur de la base de données
        $sql = "SELECT mdp_hache, username FROM utilisateurs WHERE username='$username'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPasswordFromDB = $row['mdp_hache'];
            $storedUsername = $row['username'];

            // Vérification du nom d'utilisateur et du mot de passe
            if ($username === $storedUsername && password_verify($password, $hashedPasswordFromDB)) {
                echo true;
            } else {
                echo false;
            }
        } else {
            echo false;
        }
    }
}

function addDocument($connection, $data) {
    // Récupérer les données du formulaire
    $docType = $data['docType'];
    $docTitle = $data['docTitle'];
    $docAuthor = $data['docAuthor'];
    $docDate = $data['docDate'];

    // Utilisation de requêtes préparées pour éviter les attaques par injection SQL
    if ($docType === "Livre") {
        $stmt = $connection->prepare("INSERT INTO livres (titre, auteur) VALUES (?, ?)");
        $stmt->bind_param("ss", $docTitle, $docAuthor);
    } elseif ($docType === "Journal") {
        $stmt = $connection->prepare("INSERT INTO journaux (titre, date) VALUES (?, ?)");
        $stmt->bind_param("ss", $docTitle, $docDate);
    } elseif ($docType === "CD-ROM") {
        $stmt = $connection->prepare("INSERT INTO cdrom (titre, auteur) VALUES (?, ?)");
        $stmt->bind_param("ss", $docTitle, $docAuthor);
    } elseif ($docType === "Microfilms") {
        $stmt = $connection->prepare("INSERT INTO microfilms (titre, date) VALUES (?, ?)");
        $stmt->bind_param("ss", $docTitle, $docDate);
    } else {
        // Gérez d'autres types de documents si nécessaire
        echo json_encode(array('error' => 'Type de document non pris en charge'));
        return;
    }

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'Document ajouté avec succès'));
    } else {
        echo json_encode(array('error' => 'Erreur lors de l\'ajout du document'));
    }

    $stmt->close();
}


// Utilisation de la fonction connectToDatabase pour établir la connexion
$conn = connectToDatabase("bibliotech1", "root", "");

// Gérer les requêtes
if (isset($_GET['table'])) {
    getData($_GET['table'], $conn);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifier si l'action est une inscription, une connexion ou ajout d'un document
    if (isset($data['action'])) {
        if ($data['action'] === 'signup') {
            signup($conn, $data);
        } elseif ($data['action'] === 'login') {
            login($conn, $data);
        } elseif ($data['action'] === 'add_doc') {
            addDocument($conn, $data);
        } else {
            echo json_encode(array('error' => 'Action non valide'));
        }
    } else {
        echo json_encode(array('error' => 'Action manquante'));
    }
} else {
    echo json_encode(array('error' => 'Méthode de requête non gérée'));
}

$conn->close();
?>
