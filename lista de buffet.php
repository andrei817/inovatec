<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="lista de buffet.css">
    <title>Lista de Buffets</title>
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
     // Se for um dispositivo móvel, ocupa 100% da tela; caso contrário, 250px
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



    <?php

include("php/Config.php");

// Defina o número de buffets por página
$buffetsPorPagina = 3;

// Determine a página atual (padrão é 1)
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $buffetsPorPagina;

// Consulta SQL com LIMIT e OFFSET
$sql = "SELECT * FROM buffet LIMIT $buffetsPorPagina OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Consulta para contar o total de registros
$totalBuffets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM buffet"))['total'];
$totalPaginas = ceil($totalBuffets / $buffetsPorPagina);
?>

<section class="agenda-evento">
<div class="conteudo">
<h1>BUFFETS</h1>
<a href= "buffet cadastro.php" class="button"> Adcionar Buffets </a>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>

<table>
<thead>
<tr>
    <th class="id-column">ID</th>
    <th>Nome</th>
    <th>Descrição</th>
    <th class="ações">Ações</th>
</tr>
<tr>
</thead>
    <tbody>
        <?php 
        $index = 1; // Inicializa o índice
        while ($buffet = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td class="id-column"><?php echo $index++;?></td>
                <td><?php echo $buffet['nome']; ?></td>
                <td><?php echo $buffet['descricao']; ?></td>
                <td class="actions">
                <a class="a" href="edit buffet.php?id=<?php echo $buffet['id']; ?>"> <button class='btn-edit'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
<path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
<path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/> </a> </svg> </button>


<a class="a" href="delete Buffet.php?delete=<?php echo $buffet['id']; ?>"><button class='btn-delete' > <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
</svg> </a> </button>

                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>    

</table>
 <!-- Links de Paginação -->
 <div class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>" class="<?php echo $i == $paginaAtual ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <p>Nenhum buffet cadastrado.</p>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
</div>

</body>
</html>

    
</body>
</html>
