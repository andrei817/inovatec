function Login() {
   
    const validUsername = "usuario";
    const validPassword = "senha123";

    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    const messageElement = document.getElementById("message");

   
    if (username === validUsername && password === validPassword) {
        messageElement.style.color = "green";
        messageElement.textContent = "Conta logada com sucesso.";
    } else {
        messageElement.style.color = "red";
        messageElement.textContent = "Ocorreu um erro. Tente novamente.";
    }
}


// Selecionar o campo de senha e o botão de alternância
const passwordField = document.getElementById('password');
const togglePasswordButton = document.getElementById('togglePassword');

// Adicionar um evento de clique no botão
togglePasswordButton.addEventListener('click', function () {
    // Verificar o tipo atual do campo de senha
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    
    // Alterar o tipo de "password" para "text" e vice-versa
    passwordField.setAttribute('type', type);

    // Alterar o texto do botão para "Mostrar" ou "Ocultar"
    this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
});