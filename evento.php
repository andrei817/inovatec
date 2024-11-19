<?php
include('php/Config.php');

// CREATE (Cadastrar evento)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $nome = $_POST['nome'] ?? ''; 
    $tema_id = $_POST['tema_id'] ?? '';
    $objetivo_id = $_POST['objetivo_id'] ?? '';
    $buffet_id = $_POST['buffet_id'] ?? '';
    $data = $_POST['data'] ?? '';
    $local = $_POST['local'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $lotacao = $_POST['lotacao'] ?? '';
    $duracao = $_POST['duracao'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    // Validar se os campos obrigatórios estão preenchidos
    if ($nome && $data && $local && $tema_id && $objetivo_id && $buffet_id) {
        // Usar prepared statement para evitar SQL Injection
        $stmt = $conn->prepare("INSERT INTO eventos (nome, tema_id, objetivo_id, buffet_id, data, local, hora, lotacao, duracao, descricao) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Corrigido: Passando 10 parâmetros no bind_param()
        $stmt->bind_param("ssssssssss", $nome, $tema_id, $objetivo_id, $buffet_id, $data, $local, $hora, $lotacao, $duracao, $descricao);

        // Executar e verificar se foi bem-sucedido
        if ($stmt->execute()) {
            echo "Evento cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar evento: " . $stmt->error;
        }
    } else {
        echo "Por favor, preencha os campos obrigatórios (nome, data e local).";
    }
}

// UPDATE (Atualizar evento)
if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $tema_id = $_POST['tema_id'];
    $objetivo_id = $_POST['objetivo_id'];
    $buffet_id = $_POST['buffet_id'];
    $data = $_POST['data'];
    $local = $_POST['local'];
    $hora = $_POST['hora'];
    $lotacao = $_POST['lotacao'];
    $duracao = $_POST['duracao'];
    $descricao = $_POST['descricao'];

    // Usar prepared statement para segurança
    $stmt = $conn->prepare("UPDATE eventos SET nome = ?, tema_id = ?, objetivo_id = ?, buffet_id = ?, data = ?, local = ?, hora = ?, lotacao = ?, duracao = ?, descricao = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $nome, $tema_id, $objetivo_id, $buffet_id, $data, $local, $hora, $lotacao, $duracao, $descricao, $id);

    if ($stmt->execute()) {
        echo "Evento atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar evento: " . $stmt->error;
    }
}

// DELETE (Excluir evento)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Usar prepared statement para segurança
    $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Evento deletado com sucesso!";
    } else {
        echo "Erro ao deletar evento: " . $stmt->error;
    }
}

// READ (Ler eventos cadastrados)
$sql = "SELECT * FROM eventos ORDER BY data DESC";
$result = $conn->query($sql);

// Fechar a conexão apenas ao final
$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="evento.css">
    <title>SGE - Cadastrar Eventos</title>

</head>

<body>

<!-- Carregando menu -->
<div id="header"></div> <!-- Div onde o menu será injetado -->
<script>
  fetch('/menu principal.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<!-- Conteúdo principal -->
<div class="content">
    <div class="agenda-evento">
    <div class="conteudo">
        <!-- Formulário de Cadastro de Evento -->
        <div class="form-container">
            <h2>Cadastrar Evento</h2>
            <a href="lista de eventos.php" class="close-btn-evento">&times;</a>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="nome">Nome do Evento:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="input-group">
                <label for="tema_id">Selecione o Tema:</label>
        <select name="tema_id" id="tema_id" required>
            <option value="">Escolha um tema</option> 
            <?php
            include("php/Config.php");
            $sql = "SELECT tema.id, tema.nome FROM tema";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum tema encontrado</option>";
            }
            $conn->close();
            ?>
        </select>
    </div>

    <div class="input-group">
        <label for="objetivo_id">Selecione o Objetivo:</label>
        <select name="objetivo_id" id="objetivo_id" required>
            <option value="">Escolha um objetivo</option> 
            <?php
            include("php/Config.php");
            $sql = "SELECT objetivo.id, objetivo.nome FROM objetivo";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum objetivo encontrado</option>";
            }
            $conn->close();
            ?>
        </select>
    </div>

    <!-- Adicionei os campos de relacionamento com buffets da mesma maneira -->
    <div class="input-group">
        <label for="buffet_id">Selecione o Buffet:</label>
        <select name="buffet_id" id="buffet_id" required>
            <option value="">Escolha um buffet</option>
            <?php
            include("php/Config.php");
            $sql = "SELECT buffet.id, buffet.nome FROM buffet";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum buffet encontrado</option>";
            }
            $conn->close();
            ?>
        </select>
    </div>

                <div class="input-group">
                    <label for="data">Data:</label>
                    <input type="date" id="data" name="data" required>
                </div>

                <div class="input-group">
                    <label for="local">Local:</label>
                    <input type="text" id="local" name="local" required>
                </div>

                <div class="input-group">
                    <label for="hora">Hora:</label>
                    <input type="time" id="hora" name="hora" required>
                </div>

                <div class="input-group">
                    <label for="lotacao">Lotação:</label>
                    <input type="text" id="lotacao" name="lotacao" required>
                </div>

                <div class="input-group">
                    <label for="duracao">Duração:</label>
                    <input type="time" id="duracao" name="duracao" required>
                </div>

                <div class="input-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="3" placeholder="Descrição do Evento" required></textarea>
                </div>
      
                <button type="submit" class="login-btn-evento">Cadastrar</button>
                <a href="lista de eventos.php"><button type="button" class="Cancel-btn-evento">Cancelar</button></a>
            </form>
        </div>

    </section>
</div>

<script>
    // Funções para abrir e fechar a sidebar
    function abrirSidebar() {
        document.getElementById("mySidebar").style.width = "250px";
    }

    function fecharSidebar() {
        document.getElementById("mySidebar").style.width = "0";
    }
</script>

</body>
</html>


