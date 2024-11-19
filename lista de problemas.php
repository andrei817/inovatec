<?php
include("php/Config.php"); // Inclui a conexão com o banco de dados

// Query para buscar os reportes de problemas com os nomes dos eventos
$sql = "
    SELECT 
        rp.id AS reporte_id,
        e.nome AS evento_nome,
        rp.data AS data_reporte,
        rp.descricao AS descricao_problema,
        rp.email AS contato_email,
        rp.telefone AS contato_telefone
    FROM 
        reportes_problemas rp
    INNER JOIN 
        eventos e 
    ON 
        rp.evento_id = e.id
    ORDER BY 
        rp.data DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Problemas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Reportes de Problemas</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Evento</th>
                <th>Data</th>
                <th>Descrição</th>
                <th>Email</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['reporte_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['evento_nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_reporte']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao_problema']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contato_email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contato_telefone']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum reporte encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
