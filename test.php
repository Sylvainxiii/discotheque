<?php
include('includes/header.php');

if (isset($_FILES)) {
    //     $b = getimagesize($_FILES["userImage"]["tmp_name"]);
    //     //Vérifiez si l'utilisateur à sélectionné une image
    //     if ($b !== false) {
    //         //Récupérer le contenu de l'image
    $file = $_FILES['userImage']['tmp_name'];
    $image = base64_encode(file_get_contents($file));

    //Insérer l'image dans la base de données
    $sql = "UPDATE d_utilisateur_uti SET uti_avatar = :utiAvatar
           WHERE uti_id = 1";
    $stmt = $pdo->prepare($sql);
    $params = ["utiAvatar" => $image];
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
// }

$sql2 = "SELECT uti_avatar FROM `d_utilisateur_uti` WHERE uti_id = 1";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$result = $stmt2->fetch(PDO::FETCH_ASSOC);
$img = $result['uti_avatar'];
?>


<html>

<head>
    <title>Afficher une image de type BLOB en PHP</title>
</head>


<body>
    <form enctype="multipart/form-data" action="test.php" method="post">
        <label>Uploader le fichier image:</label><br />
        <input name="userImage" type="file" />
        <input type="submit" value="Uploader" />
    </form>
    <img src="data:image/jpeg;base64,<?= $img ?>" alt="">

</body>

</html>