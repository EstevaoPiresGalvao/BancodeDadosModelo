<?php
include 'config/conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id = $id ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$id = $id ?: 0;
$erro = '';
$stmt = mysqli_prepare($conexao, "SELECT id, nome FROM alunos WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$aluno = mysqli_fetch_assoc($resultado);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!$aluno) {
$erro = 'Aluno não encontrado.';
} else {
$stmt_delete = mysqli_prepare($conexao, "DELETE FROM alunos WHERE id = ?");
mysqli_stmt_bind_param($stmt_delete, 'i', $id);
if (mysqli_stmt_execute($stmt_delete)) {
header('Location: index.php');
exit;
}
$erro = 'Erro ao excluir o aluno: ' . mysqli_stmt_error($stmt_delete);
}
}
include 'includes/header.php';
?>
<?php if (!$aluno): ?>
<div class="alert alert-danger">Aluno não encontrado.</div>
<a href="index.php" class="btn btn-primary">Voltar</a>
<?php else: ?>

<div class="card shadow-sm">
<div class="card-header bg-white py-3">
<h4 class="mb-0 text-danger"><i class="bi bi-trash me-2"></i>Excluir Aluno</h4>
</div>
<div class="card-body">
<?php if ($erro !== ''): ?>
<div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>
<p>Deseja realmente excluir o aluno <strong><?= htmlspecialchars($aluno['nome']) ?></strong>?</p>
<form method="POST" action="excluir.php">
<input type="hidden" name="id" value="<?= (int) $id ?>">
<button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>
</div>
</div>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>