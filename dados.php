<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudante1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

$genero = isset($_POST['genero']) ? mysqli_real_escape_string($conn, $_POST['genero']) : 'todos';
$tipo_diabetes = isset($_POST['tipo_diabetes']) ? mysqli_real_escape_string($conn, $_POST['tipo_diabetes']) : 'todos';
$sintomas = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];

$sql = "SELECT * FROM tabela_de_dados WHERE 1 = 1";

if ($genero != 'todos') {
    $sql .= " AND genero = '$genero'";
}

if ($tipo_diabetes != 'todos') {
    $sql .= " AND tipo_diabetes = '$tipo_diabetes'";
}

if (!empty($sintomas)) {
    $sintomasCondicao = implode("','", $sintomas);
    $sql .= " AND sintoma IN ('$sintomasCondicao')";
}

$result = $conn->query($sql);

// Exibir os resultados no HTML
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

$conn->close();
?>
