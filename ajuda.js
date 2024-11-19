

// Seleciona todos os botões de título do sumário
const helpTitles = document.querySelectorAll(".help-title");

helpTitles.forEach(button => {
  button.addEventListener("click", function() {
    // Seleciona o conteúdo relacionado ao botão clicado
    const content = this.nextElementSibling;

    // Alterna a visibilidade do conteúdo
    content.style.display = content.style.display === "block" ? "none" : "block";
  });
});

