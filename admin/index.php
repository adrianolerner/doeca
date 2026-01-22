<?php
require 'auth.php'; 
require '../config.php';

$msg = "";

// EXCLUSÃO
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $stmt = $pdo->prepare("SELECT arquivo_path FROM edicoes WHERE id = ?");
    $stmt->execute([$id]);
    $edicao = $stmt->fetch();

    if ($edicao) {
        $caminhoArquivo = "../uploads/" . $edicao['arquivo_path'];
        $stmtDelete = $pdo->prepare("DELETE FROM edicoes WHERE id = ?");
        if ($stmtDelete->execute([$id])) {
            if (file_exists($caminhoArquivo)) unlink($caminhoArquivo);
            $msg = "<div class='alert alert-success alert-dismissible fade show'>Edição excluída! <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// UPLOAD
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdf_file'])) {
    $numero = $_POST['numero'];
    $data = $_POST['data'];
    $ext = pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION);
    
    if(strtolower($ext) === 'pdf') {
        $novoNome = uniqid() . ".pdf";
        $destino = "../uploads/" . $novoNome;
        if(move_uploaded_file($_FILES['pdf_file']['tmp_name'], $destino)) {
            $stmt = $pdo->prepare("INSERT INTO edicoes (numero_edicao, data_publicacao, arquivo_path) VALUES (?, ?, ?)");
            $stmt->execute([$numero, $data, $novoNome]);
            $msg = "<div class='alert alert-success alert-dismissible fade show'>Publicado com sucesso! <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>Apenas PDF permitido.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - DOECA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 px-3">
        <a class="navbar-brand" href="#">Painel DOECA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Publicações</a></li>
                <?php if($_SESSION['usuario_nivel'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Gerenciar Usuários</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text me-3 text-white">Olá, <?php echo $_SESSION['usuario_nome']; ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-plus-circle"></i> Nova Publicação
                    </div>
                    <div class="card-body">
                        <?php echo $msg; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número da Edição</label>
                                <input type="text" name="numero" class="form-control" placeholder="Ex: 1234/2023" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Data da Publicação</label>
                                <input type="date" name="data" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Arquivo PDF</label>
                                <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-upload"></i> Publicar
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-3 d-grid">
                    <a href="../index.php" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-external-link-alt"></i> Ver Site Principal
                    </a>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="card shadow border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-list"></i> Gerenciar Edições</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaAdmin" class="table table-striped table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Edição</th>
                                        <th>Data</th>
                                        <th>Arquivo</th>
                                        <th class="text-center" width="150">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM edicoes ORDER BY data_publicacao DESC, id DESC");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $dataPub = date('d/m/Y', strtotime($row['data_publicacao']));
                                        echo "<tr>
                                                <td>{$row['numero_edicao']}</td>
                                                <td>{$dataPub}</td>
                                                <td><a href='../uploads/{$row['arquivo_path']}' target='_blank' class='text-decoration-none'><i class='fas fa-file-pdf text-danger'></i> PDF</a></td>
                                                <td class='text-center'>
                                                    <a href='editar.php?id={$row['id']}' class='btn btn-sm btn-warning' title='Editar'><i class='fas fa-edit'></i></a>
                                                    <a href='index.php?excluir={$row['id']}' class='btn btn-sm btn-danger' title='Excluir' onclick=\"return confirm('Confirmar exclusão?');\"><i class='fas fa-trash'></i></a>
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#tabelaAdmin').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json' },
                order: [[ 1, "desc" ]] 
            });
        });
    </script>
</body>
</html>