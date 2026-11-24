<?php
include 'config/conexao.php';
include 'includes/header.php';

$busca = $_GET['busca'] ?? '';

if ($busca != '') {
    $busca_segura = mysqli_real_escape_string($conexao, $busca);
    $sql = "SELECT * FROM alunos WHERE nome LIKE '%$busca_segura%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM alunos ORDER BY id DESC";
}

$resultado = mysqli_query($conexao, $sql);
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-primary"><i class="bi bi-people-fill me-2"></i>Lista de Alunos</h4>
        <a href="cadastrar.php" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Novo Aluno</a>
    </div>
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-2 mb-4">
            <div class="col-md-9">
                <input type="text" name="busca" class="form-control" placeholder="Pesquisar aluno pelo nome..." value="<?= htmlspecialchars($busca) ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
                <?php if ($busca != ''): ?>
                    <a href="index.php" class="btn btn-outline-secondary">Limpar</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Curso</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultado) > 0): ?>
                        <?php while ($aluno = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?= $aluno['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($aluno['nome']) ?></td>
                                <td><?= htmlspecialchars($aluno['email']) ?></td>
                                <td><?= htmlspecialchars($aluno['telefone']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($aluno['curso']) ?></span></td>
                                <td class="text-center">
                                    <a href="visualizar.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-outline-info" title="Visualizar"><i class="bi bi-eye"></i></a>
                                    <a href="editar.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <a href="excluir.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Deseja realmente excluir este aluno?');"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Nenhum aluno encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>