<?php
include 'conexao.php';

// pega as vagas pendentes (aguardando) do banco de dados
$sql = "SELECT  v.idVaga, v.titulo, e.nome AS nomeEmpresa FROM vaga v JOIN empresa e ON v.Empresa_idEmpresa = e.idEmpresa  WHERE v.status = 'aguardando'";

$result = $conn->query($sql);

if (!$result) {
    die('Erro na consulta: ' . $conn->error);
}

if ($result->num_rows > 0) { //consulta retorno no banco
    echo "<ul class='vagas-lista'>"; //cria alista 
    while ($row = $result->fetch_assoc()) { //percorre cada instrução retornadda pro banco
        echo "<li>
                <strong>{$row['titulo']}</strong> - {$row['nomeEmpresa']} 
                <a href='php/detalharVaga.php?id={$row['idVaga']}'>Ver detalhes</a>
              </li>"; 
    }
    echo "</ul>";
    //imprime a lista depois de retornar os dados pro banco e tals
} else {
    echo "<p>Nenhuma vaga pendente encontrada.</p>";
}

$conn->close();
?>
