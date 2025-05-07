<?php
include("php/Config.php");
// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Obtém os dados do usuário logado
$stmt = $conn->prepare("SELECT * FROM produtores_eventos WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<?php include 'header.php'; // Inclui o cabeçalho ?>

<div class="perfil-container">
    <h2>Bem-vindo, <?php echo $user['nome']; ?></h2>
    <form id="perfil-form" method="POST" action="atualizar_perfil.php">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

        <label for="telefone">Senha:</label>
        <input type="password" id="senha" name="senha" value="<?php echo $user['senha']; ?>" required>

        <button type="submit">Atualizar</button>
    </form>
</div>

<script src="script.js"></script>
</body>
</html>
