<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    // Se o usuário não estiver logado, redireciona para a página inicial
    header('Location: index.php');
    exit;
}

// Verificar se o login foi bem-sucedido
$showSuccessPopup = false;
if (isset($_SESSION['login_success'])) {
    $showSuccessPopup = true;
    unset($_SESSION['login_success']); // Remove a variável para evitar exibições futuras
}

// Se o usuário já estiver logado, redireciona para a página de ambiente
if (isset($_SESSION['user_id'])) {
    header("Location: ambiente.php");
    exit();
}

// A sessão está ativa, obtém o e-mail e o nome do produtor
$produtor_email = $_SESSION['email'];
$produtor_nome = $_SESSION['nome'];
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Ambiente do Produtor</title>
    <!-- Link para Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="ambiente.css">
</head>

<body>


    <header>

        <!-- Botão para abrir a sidebar -->
        <button class="open-btn" onclick="abrirSidebar()">☰</button>

        <!-- Sidebar -->
        <div id="mySidebar" class="sidebar">
            <span class="close-btn" onclick="fecharSidebar()">&times;</span> <!-- Botão "X" -->
            <h2 style="padding-left: 20px;">Menu</h2>
            &nbsp;
            <ul class="separator">
                <li><a href="lista de eventos.php"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
                &nbsp;
                <li><a href="colaboradores.php"><i class="fas fa-users"></i> Colaboradores</a></li>
                &nbsp;
                <ul>
                    <li><a href="lista de staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
                </ul>
                &nbsp;
                <ul>
                    <li><a href="Cargo.php"><i class="fas fa-briefcase"></i> Cargo</a></li>
                </ul>
                &nbsp;
                <ul>
                    <li><a href="tema lista.php"><i class="fas fa-tag"></i> Tema</a></li>
                </ul>
                &nbsp;
                <ul>
                    <li><a href="lista de buffet.php"><i class="fas fa-concierge-bell"></i> Buffet</a></li>
                </ul>
                &nbsp;
                <ul>
                    <li><a href="lista de objetivos.php"><i class="fas fa-bullseye"></i> Objetivos</a></li>
                </ul>
                &nbsp;
                <li><a href="relatório de problemas.php"><i class="fas fa-exclamation-circle"></i> Reportar Problemas</a></li>
                &nbsp;
            </ul>
        </div>



        <!-- Pop-up de Login Bem-Sucedido -->
        <div id="loginSuccessPopup" class="popup" style="display: none;">
            <div class="popup-content">
                <span class="close-btn-popup" onclick="closePopup()">&times;</span> <!-- Botão "X" -->
                <h2>Login Bem-Sucedido</h2>
                <img src="correto.png" alt="Bem-vindo" class="popup-image">
                <p class="bem-vindo">Bem-vindo(a), <?php echo htmlspecialchars($produtor_nome); ?>!</p>
            </div>
        </div>

        <script>
            // Verifica se o pop-up deve ser exibido
            const showSuccessPopup = <?php echo json_encode($showSuccessPopup); ?>;
            if (showSuccessPopup) {
                const popup = document.getElementById('loginSuccessPopup');
                popup.style.display = 'flex'; // Exibe o pop-up

                // Define um tempo para o pop-up desaparecer automaticamente (ex.: 3 segundos)
                setTimeout(() => {
                    popup.style.display = 'none'; // Esconde o pop-up
                }, 3000); // 3000ms = 3 segundos
            }

            // Função para fechar o pop-up manualmente (opcional)
            function closePopup() {
                document.getElementById('loginSuccessPopup').style.display = 'none';
            }
        </script>


        <div class="logo-foto">
            <img src="Logo_SGE_inova.png" width=80% height="100%">

            <div class="header-content">
                <h1> S.G.E.</h1>
                <p> Sistema de Gestão de Eventos</p>

            </div>
        </div>

        <div class="foto-container">
            <div class="logo">
                <img src="eventos.png" width=103% height="100%">
            </div>

        </div>


        <nav>

            <ul>

                <li><a href="ambiente.php" title="Página inicial">Home</a></li>
                <li><a href="ajuda do produtor.php" title="Obtenha ajuda">Ajuda</a></li>
                <li><a href="Sobre.php" title="Sobre nós">Sobre</a></li>

            </ul>

            </div>

        </nav>
        <div class="profile-dropdown">
            <div onclick="toggle()" class="profile-dropdown-btn">

                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />


                </svg>



                <span>
                    <h1>Bem-vindo, <?php echo htmlspecialchars($produtor_nome); ?>! </h1>

                </span>
            </div>

            <ul class="profile-dropdown-list">

                <li class="profile-dropdown-list-item">
                    <a href="listar produtores.php">
                        <i class="bi bi-person-plus"></i>
                        Gerenciar produtor
                    </a>
                </li>
                <li class="profile-dropdown-list-item">
                    <a href="perfil do produtor.php">
                        <i class="bi bi-pen"></i>
                        Editar Perfil
                    </a>
                </li>
                <li class="profile-dropdown-list-item">
                    <a onclick="showLogoutModal()">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Sair
                    </a>
                </li>



                <!-- Modal de Logout -->
                <div id="logoutModal" class="modal">
                    <div class="modal-content">
                        <h2>Deseja se deslogar?</h2>
                        <!-- Formulário de logout -->
                        <form id="logoutForm" method="POST" action="logo out.php">
                            <!-- Botões Sim e Não em linha -->
                            <div class="button-container">
                                <!-- Botão Sim ativa a função JavaScript -->
                                <button type="button" class="btn btn-yes" onclick="handleLogout()">Sim</button>

                                <!-- Botão Não fecha o modal -->
                                <button type="button" class="btn btn-no" onclick="closeLogout('logoutModal')">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
        
                <!-- Modal de Agradecimento -->
                <div id="thankYouModal" class="modal">
                    <div class="modal-content">
                        <h2>Obrigado por usar o nosso site!</h2>

                    </div>
                </div>

                <script>
                    // Função para mostrar o modal de logout
                    function showLogoutModal() {
                        document.getElementById('logoutModal').style.display = 'flex';
                    }

                    // Função para fechar qualquer modal
                    function closeLogout(modalId) {
                        document.getElementById(modalId).style.display = 'none';
                    }

                    // Função para lidar com o logout com modal de agradecimento
                    function handleLogout() {
                        // Fecha o modal de logout
                        closeLogout('logoutModal');

                        // Mostra o modal de agradecimento
                        document.getElementById('thankYouModal').style.display = 'flex';

                        // Aguarda 2 segundos antes de enviar o formulário
                        setTimeout(function() {
                            document.getElementById('logoutForm').submit(); // Envia o formulário de logout
                        }, 2000); // 2000 milissegundos = 2 segundos
                    }
                </script>

            </ul>
        </div>
        </nav>
        </li>

    </header>

    <section class="agenda-evento">
        <div class="scroll-container">
            <div class="conteudo">

                <?php
                include("php/Config.php");

                // Consultando o total de buffets
                $sql_buffets = "SELECT COUNT(*) AS total FROM buffet";
                $result_buffets = $conn->query($sql_buffets);
                $total_buffets = $result_buffets ? $result_buffets->fetch_assoc()['total'] : 0;

                // Consultando o total de staffs
                $sql_staffs = "SELECT COUNT(*) AS total FROM staffs_eventos";
                $result_staffs = $conn->query($sql_staffs);
                $total_staffs = $result_staffs ? $result_staffs->fetch_assoc()['total'] : 0;

                // Consultando o total de eventos
                $sql_eventos = "SELECT COUNT(*) AS total FROM eventos";
                $result_eventos = $conn->query($sql_eventos);
                $total_eventos = $result_eventos ? $result_eventos->fetch_assoc()['total'] : 0;

                // Consultando o total de temas
                $sql_temas = "SELECT COUNT(*) AS total FROM tema";
                $result_temas = $conn->query($sql_temas);
                $total_temas = $result_temas ? $result_temas->fetch_assoc()['total'] : 0;

                // Consultando o total de objetivo
                $sql_objetivo = "SELECT COUNT(*) AS total FROM objetivo";
                $result_objetivo = $conn->query($sql_objetivo);
                $total_objetivo = $result_objetivo ? $result_objetivo->fetch_assoc()['total'] : 0;

                // Consultando o total de evento_staff
                $sql_evento_staff = "SELECT COUNT(*) AS total FROM evento_staff";
                $result_evento_staff = $conn->query($sql_evento_staff);
                $total_evento_staff = $result_evento_staff ? $result_evento_staff->fetch_assoc()['total'] : 0;

                // Consultando o total de cargos
                $sql_cargos = "SELECT COUNT(*) AS total FROM cargos";
                $result_cargos = $conn->query($sql_cargos);
                $total_cargos = $result_cargos ? $result_cargos->fetch_assoc()['total'] : 0;

                // Consultando o total de problemas_evento
                $sql_problemas = "SELECT COUNT(*) AS total FROM problemas_evento";
                $result_problemas = $conn->query($sql_problemas);
                $total_problemas = $result_problemas ? $result_problemas->fetch_assoc()['total'] : 0;

                // Cálculo total geral
                $total_geral = $total_buffets + $total_staffs + $total_eventos + $total_temas + $total_objetivo + $total_evento_staff + $total_cargos + $total_problemas;

                // Função para calcular porcentagem
                function calcularPorcentagem($total_categoria, $total_geral)
                {
                    return $total_geral > 0 ? round(($total_categoria / $total_geral) * 100) : 0;
                }

                // Calcula as porcentagens
                $porcentagem_buffet        = calcularPorcentagem($total_buffets, $total_geral);
                $porcentagem_staffs        = calcularPorcentagem($total_staffs, $total_geral);
                $porcentagem_eventos       = calcularPorcentagem($total_eventos, $total_geral);
                $porcentagem_temas         = calcularPorcentagem($total_temas, $total_geral);
                $porcentagem_objetivo      = calcularPorcentagem($total_objetivo, $total_geral);
                $porcentagem_evento_staff  = calcularPorcentagem($total_evento_staff, $total_geral);
                $porcentagem_cargos        = calcularPorcentagem($total_cargos, $total_geral);
                $porcentagem_problemas     = calcularPorcentagem($total_problemas, $total_geral);

                // Simulação de valores anteriores (exemplo: valores de ontem)
                $anterior_buffets       = 7;
                $anterior_staffs        = 5;
                $anterior_eventos       = 5;
                $anterior_temas         = 5;
                $anterior_objetivo      = 3;
                $anterior_evento_staff  = 6;
                $anterior_cargos        = 6;
                $anterior_problemas     = 7;

                // Função para calcular o ícone de tendência
                function getTrendIcon($atual, $anterior)
                {
                    if ($atual > $anterior) {
                        return "bx bx-trending-up icon";  // Ícone de aumento
                    } elseif ($atual < $anterior) {
                        return "bx bx-trending-down icon down";  // Ícone de queda
                    } else {
                        return "bx bx-minus icon";  // Ícone neutro
                    }
                }

                // Calcula os ícones de tendência
                $icon_buffets       = getTrendIcon($total_buffets, $anterior_buffets);
                $icon_staffs        = getTrendIcon($total_staffs, $anterior_staffs);
                $icon_eventos       = getTrendIcon($total_eventos, $anterior_eventos);
                $icon_temas         = getTrendIcon($total_temas, $anterior_temas);
                $icon_objetivo      = getTrendIcon($total_objetivo, $anterior_objetivo);
                $icon_evento_staff  = getTrendIcon($total_evento_staff, $anterior_evento_staff);
                $icon_cargos        = getTrendIcon($total_cargos, $anterior_cargos);
                $icon_problemas     = getTrendIcon($total_problemas, $anterior_problemas);
                ?>

                <!-- MAIN -->
                <main>
                    <div class="info-data">

                        <!-- Buffet -->
                        <div class="card">
                            <a href="lista de buffet.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">restaurant</span>
                                    <div>
                                        <h2>Total de Buffet</h2>
                                        <p><?php echo $total_buffets; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_buffets; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_buffet; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_buffet; ?>%</span>
                            </a>
                        </div>

                        <!-- Staffs -->
                        <div class="card">
                            <a href="lista de staff.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">groups</span>
                                    <div>
                                        <h2>Total de Staffs</h2>
                                        <p><?php echo $total_staffs; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_staffs; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_staffs; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_staffs; ?>%</span>
                            </a>
                        </div>

                        <!-- Eventos -->
                        <div class="card">
                            <a href="lista de eventos.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">event</span>
                                    <div>
                                        <h2>Total de Eventos</h2>
                                        <p><?php echo $total_eventos; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_eventos; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_eventos; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_eventos; ?>%</span>
                            </a>
                        </div>

                        <!-- Temas -->
                        <div class="card">
                            <a href="tema lista.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">category</span>
                                    <div>
                                        <h2>Total de Temas</h2>
                                        <p><?php echo $total_temas; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_temas; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_temas; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_temas; ?>%</span>
                            </a>
                        </div>

                        <!-- Objetivo -->
                        <div class="card">
                            <a href="lista de objetivos.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">flag</span>
                                    <div>
                                        <h2>Total de Objetivos</h2>
                                        <p><?php echo $total_objetivo; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_objetivo; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_objetivo; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_objetivo; ?>%</span>
                            </a>
                        </div>

                        <!-- Evento Staff -->
                        <div class="card">
                            <a href="colaboradores.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">badge</span>
                                    <div>
                                        <h2>Colaboradores</h2>
                                        <p><?php echo $total_evento_staff; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_evento_staff; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_evento_staff; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_evento_staff; ?>%</span>
                            </a>
                        </div>

                        <!-- Cargos -->
                        <div class="card">
                            <a href="Cargo.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">work</span>
                                    <div>
                                        <h2>Total de Cargos</h2>
                                        <p><?php echo $total_cargos; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_cargos; ?>'></i>
                                </div>
                                <span class="progress" data-value="<?php echo $porcentagem_cargos; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_cargos; ?>%</span>
                            </a>
                        </div>

                        <!-- Problemas -->
                        <div class="card">
                            <a href="relatório de problemas.php">
                                <div class="head">
                                    <span class="material-symbols-outlined">report_problem</span>
                                    <div>
                                        <h2>Problemas</h2>
                                        <p><?php echo $total_problemas; ?></p>
                                    </div>
                                    <i class='<?php echo $icon_problemas; ?>'></i>
                                </div>
                                <span class="progress" style="width: 65%;" data-value="<?php echo $porcentagem_problemas; ?>%"></span>
                                <span class="label"><?php echo $porcentagem_problemas; ?>%</span>
                            </a>
                        </div>
                    </div>
                    <?php
                    // Consultando o número de eventos por status
                    $sql_eventos = "SELECT status_do_evento_id, COUNT(*) AS total FROM eventos GROUP BY status_do_evento_id";
                    $result_eventos = $conn->query($sql_eventos);

                    // Verificando se a consulta foi bem-sucedida
                    if ($result_eventos) {
                        // Criar arrays para armazenar os rótulos e os valores
                        $labels = [];
                        $values = [];

                        // Loop para preencher os arrays com os dados
                        while ($row = $result_eventos->fetch_assoc()) {
                            $labels[] = $row['status_do_evento_id'];
                            $values[] = $row['total'];
                        }

                        // Converter os arrays PHP para JSON para passar para o JavaScript
                        $labels_json = json_encode($labels);
                        $values_json = json_encode($values);
                    } else {
                        echo "Erro na consulta SQL para eventos: " . $conn->error;
                    }
                    ?>

                    <div class="charts-wrapper">
                        <div class="charts-container">
                            <div class="data">
                                <div class="content-data">
                                    <div class="head">
                                        <h3>Relatório de Eventos</h3>
                                    </div>
                                    <!-- Container do gráfico -->
                                    <div class="chart-container">
                                        <canvas id="eventStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adicionar Chart.js -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                // Dados dos eventos por status
                                const eventData = {
                                    labels: ["Concluídos", "Cancelados", "Em Andamento", "Adiado"], // Rótulos dos status (obtidos do banco de dados)
                                    values: <?php echo $values_json; ?>, // Quantidade de eventos por status (obtidos do banco de dados)
                                    total: <?php echo array_sum($values); ?> // Total de eventos (soma das categorias)
                                };

                                // Criar gráfico de colunas
                                const ctx = document.getElementById("eventStatusChart").getContext("2d");
                                new Chart(ctx, {
                                    type: "bar", // Tipo de gráfico de colunas
                                    data: {
                                        labels: eventData.labels,
                                        datasets: [{
                                            label: "Quantidade de Eventos",
                                            data: eventData.values,
                                            backgroundColor: ["#4CAF50", "#F44336", "#FF9800", "#2196F3"],
                                            borderColor: ["#388E3C", "#D32F2F", "#F57C00", "#1976D2"],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(tooltipItem) {
                                                        let total = eventData.total;
                                                        let valor = tooltipItem.raw;
                                                        let porcentagem = ((valor / total) * 100).toFixed(2) + "%";
                                                        return `${valor} eventos (${porcentagem})`;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>

                        <?php

                        // Consulta SQL com JOIN para pegar as descrições dos status sociais
                        $sql_status_social = "
                        SELECT ss.descricao, COUNT(*) AS total 
                        FROM eventos e
                        JOIN status_social ss ON e.status_social_id = ss.id
                        GROUP BY ss.descricao
                    ";
                        $result_status_social = $conn->query($sql_status_social);

                        if ($result_status_social) {
                            $labels = [];
                            $values = [];

                            while ($row = $result_status_social->fetch_assoc()) {
                                $labels[] = $row['descricao']; // Usando descrição como rótulo
                                $values[] = $row['total'];
                            }

                            $labels_json = json_encode($labels);
                            $values_json = json_encode($values);
                            $total_json = array_sum($values);
                        } else {
                            echo "Erro na consulta SQL para status social: " . $conn->error;
                        }

                        ?>


                        <div class="charts-container">
                            <div class="data">
                                <div class="content-data">
                                    <div class="head">
                                        <h3>Distribuição de Status Social</h3>
                                    </div>
                                    <!-- Container do gráfico -->
                                    <div class="chart-container">
                                        <canvas id="socialStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Chart.js CDN -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const socialData = {
                                    labels: <?php echo $labels_json; ?>,
                                    values: <?php echo $values_json; ?>,
                                    total: <?php echo $total_json; ?>
                                };

                                const ctx = document.getElementById("socialStatusChart").getContext("2d");

                                new Chart(ctx, {
                                    type: "pie",
                                    data: {
                                        labels: socialData.labels,
                                        datasets: [{
                                            label: "Distribuição (%)",
                                            data: socialData.values,
                                            backgroundColor: ["#4CAF50", "#2196F3", "#FF9800", "#F44336", "#9C27B0", "#00BCD4", "#8BC34A"],
                                            borderColor: ["#388E3C", "#1976D2", "#F57C00", "#D32F2F", "#7B1FA2", "#0097A7", "#689F38"],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: "bottom"
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(tooltipItem) {
                                                        let valor = tooltipItem.raw;
                                                        let porcentagem = ((valor / socialData.total) * 100).toFixed(2) + "%";
                                                        return `${valor} eventos (${porcentagem})`;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                </main>
            </div>
        </div>

    </section>



    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <script src="ambiente.js"></script>
</body>

</html>