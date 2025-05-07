<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Ajuda</title>
    <link rel="stylesheet" href="ajuda do produtor.css">
    
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

<div class="agenda-evento">
    <div class="conteudo">


        <a href="ambiente.php" class="close-btn-ajuda">&times;</a>
        <div class="help-container">

              <div class="ajuda-title">
                <h1 class="Ajuda">Ajuda</h1>
                <p>Bem-vindo ao Sistema de Gestão de Eventos (SGE). Aqui estão as instruções para ajudá-lo a gerenciar seus eventos de forma eficaz.</p>
              </div>

                <!-- Gerenciamento de Eventos -->
                <div class="help-section">
                    <button class="help-title">Como Criar um Evento?</button>
                    <div class="help-content">
                        <p>Para criar um evento, Acesse o menu lateral e clique na seção "Eventos", nele vc terá uma lista de eventos e acesse "Adicionar Novo Evento", preencha os campos obrigatórios como nome, data, local, etc., e clique em "Cadastrar".</p>
                    </div>
                </div>

                <!-- Adicionar Buffet -->
                <div class="help-section">
                    <button class="help-title">Como Adicionar um Buffet ao Evento?</button>
                    <div class="help-content">
                        <p>Acesse o menu, e vá na seção "Buffet" você terá uma lista de buffets associadas ao evento . Na lista, vai em "Adicionar Novo Buffet" e preencha os campos com a opção desejada. O buffet será cadastrado e vinculado ao evento na tabela.</p>
                    </div>
                </div>

                <!-- Adicionar Staff -->
                <div class="help-section">
                    <button class="help-title">Como Adicionar um Staff ao Evento?</button>
                    <div class="help-content">
                        <p>Você pode também cadastrar um membros da equipe (staff) ao evento. Primeiro clique no menu e vai em "Staff" você verá a lista de staffs e ao clicar em "Adicionar Staff", selecione o membro desejado e defina suas responsabilidades.</p>
                    </div>
                </div>

                <!-- Adicionar Objetivos -->
                <div class="help-section">
                    <button class="help-title">Como Definir Objetivos para o Evento?</button>
                    <div class="help-content">
                        <p>Na seção "Objetivos", você pode especificar as metas e resultados desejados para o evento. Defina os objetivos antes de salvar o evento.</p>
                    </div>
                </div>

                <!-- Adicionar Imagens -->
                <div class="help-section">
                    <button class="help-title">Como Adicionar Imagens ao Evento?</button>
                    <div class="help-content">
                        <p>Para adicionar imagens, vá até a seção de "Adicionar Novo Evento" e lá você terá a opção de "Imagem do evento" selecione as fotos que deseja associar. As imagens serão exibidas na lista de eventos.</p>
                    </div>
                </div>

                <!-- Visualizar Relatório -->
                <div class="help-section">
                    <button class="help-title">Como Visualizar o Relatórios?</button>
                    <div class="help-content">
                        <p>Acesse o relatório de eventos para visualizar todas as informações de seus eventos, como data, local, staff, buffet e objetivos associados.</p>
                    </div>
                </div>

                <!-- Editar e Excluir Eventos -->
                <div class="help-section">
                    <button class="help-title">Como Editar ou Excluir um Evento?</button>
                    <div class="help-content">
                        <p>Para editar um evento, clique no evento desejado na lista de eventos e selecione "Editar". Para excluir, clique em "Excluir" no evento que deseja remover.</p>
                    </div>
                </div>

                <!-- Reportar Problemas -->
              <div class="help-section">
                  <button class="help-title">Como Reportar Problemas?</button>
                  <div class="help-content">
                      <p>Para reportar problemas, acesse o menu e clique na opção "Reportar Problema". Nele você verá um relatório com as informações de um evento com o problema ocorrido em seguida, clique em "Reportar Problemas" Preencha o formulário com detalhes sobre o erro ou situação encontrada e clique em "Reportar". Nossa equipe analisará sua solicitação.</p>
                  </div>
              </div>

              <!-- Redefinir Senha -->
              <div class="help-section">
                  <button class="help-title">Como Redefinir a Senha?</button>
                  <div class="help-content">
                      <p>Se você esquecer sua senha, clique em "Esqueceu a senha?" na página de login. Insira o e-mail, a pergunta que você digitou e a resposta cadastrada e siga as instruções enviadas para redefinir sua senha. Caso seja produtor, vá até o perfil e escolha "Alterar Senha" e confirme sua nova senha.</p>
                  </div>
              </div>
            </div>
        </div>
    </div>

    <script> 
// Função para expandir o conteúdo e rolar a página até ele
function toggleSection(sectionId) {
  var content = document.getElementById(sectionId);
  content.classList.toggle('active');

  // A rolagem para a seção expandidas
  if (content.classList.contains('active')) {
    // Adiciona um pequeno atraso para garantir que a animação de expansão ocorra
    setTimeout(function() {
      content.scrollIntoView({ behavior: 'smooth' });
    }, 200);
  }
}

// Adicionando o evento de click nos botões de ajuda
document.querySelectorAll('.help-title').forEach(function(button) {
  button.addEventListener('click', function() {
    var section = button.nextElementSibling;  // Encontre o conteúdo da seção
    toggleSection(section.id);  // Chame a função de toggle
  });
});

</script>

   </section>

    <script src="ajuda.js"> </script>
</body>
</html>