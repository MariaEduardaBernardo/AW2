<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudante1";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $genero = isset($_POST['genero']) ? mysqli_real_escape_string($conn, $_POST['genero']) : 'todos';
    $tipo_diabetes = isset($_POST['tipo_diabetes']) ? mysqli_real_escape_string($conn, $_POST['tipo_diabetes']) : 'todos';
    $sintomas = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];

    $sql = "SELECT campo1, campo2 FROM pacientes WHERE 1 = 1";

    if ($genero != 'todos') {
        $sql .= " AND genero = ?";
    }

    if ($tipo_diabetes != 'todos') {
        $sql .= " AND tipo_diabetes = ?";
    }

    if (!empty($sintomas)) {
        $placeholders = implode(', ', array_fill(0, count($sintomas), '?'));
        $sql .= " AND sintoma IN ($placeholders)";
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Erro na preparação da consulta: " . $conn->error);
    }

    if ($genero != 'todos') {
        $stmt->bind_param("s", $genero);
    }

    if ($tipo_diabetes != 'todos') {
        $stmt->bind_param("s", $tipo_diabetes);
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
            echo "<td>" . $row['campo1'] . "</td>";
            echo "<td>" . $row['campo2'] . "</td>";
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
