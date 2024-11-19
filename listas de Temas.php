<?php
include ("php/Config.php");
$sql = "SELECT * FROM tema";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - listas de temas</title>
    <link rel="stylesheet" href="tema.css">
    <script src="evento.js"> </script>
    <style>
        
table {
    width: 70%;
    border-collapse: collapse;
}
table, th, td {
    border: 1px solid black;
}
th, td {
    padding: 10px;
    text-align: left;
}
button {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button.delete {
    background-color: #f44336;
}

.id-column {
    display: none;
  }
        </style>
    
</head>

<body>
    
<header>
     

    <div class="logo-foto"> 
        <img src="SGE logo.png"width=80% height="100%">
    <div class="header-content"> 
   <h1> S.G.E.</h1> 
   <p> Sistema de Gestão de Eventos</p>

   </div>   
       </div>
       

    

       <div class="logo">

        <img src="eventos.png"width=103% height="100%">
       
       </div>
 
        <nav> 
            
            <ul> 
               
                <li> <a href="index.html"> Home</a></li>
                <li> <a href="#"> Eventos</a></li> 
                <li> <a href= "Sobre.html">Sobre</a></li>
                <li> <a href= "ajuda.html">Ajuda</a></li>
                <li>  Olá Produtor</li>
            </ul>
    
        </nav>
     
    </header>
     

    <section class="tema-evento">
        
        <div class="conteudo">

        <h1> LISTA DE TEMAS</h1>
        <table>
    <tr>
        <th>ID</th>
        <th>Nome do Tema</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nome'] ?></td>
            <td><?= $row['descricao'] ?></td>
            <td>
                <a href="editar_tema.php?id=<?= $row['id'] ?>"><button>Editar</button></a>
                <a href="excluir_tema.php?id=<?= $row['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este tema?');"><button class="delete">Excluir</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">Nenhum tema encontrado.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
// Fechar conexão
$conn->close();
?>

   



