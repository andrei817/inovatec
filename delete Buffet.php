<?php
include("php/Config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM buffet WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: lista de buffet.php");
        exit;
    } else {
        echo "Erro ao excluir o buffet: " . mysqli_error($conn);
    }

}

mysqli_close($conn);
?>