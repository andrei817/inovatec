menuItems.forEach(item => {
    item.addEventListener('click', () => {
        // Remove a classe 'active' de todos os itens
        menuItems.forEach(i => i.classList.remove('active'));

        // Adiciona a classe 'active' ao item clicado
        item.classList.add('active');
    });
});

      //Função para mostrar o modal de confirmação
    function showConfirmBox() {
        document.getElementById("confirmModal").style.display = "flex";
     }

     //Função para fechar o modal de confirmação
    function closeConfirmBox() {
        document.getElementById("confirmModal").style.display = "none";
    }

    // Função para mostrar o modal de informações
    function showDetails(eventId) {
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

// script do modal-login
   

    //function confirmar() {
       // fecharPopUp();
      
      //  window.location.href = 'Logar produtor.php'; // Exemplo de redirecionamento
   // }

   const express = require("express");
const jwt = require("jsonwebtoken");
const cookieParser = require("cookie-parser");

const app = express();
app.use(cookieParser());

// Função para criar o token JWT e armazená-lo como cookie
function createSessionToken(res, userId) {
  const token = jwt.sign({ userId }, process.env.JWT_SECRET, { expiresIn: "1h" });
  res.cookie("session", token, {
    httpOnly: true,
    secure: true,
    sameSite: "Strict",
    maxAge: 3600000 // 1 hora em milissegundos
  });
}

// Endpoint de login
app.post("/login", (req, res) => {
  const userId = req.body.userId; // ID do usuário após autenticação
  createSessionToken(res, userId);
  res.send("Login efetuado com sucesso!");
});

// Middleware para verificar se o usuário está autenticado
function authMiddleware(req, res, next) {
  const token = req.cookies.session;
  if (!token) return res.status(401).send("Sessão expirada");

  jwt.verify(token, process.env.JWT_SECRET, (err, decoded) => {
    if (err) return res.status(401).send("Sessão inválida");
    req.userId = decoded.userId;
    next();
  });
}

// Rota protegida
app.get("/protected", authMiddleware, (req, res) => {
  res.send("Conteúdo protegido");
});

app.listen(3000, () => console.log("Servidor rodando na porta 3000"));
   



  

  
    



