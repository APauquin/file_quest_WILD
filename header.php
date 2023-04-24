<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    require_once '_connec.php';

    $pdo = new \PDO(DSN, USER, PASS);

    $query = "SELECT * FROM homer";
    $statement = $pdo->query($query);
    $homerArray = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($homerArray as $homer) : ?>
        <img src="<?= $homer['uploadFile']; ?>" alt="">
        
        <tr>
            <td><?= $homer['name']; ?></td>
            <td><?= $homer['address']; ?></td>
        </tr>
    <?php endforeach; ?>
</body>

</html>