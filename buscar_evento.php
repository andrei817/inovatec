<?php
include("php/Config.php");
$sql = "SELECT * FROM eventos WHERE nome = 'Dia das Mulheres'"; // Altere conforme o nome do evento ou outros filtros
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nome = $row["nome"];
        $data = $row["data"];
        $horario = $row["horario"];
        $local = $row["local"];
        $descricao = $row["descricao"];
    }
} else {
    echo "Nenhum evento encontrado";
}
?>
