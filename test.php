<?php
// Senha fornecida pelo usuário
$senhaFornecida = 'andrei1234';  

// Hash armazenado no banco de dados (substitua pelo valor real)
$senhaHash = '$2y$10$abcdefg12345...';  // Exemplo de um hash armazenado no banco de dados

// Verificar se a senha fornecida corresponde ao hash armazenado
if (password_verify($senhaFornecida, $senhaHash)) {
    echo "Senha correta!";
} else {
    echo "Senha incorreta!";
}
?>