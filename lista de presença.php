<?php

include ("php/Config.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Staff.css">
    <title>Document</title>
</head>
<body>
    
<div id="header"></div> <!-- Div onde o menu será injetado -->

<script>
  fetch('/menu principal.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<div class="content">
  <!-- Conteúdo da página -->
</div>


<h2>Marcar Presença de Staffs</h2>
    <form action="presença.php" method="post" class="form-staff">
        <label for="data" class="label-staff">Data:</label>
        <input type="date" name="data" id="data" class="input-staff" required><br>

        <h3>Lista de Staffs:</h3>
        <?php
        // Conexão com o banco de dados
        include("php/Config.php");

        // Buscar os staffs
        $sql = "SELECT * FROM staffs_eventos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<label>" . htmlspecialchars($row['nome']) . "</label><br>";
                echo "<select name='status[" . $row['id'] . "]' required><br>";
                echo "<option value='presente'>Presente</option>";
                echo "<option value='ausente'>Ausente</option>";
                echo "</select><br>";
            }
        } else {
            echo "Nenhum staff encontrado.";
        }

        $conn->close();
        ?>
        <button type="submit" class="staff-btn" >Registrar Presença</button>
    </form>




</body>
</html>