<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
  <link rel="stylesheet" href="lista de buffet.css">
  <title>Lista de Buffets</title>
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
    document.addEventListener('click', function(event) {
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

  <?php

  include("php/Config.php");

  // Defina o número de buffets por página
  $buffetsPorPagina = 4;

  // Determine a página atual (padrão é 1)
  $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
  $offset = ($paginaAtual - 1) * $buffetsPorPagina;


  // Consulta SQL com LIMIT e OFFSET, agora com JOIN
  $sql = "
    SELECT b.*, t.descricao AS tipo_nome
    FROM buffet b
    LEFT JOIN buffet_tipo bt ON b.id = bt.buffet_id
    LEFT JOIN tipo t ON bt.tipo_id = t.id
    LIMIT $buffetsPorPagina OFFSET $offset
";

  $result = mysqli_query($conn, $sql);



  // Consulta para contar o total de registros
  $totalBuffets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM buffet"))['total'];
  $totalPaginas = ceil($totalBuffets / $buffetsPorPagina);
  ?>

  <section class="buffet-evento">
    <div class="conteudo-buffet">
      <div class="nome-buffet">
        <h1>BUFFETS</h1>
      </div>
      <a href="buffet cadastro.php" class="button"> Adcionar Buffets </a>
    </div>

    <?php if (mysqli_num_rows($result) > 0) : ?>

      <table>
        <thead>
          <tr>
            <th class="id-column">ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th class="th">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php $index = 1;
          while ($buffet = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td class="id-column"><?php echo $index++; ?></td>
              <td><?php echo $buffet['nome']; ?></td>
              <td><?php echo $buffet['descricao']; ?></td>
              <td><?php echo $buffet['tipo_nome']; ?></td>
              <td class="action">
                <a href="edit buffet.php?id=<?php echo $buffet['id']; ?>" class="btn-action edit" title="Editar">
                  <button class="btn-edit">
                    <!-- SVG do lápis (editar) -->
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                      <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
                      <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
                    </svg>
                  </button>
                </a>

                <a href="#" onclick="abrirModal(<?php echo $buffet['id']; ?>)" class="btn-action delete" title="Deletar">
                  <button class="btn-delete">
                    <!-- SVG da lixeira (deletar) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                      <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
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
          <h2>Deseja excluir este buffet?</h2>
          <div class="modal-buttons">
            <button onclick="confirmarExclusao()" class="btn-confirm">Sim</button>
            <button onclick="fecharModal()" class="btn-cancel">Não</button>
          </div>
        </div>
      </div>


      <script>
        let buffetIdToDelete; // Variável para armazenar o ID do buffet a ser excluído

        // Função para abrir o modal e armazenar o ID
        function abrirModal(id) {
          buffetIdToDelete = id; // Armazena o ID do buffet selecionado
          document.getElementById('confirmModal').style.display = 'flex'; // Exibe o modal
        }

        // Função para fechar o modal
        function fecharModal() {
          document.getElementById('confirmModal').style.display = 'none'; // Oculta o modal
        }

        // Função para confirmar a exclusão
        function confirmarExclusao() {
          // Redireciona para o script de exclusão com o ID armazenado
          window.location.href = 'delete Buffet.php?delete=' + buffetIdToDelete;
        }
      </script>

      <!-- Modal -->
      <div id="modalErro" style="display: none;">
        <div class="modal-content-associado">
          <span id="modalFechar" style="cursor: pointer;">&times;</span>
          <p>Este buffet está associado a eventos e não pode ser excluído.</p>
        </div>
      </div>

      <script>
        window.onload = function() {
          const urlParams = new URLSearchParams(window.location.search);

          // Verifica se o parâmetro "erro" está na URL
          if (urlParams.has('erro') && urlParams.get('erro') === 'associado') {
            // Exibe o modal
            document.getElementById('modalErro').style.display = 'flex';

            // Remove o parâmetro "erro" da URL
            urlParams.delete('erro');
            window.history.replaceState({}, '', window.location.pathname + '?' + urlParams.toString());
          }

          // Fecha o modal ao clicar no "X"
          document.getElementById('modalFechar').addEventListener('click', function() {
            document.getElementById('modalErro').style.display = 'none';
          });
        };
      </script>


      <!-- Links de Paginação -->
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
          <a href="?pagina=<?php echo $i; ?>" class="<?php echo $i == $paginaAtual ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php else : ?>
      <p>Nenhum buffet cadastrado.</p>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
    </div>

</body>

</html>


</body>

</html>