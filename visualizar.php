<?php
include 'conexao.php';
$id = intval($_GET['id'] ?? 0);

$sql = "SELECT * FROM alunos WHERE id = $id";
$resultado = mysqli_query($conexao, $sql);
$aluno = mysqli_fetch_assoc($resultado);

if (!$aluno) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Aluno não encontrado!</div><a href='index.php' class='btn btn-primary'>Voltar</a></div>";
    exit;
}

include 'header.php';
?>
<!-- Estrutura de exibição dos detalhes do aluno -->
