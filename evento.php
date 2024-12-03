<?php
include('php/Config.php');

$cadastroSucesso = false;

function normalizarNomeArquivo($nomeArquivo) {
    $nomeSemAcentos = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeArquivo);
    $nomeNormalizado = preg_replace('/[^A-Za-z0-9._-]/', '_', $nomeSemAcentos);
    return $nomeNormalizado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura e validação dos dados do formulário
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
    $imagem = $_FILES['imagem'] ?? null;

    if ($nome && $data && $local && $tema_id && $objetivo_id && $buffet_id) {
        $nomeImagem = null;

        if ($imagem && $imagem['error'] == UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $tamanhoMaximo = 2 * 1024 * 1024;
        
            if (!in_array($extensao, $extensoesPermitidas)) {
                die("Formato de arquivo inválido.");
            }
        
            if ($imagem['size'] > $tamanhoMaximo) {
                die("O tamanho da imagem excede o limite de 2MB.");
            }
        
            $uploadDir = "uploads/eventos/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
        
            $nomeImagem = uniqid("evento_", true) . "_" . normalizarNomeArquivo($imagem['name']);
            $caminhoImagem = $uploadDir . $nomeImagem;
        
            if (!move_uploaded_file($imagem['tmp_name'], $caminhoImagem)) {
                die("Erro ao mover a imagem.");
            }
        }

        // Insere no banco apenas o nome da imagem
        $stmt = $conn->prepare("INSERT INTO eventos (nome, tema_id, objetivo_id, buffet_id, data, local, hora, lotacao, duracao, descricao, imagem) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $nome, $tema_id, $objetivo_id, $buffet_id, $data, $local, $hora, $lotacao, $duracao, $descricao, $nomeImagem);

        if ($stmt->execute()) {
            $cadastroSucesso = true;
        } else {
            echo "Erro ao cadastrar evento: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, preencha todos os campos obrigatórios.";
    }
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
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
            <form method="POST" action="" enctype="multipart/form-data">
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
                    <textarea id="descricao" name="descricao" rows="3" class="inputUser" placeholder="Descrição do Evento" required></textarea>
                </div>
                
<button type="submit" name="cadastrar" class="login-btn-evento">Cadastrar</button>
    <a href="lista de eventos.php"><button type="button" class="Cancel-btn-evento">Cancelar</button></a>
    
    <div class="input-group">
        <label for="imagem">Imagem do Evento:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>
         <div class="image-preview">
        <img id="image-preview" src="#" alt="Pré-visualização da Imagem" style="display:none; max-width: 200px; max-height: 200px;">
    </div>
    </div>
                
    
</form>

           

                        <!-- Modal de Sucesso -->
            <div id="modalSucesso" class="modal-correto">
                <div class="modal-content-correto">
                    <span class="close-icon" onclick="fecharModal()">&times;</span>
                    <h2>Evento Cadastrado com Sucesso!</h2>
                    <img src="correto.png" class="correto-img">
                
                </div>
            </div>

            <script>
                // Função para fechar o modal
                function fecharModal() {
                    document.getElementById("modalSucesso").style.display = "none";
                }

                // Exibe o modal se o cadastro foi bem-sucedido
                <?php if ($cadastroSucesso): ?>
                    document.getElementById("modalSucesso").style.display = "flex";
                    setTimeout(fecharModal, 3000); // Fecha automaticamente após 3 segundos
                <?php endif; ?>
            </script>

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


