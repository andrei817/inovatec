<?php
// Conexão com o banco de dados
 include("php/Config.php");

// Receber os dados do formulário
$data = $_POST['data'];
$status = $_POST['status'];  // Recebe os status de presença de cada staff

// Inserir presença para cada staff
foreach ($status as $staff_id => $presenca) {
    // Verifica se o status é válido
    if ($presenca === 'presente' || $presenca === 'ausente') {
        $sql = "INSERT INTO presencas (staff_id, data, status) VALUES ('$staff_id', '$data', '$status')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($staff_id, $data, $presenca);

        if (!$stmt->execute()) {
            echo "Erro ao registrar presença para o staff ID $staff_id: " . $stmt->error . "<br>";
        }
    }
}

$stmt->close();
$conn->close();

echo "Presença registrada com sucesso!";
?>
