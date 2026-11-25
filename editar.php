<?php
include 'conexao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id = $id ?: 0;
$erro = '';
$sql_aluno = "SELECT * FROM alunos WHERE id = ? LIMIT 1";
$stmt_aluno = mysqli_prepare($conexao, $sql_aluno);
mysqli_stmt_bind_param($stmt_aluno, 'i', $id);
mysqli_stmt_execute($stmt_aluno);
$resultado = mysqli_stmt_get_result($stmt_aluno);
$aluno = mysqli_fetch_assoc($resultado);
if (!$aluno) {
include 'includes/header.php';
echo '<div class="alert alert-danger">Aluno não encontrado.</div>';
echo '<a href="index.php" class="btn btn-primary">Voltar</a>';
include 'includes/footer.php';

EEEM Dr. Silva Mello Valor: Nota:
Professor: Cláudio Batista Disciplina: Banco de dados

Aluno (a):
Instruções:

Data: / / 2026 Turma:

exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$data_nascimento = trim($_POST['data_nascimento'] ?? '');
$curso = trim($_POST['curso'] ?? '');
if ($nome === '' || $email === '' || $telefone === '' || $data_nascimento === '' || $curso === '') {
$erro = 'Todos os campos são obrigatórios.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$erro = 'Informe um e-mail válido.';
} else {
$sql_update = "UPDATE alunos
SET nome = ?, email = ?, telefone = ?, data_nascimento = ?, curso = ?
WHERE id = ?";
$stmt_update = mysqli_prepare($conexao, $sql_update);
mysqli_stmt_bind_param(
$stmt_update,
'sssssi',
$nome,
$email,
$telefone,
$data_nascimento,
$curso,
$id
);
if (mysqli_stmt_execute($stmt_update)) {
header('Location: index.php');
exit;
}
$erro = 'Erro ao atualizar o aluno: ' . mysqli_stmt_error($stmt_update);
}
$aluno = [
'id' => $id,
'nome' => $nome,
'email' => $email,
'telefone' => $telefone,
'data_nascimento' => $data_nascimento,
'curso' => $curso,
];
}
include 'includes/header.php';
?>
<div class="card shadow-sm">
<div class="card-header bg-white py-3">
<h4 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Editar Aluno</h4>
</div>
<div class="card-body">
<?php if ($erro !== ''): ?>
<div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>
<form method="POST" action="editar.php?id=<?= (int) $id ?>" class="row g-3">
<div class="col-md-6">
<label for="nome" class="form-label">Nome</label>
<input type="text" id="nome" name="nome" class="form-control" required value="<?=
htmlspecialchars($aluno['nome']) ?>">
</div>
<div class="col-md-6">
<label for="email" class="form-label">E-mail</label>

<input type="email" id="email" name="email" class="form-control" required value="<?=
htmlspecialchars($aluno['email']) ?>">
</div>
<div class="col-md-6">
<label for="telefone" class="form-label">Telefone</label>
<input type="text" id="telefone" name="telefone" class="form-control" required value="<?=
htmlspecialchars($aluno['telefone']) ?>">
</div>
<div class="col-md-6">
<label for="data_nascimento" class="form-label">Data de nascimento</label>
<input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required value="<?=
htmlspecialchars($aluno['data_nascimento']) ?>">
</div>
<div class="col-md-12">
<label for="curso" class="form-label">Curso</label>
<input type="text" id="curso" name="curso" class="form-control" required value="<?=
htmlspecialchars($aluno['curso']) ?>">
</div>
<div class="col-12">
<button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Salvar alterações</button>
<a href="index.php" class="btn btn-secondary">Cancelar</a>
</div>
</form>
</div>
</div>
<?php include 'footer.php'; ?>
