<?php
include 'conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');    $curso = trim($_POST['curso'] ?? '');

    if (empty($nome) || empty($email) || empty($telefone) || empty($data_nascimento) || empty($curso)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido!";
    } else {
        $stmt_verificar = mysqli_prepare($conexao, "SELECT id FROM alunos WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_verificar, 's', $email);

        if (!mysqli_stmt_execute($stmt_verificar)) {
            $erro = "Erro ao verificar o e-mail: " . mysqli_stmt_error($stmt_verificar);
        } else {
            $resultado_email = mysqli_stmt_get_result($stmt_verificar);

            if (mysqli_num_rows($resultado_email) > 0) {
                $erro = "Já existe um aluno cadastrado com este e-mail!";
            } else {
                $sql = "INSERT INTO alunos (nome, email, telefone, data_nascimento, curso)
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexao, $sql);
                mysqli_stmt_bind_param($stmt, 'sssss', $nome, $email, $telefone, $data_nascimento, $curso);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: index.php");
                    exit;
                } elseif (mysqli_stmt_errno($stmt) === 1062) {
                    $erro = "Já existe um aluno cadastrado com este e-mail!";
                } else {
                    $erro = "Erro ao cadastrar: " . mysqli_stmt_error($stmt);
                }
            }
        }
    }
}
include 'includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 text-success"><i class="bi bi-person-plus me-2"></i>Cadastrar Aluno</h4>
    </div>
    <div class="card-body">
        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="cadastrar.php" class="row g-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" required value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label for="data_nascimento" class="form-label">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>">
            </div>
            <div class="col-md-12">
                <label for="curso" class="form-label">Curso</label>
                <input type="text" id="curso" name="curso" class="form-control" required value="<?= htmlspecialchars($_POST['curso'] ?? '') ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Cadastrar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
