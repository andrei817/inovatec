<?php
session_start();
include("php/Config.php");

// Definir o número de registros por página
$registros_por_pagina = 4;

// Determinar a página atual (padrão: 1)
$pagina_atual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Consulta para obter o total de registros
$total_result = $conn->query("SELECT COUNT(*) AS total FROM cargos");
$total_row = $total_result->fetch_assoc();
$total_registros = $total_row['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta com LIMIT e OFFSET para a página atual
$sql = "SELECT * FROM cargos LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $registros_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="cargo.css">
    <title>Lista de Cargos de Staff</title>
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

  
<section class="cargo-evento">
     <div class="conteudo-cargo">
       <div class="nome-cargo">
    <h1>CARGOS</h1> 
       </div>
     <a href="adcionar_cargo.php" class="button">Adicionar Novo Cargo</a>
     </div>

    <table>
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th class="itens">Cargo</th>
                <th class="ações">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cargo = $result->fetch_assoc()): ?>
            <tr>
                <td class="id-column"><?php echo $cargo['id']; ?></td>
                <td><?php echo $cargo['nome']; ?></td>
                <td class="cargo-action">


                    <a class="a" href="edit cargo.php?id=<?php echo $cargo['id']; ?>">
                    
                    <button class='btn-edit-cargo'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
<path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
<path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
 </svg> </button>
</a> 

<a class="a" href="#" onclick="abrirModal(<?php echo $cargo['id']; ?>)">
            <button class='btn-delete-cargo'>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
              </svg> 
            </button>
          </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

   <!-- Modal de Confirmação -->
  <div id="confirmModal" class="modal-delete">
    <div class="modal-content-delete">
      <h2>Deseja excluir este cargo?</h2>
      <div class="modal-buttons">
        <button onclick="confirmarExclusao()" class="btn-confirm">Sim</button>
        <button onclick="fecharModalExclusao()" class="btn-cancel">Não</button>
      </div>
    </div>
  </div>

  <script> 
    let cargoIdToDelete; // Variável para armazenar o ID do cargo a ser excluído

    // Função para abrir o modal e armazenar o ID
    function abrirModal(id) {
      cargoIdToDelete = id; // Armazena o ID do cargo selecionado
      document.getElementById('confirmModal').style.display = 'flex'; // Exibe o modal
    }

    // Função para fechar o modal
    function fecharModalExclusao() {
      document.getElementById('confirmModal').style.display = 'none'; // Oculta o modal
    }

    // Função para confirmar a exclusão
    function confirmarExclusao() {
      // Redireciona para o script de exclusão com o ID armazenado
      window.location.href = 'delete cargo.php?id=' + cargoIdToDelete;
    }
  </script>



<div id="modalErro" class="modal-erro" style="display: none;">
    <div class="modal-content-erro">
        <h2 class="modal-title-erro">Erro</h2>
        <p id="modalMensagem" class="modal-message"></p>
        <span class="icon-close-erro" onclick="fecharModal()">&times;</span>
    </div>
</div>

<script>
    function exibirModal(mensagem) {
        document.getElementById('modalMensagem').innerText = mensagem;
        document.getElementById('modalErro').style.display = 'flex'; // Exibe o modal
    }

    function fecharModal() {
        document.getElementById('modalErro').style.display = 'none'; // Oculta o modal
    }

     // Exibir o modal se houver uma mensagem na sessão
     <?php if (isset($_SESSION['erro'])): ?>
            exibirModal("<?php echo $_SESSION['erro']; ?>");
            <?php unset($_SESSION['erro']); ?>
        <?php elseif (isset($_SESSION['mensagem'])): ?>
            ("<?php echo $_SESSION['mensagem']; ?>");
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

</script>


   
      <!-- Paginação -->
      <div class="pagination">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" class="<?php echo $pagina_atual == $i ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>


</section>
</body>
</html>
