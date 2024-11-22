<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';

 
    if (!empty($nome) && !empty($email)) {
    
        $stmt = $conn->prepare("INSERT INTO Cliente (Nome, Email, Telefone) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $nome, $email, $telefone);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Cliente cadastrado com sucesso!</p>";
            } else {
                echo "<p style='color: red;'>Erro ao executar: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Erro ao preparar a consulta: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Por favor, preencha todos os campos obrigatórios.</p>";
    }
}
?>

