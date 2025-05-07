<?php
include("php/Config.php");

// Definir o número de registros por página
$registros_por_pagina = 4;  // Você pode ajustar este valor

// Determinar a página atual
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina_atual - 1) * $registros_por_pagina;

// Consultar o número total de registros
$total_registros_query = "SELECT COUNT(*) AS total FROM produtor";
$total_result = mysqli_query($conn, $total_registros_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_registros = $total_row['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consultar os registros para a página atual
$sql = "SELECT * FROM produtor LIMIT $inicio, $registros_por_pagina";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="listar produtor.css">
    <title>Lista de Produtores</title>
    
</head>
<body>

<div id="header"></div> <!-- Div onde o menu será injetado -->

<script>
  fetch('/menu principal.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<div class="content">
  <!-- Conteúdo da página -->
</div>

<script> 
    function abrirSidebar() {
    if (window.innerWidth <= 768) {
      document.getElementById("mySidebar").style.width = "100%";
    } else {
      document.getElementById("mySidebar").style.width = "310px";
    }
    // Adiciona a classe "aberto" à sidebar
    document.getElementById("mySidebar").classList.add("aberto");
  }

  // Função para fechar a sidebar
  function fecharSidebar() {
    document.getElementById("mySidebar").style.width = "0";
    // Remove a classe "aberto"
    document.getElementById("mySidebar").classList.remove("aberto");
  }

  // Adiciona o evento para fechar ao clicar fora da sidebar
  document.addEventListener('click', function (event) {
    const sidebar = document.getElementById("mySidebar");
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickOnButton = event.target.closest('.open-btn');

    // Fecha a sidebar se o clique não for nela nem no botão de abrir
    if (!isClickInsideSidebar && !isClickOnButton && sidebar.classList.contains("aberto")) {
      fecharSidebar();
    }
  });

  // Fecha a sidebar ao clicar nos links
  document.querySelectorAll('#mySidebar a').forEach(link => {
    link.addEventListener('click', fecharSidebar);
  });
   </script>


<script>
  // Função para mostrar/ocultar a lista suspensa do perfil
  function toggle() {
      var profileDropdownList = document.querySelector('.profile-dropdown-list');
      profileDropdownList.classList.toggle('active');
  }

  // Função para mostrar o modal de logout
  function showLogoutModal() {
      document.getElementById('logoutModal').style.display = 'flex';
  }

  // Função para fechar qualquer modal
  function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
  }

  // Função para confirmar o logout e mostrar o modal de agradecimento
  function confirmLogout() {
      closeModal('logoutModal'); // Fecha o modal de logout
      document.getElementById('thankYouModal').style.display = 'flex'; // Mostra o modal de agradecimento
      
      // Redireciona após alguns segundos (opcional)
      setTimeout(function() {
          window.location.href = 'index.php'; // Redireciona para a página inicial
      }, 2000); // Aguarda 2 segundos antes de redirecionar
  }
</script>




<section class="prod-evento">

     <div class="conteudo">
     <div class="nome-produtor">
    <h1>PRODUTORES</h1> 
     </div>
    <a href="cadastro produtor.php"  class="button" >Adicionar Novo Produtor</a>

     </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th class="id-column">ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th class="senha-column">Senha</th>
                    <th>CPF</th>
                    <th class="acao">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produtor = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="id-column"><?php echo $produtor['id']; ?></td>
                        <td><?php echo $produtor['nome']; ?></td>
                        <td><?php echo $produtor['email']; ?></td>
                        <td><?php echo $produtor['telefone']; ?></td>
                        <td class="senha-column"><?php echo $produtor['senha']; ?></td>
                        <td><?php echo $produtor['cpf']; ?></td>
                        <td class="actions">
  <a class="a" title="Editar" href="edit.php?id=<?php echo $produtor['id']; ?>"> <button class='btn-edit'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
  <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
  <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>  </svg> </button>
  </a>


  <a class="a" title="Deletar" href="#" onclick="openModal('delete prod.php?id=<?php echo $produtor['id']; ?>'); return false;">  <button class='btn-delete'> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
</svg>  </button></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Modal de Confirmação -->
<div id="confirmModal" class="modal-delete">
    <div class="modal-content-delete">
        <h2>Deseja excluir este produtor?</h2>
        <div class="modal-buttons">
            <button class="btn-confirm" onclick="confirmDelete()">Sim</button>
            <button class="btn-cancel" onclick="closeModalDelete()">Não</button>
        </div>
    </div>
</div>

<script>
let deleteUrl = ""; // Variável para armazenar o URL

function openModal(url) {
    deleteUrl = url; // Guarda o URL do botão clicado
    document.getElementById('confirmModal').style.display = 'flex'; // Mostra o modal
}

// Função chamada quando o usuário confirma a exclusão
function confirmDelete() {
    window.location.href = deleteUrl; // Redireciona para o link de exclusão
}

// Função para fechar o modal sem excluir
function closeModalDelete() {
    document.getElementById('confirmModal').style.display = 'none';
}
</script>



        <!-- Navegação da Paginação -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $pagina_atual) echo 'class="active"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>

    <?php else: ?>
        <p>Nenhum produtor de eventos cadastrado.</p>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>

</body>
</html>













