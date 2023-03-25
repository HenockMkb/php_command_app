
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commandes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <h1>Commandes</h1>
        <form method="post">
            <div class="form-group">
                <label for="nom_client">Nom du client</label>
                <input type="text" class="form-control" id="nom_client" name="nom_client" required>
            </div>
            <div class="form-group">
                <label for="plat">Plat commandé</label>
                <select class="form-control" id="plat" name="plat" required>
                    <option value="">Choisissez un plat</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Pâtes">Pâtes</option>
                    <option value="Salade">Salade</option>
                    <option value="Burger">Burger</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" class="form-control" id="quantite" name="quantite" required>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="emporter" name="emporter">
                    <label class="form-check-label" for="emporter">
                        A emporter
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>

</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resto";

// Connexion à la base de données
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Vérification de la connexion
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs des champs du formulaire
    $nom_client = test_input($_POST["nom_client"]);
    $plat = test_input($_POST["plat"]);
    $quantite = test_input($_POST["quantite"]);
    $emporter = isset($_POST["emporter"]) ? 1 : 0;

    // Insertion des données dans la base de données
    $sql = "INSERT INTO commandes (nom_client, plat, quantite, emporter) VALUES ('$nom_client', '$plat', '$quantite', '$emporter')";

    if (mysqli_query($conn, $sql)) {
        // Redirection vers une nouvelle page pour éviter la répétition des actions lors de l'actualisation
        header("Location: index.php");
        exit(); // Assurez-vous de terminer le script après la redirection
    } else {
        echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
    }
}


// Fonction pour nettoyer les données du formulaire
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Récupération des commandes dans la base de données
$sql = "SELECT * FROM commandes";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<div class="container mt-5">';
    echo '<h2>Liste des commandes</h2>';
    echo '<table class="table table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Nom du client</th>';
    echo '<th>Plat commandé</th>';
    echo '<th>Quantité</th>';
    echo '<th>À emporter</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Parcours des résultats de la requête
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['nom_client'] . '</td>';
        echo '<td>' . $row['plat'] . '</td>';
        echo '<td>' . $row['quantite'] . '</td>';
        echo '<td>' . ($row['emporter'] ? 'Oui' : 'Non') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo "Aucune commande enregistrée.";
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>

