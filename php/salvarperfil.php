<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "conexao.php";


if (!isset($_SESSION['idAluno'])) {
    http_response_code(401);
    echo "Usuário não autenticado.";
    exit;
}

$idAluno = (int) $_SESSION['idAluno'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$sobrevoce = trim($_POST['sobrevoce'] ?? '');

// atualiza nome e email na tabela aluno 
$sqlAluno = "UPDATE Aluno SET nome = ?, email = ? WHERE idAluno = ?";
$stmtAluno = $conn->prepare($sqlAluno);
if (!$stmtAluno) {
    echo "Erro prepare (sqlAluno): " . $conn->error;
    exit;
}
$stmtAluno->bind_param("ssi", $nome, $email, $idAluno);
$stmtAluno->execute();
$stmtAluno->close();

// verifica se já existe perfil
$sqlCheck = "SELECT idPerfil FROM Perfil WHERE Aluno_idAluno = ?";
$stmtCheck = $conn->prepare($sqlCheck);
if (!$stmtCheck) {
    echo "Erro prepare (sqlCheck): " . $conn->error;
    exit;
}
$stmtCheck->bind_param("i", $idAluno);
$stmtCheck->execute();
$stmtCheck->store_result();
$perfilExiste = $stmtCheck->num_rows > 0;
$stmtCheck->close();

// le curriculo e foto
$foto = null;
$curriculo = null;

if (!empty($_FILES['foto']['tmp_name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
    if ($_FILES['foto']['size'] > 4 * 1024 * 1024) { //  tamanho da foto
        echo "A imagem é muito grande (máx 4 MB).";
        exit;
    }
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
}

if (!empty($_FILES['curriculo']['tmp_name']) && is_uploaded_file($_FILES['curriculo']['tmp_name'])) {
    if ($_FILES['curriculo']['size'] > 10 * 1024 * 1024) { // tamanho do curriculo
        echo "O currículo é muito grande (máx 10 MB).";
        exit;
    }
    $curriculo = file_get_contents($_FILES['curriculo']['tmp_name']);
}

//transforma um array normal em um array de referências se nao da erro na consulta do banco de dados
function refValues($arr){
    $refs = [];
    foreach ($arr as $key => $value) $refs[$key] = &$arr[$key];
    return $refs;
}

//atualiz perfil
if ($perfilExiste) {
   
    $fields = [];
    $params = [];
    $types = '';

    $fields[] = "sobrevoc = ?";
    $params[] = $sobrevoce;
    $types .= 's';

    if ($foto !== null) {
        $fields[] = "foto = ?";
        $params[] = $foto;
        $types .= 's'; 
    }

    if ($curriculo !== null) {
        $fields[] = "curriculo = ?";
        $params[] = $curriculo;
        $types .= 's';
    }

    $sql = "UPDATE Perfil SET " . implode(", ", $fields) . " WHERE Aluno_idAluno = ?";
    $types .= 'i';
    $params[] = $idAluno;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Erro prepare (update): " . $conn->error;
        exit;
    }

    $bind_names = [];
    $bind_names[] = $types;
    foreach ($params as $k => $v) $bind_names[] = $v;
    $bind_result = call_user_func_array([$stmt, 'bind_param'], refValues($bind_names));

    if ($bind_result === false) {
        echo "Erro bind_param (update): " . $stmt->error;
        exit;
    }

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso!";
    } else {
        echo "Erro ao executar UPDATE: " . $stmt->error;
    }

    $stmt->close();
} else {
 
    $fields = ['sobrevoc', 'Aluno_idAluno'];
    $placeholders = ['?', '?'];
    $params = [$sobrevoce, $idAluno];
    $types = 'si';

    if ($foto !== null) {
        array_splice($fields, 1, 0, 'foto'); 
        array_splice($placeholders, 1, 0, '?');
        array_splice($params, 1, 0, $foto);
        $types = 'ssi';
    }

    if ($curriculo !== null) {
        
        $idx = count($fields) - 1; 
        array_splice($fields, $idx, 0, 'curriculo');
        array_splice($placeholders, $idx, 0, '?');
        array_splice($params, $idx, 0, $curriculo);
       
    }

    
    $types = '';
    foreach ($params as $p) {
        if (is_int($p)) $types .= 'i';
        else $types .= 's';
    }
//se n existe perfil
    $sql = "INSERT INTO Perfil (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Erro prepare (insert): " . $conn->error;
        exit;
    }

    $bind_names = [];
    $bind_names[] = $types;
    foreach ($params as $k => $v) $bind_names[] = $v;
    $bind_result = call_user_func_array([$stmt, 'bind_param'], refValues($bind_names));
    if ($bind_result === false) {
        echo "Erro bind_param (insert): " . $stmt->error;
        exit;
    }

    if ($stmt->execute()) {
        echo "Perfil criado com sucesso!";
    } else {
        echo "Erro ao executar INSERT: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
exit;
?>
