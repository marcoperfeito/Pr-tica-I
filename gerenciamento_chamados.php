<?php
include 'config.php';


$status = isset($_GET['status']) ? $_GET['status'] : '';
$criticidade = isset($_GET['criticidade']) ? $_GET['criticidade'] : '';
$colaborador = isset($_GET['colaborador']) ? $_GET['colaborador'] : '';


$sql = "SELECT Chamado.ID_chamado, Cliente.Nome AS Cliente, Chamado.Descricao, 
               Chamado.Criticidade, Chamado.Status, Colaborador.Nome AS Responsavel
        FROM Chamado
        LEFT JOIN Cliente ON Chamado.ID_cliente = Cliente.ID_cliente
        LEFT JOIN Colaborador ON Chamado.ID_colaborador_responsavel = Colaborador.ID_colaborador";


$conditions = [];
$params = [];
$types = '';

if ($status) {
    $conditions[] = "Chamado.Status = ?";
    $params[] = $status;
    $types .= 's';
}
if ($criticidade) {
    $conditions[] = "Chamado.Criticidade = ?";
    $params[] = $criticidade;
    $types .= 's';
}
if ($colaborador) {
    $conditions[] = "Colaborador.ID_colaborador = ?";
    $params[] = $colaborador;
    $types .= 'i';
}
if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}


$stmt = $conn->prepare($sql);

if ($conditions) {
    $stmt->bind_param($types, ...$params);
}


$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Chamados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciamento de Chamados</h1>
        <form class="filter-form" method="GET">
            <div class="filter-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <option value="aberto" <?php if ($status == 'aberto') echo 'selected'; ?>>Aberto</option>
                    <option value="em andamento" <?php if ($status == 'em andamento') echo 'selected'; ?>>Em andamento</option>
                    <option value="resolvido" <?php if ($status == 'resolvido') echo 'selected'; ?>>Resolvido</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="criticidade">Criticidade:</label>
                <select id="criticidade" name="criticidade">
                    <option value="">Todas</option>
                    <option value="baixa" <?php if ($criticidade == 'baixa') echo 'selected'; ?>>Baixa</option>
                    <option value="média" <?php if ($criticidade == 'média') echo 'selected'; ?>>Média</option>
                    <option value="alta" <?php if ($criticidade == 'alta') echo 'selected'; ?>>Alta</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="colaborador">Colaborador:</label>
                <select id="colaborador" name="colaborador">
                    <option value="">Todos</option>
                    <?php
                    $colaboradores = $conn->query("SELECT * FROM Colaborador");
                    while ($row = $colaboradores->fetch_assoc()) {
                        $selected = $colaborador == $row['ID_colaborador'] ? 'selected' : '';
                        echo "<option value='{$row['ID_colaborador']}' $selected>{$row['Nome']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="filter-btn">Filtrar</button>
        </form>

        <table class="chamados-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Descrição</th>
                    <th>Criticidade</th>
                    <th>Status</th>
                    <th>Responsável</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['ID_chamado']}</td>
                                <td>{$row['Cliente']}</td>
                                <td>{$row['Descricao']}</td>
                                <td>{$row['Criticidade']}</td>
                                <td>{$row['Status']}</td>
                                <td>{$row['Responsavel']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum chamado encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
