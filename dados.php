<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Informações</title>
  <link href="style.css" rel="stylesheet" type="text/css"/>
  <script src="/script.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
  <h1>Informações sobre Diabetes</h1>

   <form id="filter-form" action="dados.php" method="post">
    <label for="gener">Filtrar por Gênero:</label>
    <select class="form-select" aria-label="Default select example" name="gener" id="gener">
      <option selected value="">Todos</option>
      <option value="F">Feminino</option>
      <option value="M">Masculino</option>
    </select>

    <label for="diabetes">Filtrar por Tipo de Diabetes:</label>
    <select class="form-select" aria-label="Default select example" name="diabetes" id="diabetes">
      <option value="">Todos</option>
      <option value="tipo1">Tipo 1</option>
      <option value="tipo2">Tipo 2</option>
    </select>

    <label>Selecione nome_Sintomas:</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="aumento_sede"  name="nome_sintomas[]" value="aumento_sede">
      <label class="form-check-label" for="aumento_sede">Aumento de sede</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="urinacao_frequente"  name="nome_sintomas[]" value="urinacao_frequente">
      <label class="form-check-label" for="urinacao_frequente">Urinação frequente</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="cansaco"  name="nome_sintomas[]" value="cansaco">
      <label class="form-check-label" for="cansaco">Cansaço</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="perda_peso"  name="nome_sintomas[]" value="perda_peso">
      <label class="form-check-label" for="perda_peso">Perda de peso repentina</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="obesidade"  name="nome_sintomas[]" value="obesidade">
      <label class="form-check-label" for="obesidade">Obesidade</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="fraqueza"  name="nome_sintomas[]" value="fraqueza">
      <label class="form-check-label" for="fraqueza">Fraqueza</label>
    </div>

    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="embaracamento_visual"  name="nome_sintomas[]" value="embaracamento_visual">
      <label class="form-check-label" for="embaracamento_visual">Embaçamento visual</label>
    </div>

    <input type="submit" value="Ver dados">
  </form>

  <div id="resultados">
    <h2>Dados dos pacientes</h2>

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

    // Verifique se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $gener = isset($_POST['gener']) ? mysqli_real_escape_string($conn, $_POST['gener']) : '';
        $tipo_diabetes = isset($_POST['diabetes']) ? mysqli_real_escape_string($conn, $_POST['diabetes']) : '';
        $nome_sintomas = isset($_POST['nome_sintomas']) ? $_POST['nome_sintomas'] : [];
        
           // Consulta SQL para exibir todos os dados
    $sql = "SELECT * FROM pacientes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Gênero</th>";
        echo "<th>Idade</th>";
        echo "<th>Tipo de Diabetes</th>";
        echo "<th>nome_Sintomas</th>";
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['gener'] . "</td>";
            echo "<td>" . $row['age'] . "</td>";
            echo "<td>" . $row['diabetes'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado.";
    }
    } else {
        // Consulta SQL para exibir todos os dados quando o formulário não foi enviado
        $sql = "SELECT * FROM pacientes";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Gênero</th>";
            echo "<th>Idade</th>";
            echo "<th>Tipo de Diabetes</th>";
            echo "<th>nome_Sintomas</th>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['gener'] . "</td>";
                echo "<td>" . $row['age'] . "</td>";
                echo "<td>" . $row['diabetes'] . "</td>";
        
                // Recupere os nome_sintomas do paciente
                $pacienteId = $row['idpacientes'];
                $nome_sintomasQuery = "SELECT nome_sintomas FROM pacientes_nome_sintomas WHERE idpaciente = $pacienteId";
                $nome_sintomasResult = $conn->query($nome_sintomasQuery);
        
                if ($nome_sintomasResult->num_rows > 0) {
                    echo "<td>";
                    while ($sintomaRow = $nome_sintomasResult->fetch_assoc()) {
                        echo $sintomaRow['nome_sintomas'] . "<br>";
                    }
                    echo "</td>";
                } else {
                    echo "<td>Nenhum sintoma registrado.</td>";
                }
        
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhum resultado encontrado.";
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

  </div>
</body>
</html>
