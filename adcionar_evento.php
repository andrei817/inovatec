<?php
include ("php/Config.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    // Pega os dados do formulário

    // Processa a imagem
    $imagem = $_FILES['imagem']['name']; // Nome original do arquivo
    $imagem_tmp = $_FILES['imagem']['tmp_name']; // Local temporário do arquivo
    $imagem_destino = 'uploads/' . $imagem; // Caminho para salvar a imagem

    // Move a imagem para a pasta 'uploads'
    if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
        // Insere o evento no banco de dados, incluindo o caminho da imagem
        $sql = "INSERT INTO eventos (imagem) VALUES ('$imagem_destino')";

        if (mysqli_query($conn, $sql)) {
            //echo "Evento adicionado com sucesso!";
        } else {
          //  echo "Erro ao adicionar evento: " . mysqli_error($conn);
        }
    } else {
      //  echo "Erro ao fazer upload da imagem.";
    }

    mysqli_close($conn);
}
?>
