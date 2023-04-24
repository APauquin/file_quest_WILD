<?php
require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'public/uploads/';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $authorizedExtensions = ['jpg', 'webp', 'png', 'gif'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 1000000;
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg, Gif, Webp ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }

    // PARTIE OPTIONNEL POUR RAJOUTER LE NOM ET LADDRESS DANS LE FORMULAIRE //
    // nettoyage et validation des données soumises via le formulaire 
    if (empty($name) || strlen($name) >= 45)
        $errors[] = "Name is required and must be less than 45 characters";
    if (empty($address) || strlen($address) >= 255)
        $errors[] = "Address is required and must be less than 255 characters";
    if (empty($errors)) {
        $query = 'INSERT INTO homer (name, address, uploadFile) VALUES (:name, :address, :uploadFile)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':name', $name, \PDO::PARAM_STR);
        $statement->bindValue(':address', $address, \PDO::PARAM_STR);
        $statement->bindValue(':uploadFile', $uploadFile, \PDO::PARAM_STR);

        $statement->execute();
        // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ça y est, le fichier est uploadé
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);

        header('Location: header.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form method="post" enctype="multipart/form-data">
        <label for="imageUpload">Upload an profile image</label>
        <input type="file" name="avatar" id="imageUpload" />
        <label for="name">Name</label>
        <input type="text" name="name" id="name" />
        <label for="address">Address</label>
        <input type="text" name="address" id="address" />
        <button name="send">Send</button>
    </form>

</body>

</html>