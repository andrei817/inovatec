<?php
// Conexão com o banco de dados
include("php/Config.php");

// Variável para armazenar mensagem de sucesso ou erro
$msg = "";

// Configuração da paginação
$temas_por_pagina = 3;  // Quantos temas serão exibidos por página
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $temas_por_pagina;

// Adicionar novo tema
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $sql = "INSERT INTO tema (nome, descricao) VALUES ('$nome', '$descricao')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Tema adicionado com sucesso!";
    } else {
        $msg = "Erro ao adicionar tema: " . $conn->error;
    }
}

// Excluir tema
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);  // Garantir que o ID seja um número inteiro
    $sql = "DELETE FROM tema WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $msg = "Tema excluído com sucesso!";
        // Redirecionar para recarregar a página após exclusão
        header("Location: tema lista.php");
        exit();
    } else {
        $msg = "Erro ao excluir tema: " . $conn->error;
    }
}

// Buscar temas com paginação
$sql = "SELECT * FROM tema LIMIT $temas_por_pagina OFFSET $offset";
$result = $conn->query($sql);

// Contar o número total de temas
$sql_total = "SELECT COUNT(*) AS total FROM tema";
$total_result = $conn->query($sql_total);
$total_temas = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total_temas / $temas_por_pagina);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tema lista.css">
    <title>Gerenciar Temas</title>
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

<section class="tema-evento">
    <div class="conteudo">
        <h2>TEMAS</h2>
        <a href="tema cadastro.php" class="button">Adicionar Novo Tema</a>
    </div>

     

    <?php if ($msg != ""): ?>
        <div class="msg"><?= $msg ?></div> <!-- Exibe a mensagem de sucesso ou erro -->
    <?php endif; ?>

    <table>
        <tr>
            <th class="id-column">ID</th>
            <th>Nome do Tema</th>
            <th>Descrição</th>
            <th class="ações">Ações</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="id-column"><?= $row['id'] ?></td>
                <td><?= $row['nome'] ?></td>
                <td><?= $row['descricao'] ?></td>
                <td class="action">
                    <a href="editar tema.php?id=<?= $row['id'] ?>">
                        <button class='btn-edit'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                                <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                                <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
                            </svg>
                        </button>
                    </a>
                    <a href="delete tema.php?excluir=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este tema?');">
                        <button class="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        </button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Nenhum tema encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Paginação -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="<?= ($pagina_atual == $i) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    
</section>

<script> 
// Função para abrir a sidebar
function abrirSidebar() {
  document.getElementById("mySidebar").style.width = "250px";
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

// Função para abrir a sidebar
function abrirSidebar() {
  if (window.innerWidth <= 768) {
      document.getElementById("mySidebar").style.width = "100%";
  } else {
      document.getElementById("mySidebar").style.width = "310px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}
</script>


</body>
</html>

<?php
// Fechar conexão
$conn->close();
?>
