// Função para abrir a sidebar
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


let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");
let classList = profileDropdownList.classList;
const toggle = () => classList.toggle("active");
window.addEventListener("click", function (e) {
if (!btn.contains(e.target)) classList.remove("active");
});


function closeModal() {
document.getElementById('eventModal').style.display = 'none';
}

window.onclick = function(event) {
  if (event.target == document.getElementById('eventModal')) {
      closeModal();
  }
}


document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.progress').forEach(function (el) {
      let value = el.getAttribute('data-value');
      let bar = document.createElement('div');
      bar.style.width = value;
      bar.style.height = '100%';
      bar.style.backgroundColor = '#ffff';
      bar.style.borderRadius = '5px';
      bar.style.transition = 'width 1s ease-in-out';
      el.appendChild(bar);
  });
});

  

