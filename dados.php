<?php
$servername = "localhost";
$username = "estudante1";
$password = "estudante1";
$dbname = "pacientes";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $gener = isset($_POST['gener']) ? mysqli_real_escape_string($conn, $_POST['gener']) : 'todos';
    $diabetes = isset($_POST['diabetes']) ? mysqli_real_escape_string($conn, $_POST['diabetes']) : 'todos';
    $sintomas = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];

    $sql = "SELECT gener, age FROM pacientes WHERE 1 = 1";

    if ($gener != 'todos') {
        $sql .= " AND gener = ?";
    }

    if ($diabetes != 'todos') {
        $sql .= " AND diabetes = ?";
    }

    if (!empty($sintomas)) {
        $placeholders = implode(', ', array_fill(0, count($sintomas), '?'));
        $sql .= " AND sintomas IN ($placeholders)";
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Erro na preparação da consulta: " . $conn->error);
    }

    if ($gener != 'todos') {
        $stmt->bind_param("s", $gener);
    }

    if ($diabetes != 'todos') {
        $stmt->bind_param("s", $diabetes);
    }

    if (!empty($sintomas)) {
        $stmt->bind_param(str_repeat("s", count($sintomas)), ...$sintomas);
    }

    if (!$stmt->execute()) {
        throw new Exception("Erro na execução da consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['gener'] . "</td>";
            echo "<td>" . $row['age'] . "</td>";
            echo "<td>" . $row['age'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado.";
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
