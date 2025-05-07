

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

function toggleHelp(button) {
  const content = button.nextElementSibling; // Seleciona o conteúdo associado ao botão clicado
  const isOpen = content.classList.contains('scrollable'); // Verifica se está expandido

  if (isOpen) {
      content.style.maxHeight = '0'; // Recolhe o conteúdo
      content.classList.remove('scrollable');
  } else {
      content.style.maxHeight = '200px'; // Expande o conteúdo
      content.classList.add('scrollable');
  }
}

