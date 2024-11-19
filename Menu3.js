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




//function showMsgBox1() {
   // document.getElementById('halloween').style.display = 'block';
    // }
  
  // function closeMsgBox1() {
    // document.getElementById('halloween').style.display = 'none';
     //  }
  
      // function showMsgBox2() {
       // document.getElementById('festa junina').style.display = 'block';
        // }
    
      // function closeMsgBox2() {
        // document.getElementById('festa junina').style.display = 'none';
         ///  }
  
          // function showMsgBox3() {
           // document.getElementById('dia das crianças').style.display = 'block';
           //  }
        
          // function closeMsgBox3 () {
           //  document.getElementById('dia das crianças').style.display = 'none';
             //  }
  
  
             //  function redirecionarPagina() {
             //   window.location.href = "#"; // Coloque aqui a URL desejada
          //  }

           // Função para mostrar o modal de confirmação
    function showConfirmBox() {
      document.getElementById("confirmModal").style.display = "flex";
  }

  // Função para fechar o modal de confirmação
  function closeConfirmBox() {
      document.getElementById("confirmModal").style.display = "none";
  }

  // Função para mostrar o modal de informações
  function showDetails() {
      closeConfirmBox();
      document.getElementById("infoModal").style.display = "flex";
  }

  // Função para fechar o modal de informações
  function closeDetails() {
      document.getElementById("infoModal").style.display = "none";
  }

  // Fechar o modal ao clicar fora do conteúdo
  window.onclick = function(event) {
      var confirmModal = document.getElementById("confirmModal");
      var infoModal = document.getElementById("infoModal");
      
      if (event.target == confirmModal) {
          confirmModal.style.display = "none";
      }
      if (event.target == infoModal) {
          infoModal.style.display = "none";
      }
  }


     // Função para mostrar o modal de confirmação
     function showConfirmBox2() {
      document.getElementById("confirmModal 2").style.display = "flex";
  }

  // Função para fechar o modal de confirmação
  function closeConfirmBox2() {
      document.getElementById("confirmModal 2").style.display = "none";
  }

  // Função para mostrar o modal de informações
  function showDetails2() {
      closeConfirmBox2();
      document.getElementById("infoModal 2").style.display = "flex";
  }

  // Função para fechar o modal de informações
  function closeDetails2() {
      document.getElementById("infoModal 2").style.display = "none";
  }

  // Fechar o modal ao clicar fora do conteúdo
  window.onclick = function(event) {
      var confirmModal = document.getElementById("confirmModal 2");
      var infoModal = document.getElementById("infoModal 2");
      
      if (event.target == confirmModal) {
          confirmModal.style.display = "none";
      }
      if (event.target == infoModal) {
          infoModal.style.display = "none";
      }
  }



    // Função para mostrar o modal de confirmação
    function showConfirmBox3() {
      document.getElementById("confirmModal 3").style.display = "flex";
  }

  // Função para fechar o modal de confirmação
  function closeConfirmBox3() {
      document.getElementById("confirmModal 3").style.display = "none";
  }

  // Função para mostrar o modal de informações
  function showDetails3() {
      closeConfirmBox3();
      document.getElementById("infoModal 3").style.display = "flex";
  }

  // Função para fechar o modal de informações
  function closeDetails3() {
      document.getElementById("infoModal 3").style.display = "none";
  }

  // Fechar o modal ao clicar fora do conteúdo
  window.onclick = function(event) {
      var confirmModal = document.getElementById("confirmModal 3");
      var infoModal = document.getElementById("infoModal 3");
      
      if (event.target == confirmModal) {
          confirmModal.style.display = "none";
      }
      if (event.target == infoModal) {
          infoModal.style.display = "none";
      }
  }


  let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");
let classList = profileDropdownList.classList;
const toggle = () => classList.toggle("active");
window.addEventListener("click", function (e) {
  if (!btn.contains(e.target)) classList.remove("active");
});

