<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOECA - Diário Oficial Eletrônico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-book-open"></i> DOECA</a>
            <div class="d-flex">
                <a href="admin/login.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-lock"></i> Área Administrativa
                </a>
            </div>
        </div>
    </nav>

    <div class="container bg-white p-4 rounded shadow-sm">
        <h2 class="mb-4 text-center text-primary">Edições Publicadas</h2>
        <hr>
        
        <div class="table-responsive">
            <table id="tabelaDiario" class="table table-striped table-hover" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Nº Edição</th>
                        <th>Data Publicação</th>
                        <th>Data Upload</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM edicoes ORDER BY data_publicacao DESC, id DESC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $dataPub = date('d/m/Y', strtotime($row['data_publicacao']));
                        $dataUp = date('d/m/Y H:i', strtotime($row['criado_em']));
                        echo "<tr>
                                <td><strong>{$row['numero_edicao']}</strong></td>
                                <td>{$dataPub}</td>
                                <td>{$dataUp}</td>
                                <td class='text-center'>
                                    <a href='visualizar.php?id={$row['id']}' class='btn btn-sm btn-info text-white' title='Visualizar'>
                                        <i class='fas fa-eye'></i> Ler
                                    </a>
                                    <a href='uploads/{$row['arquivo_path']}' download class='btn btn-sm btn-secondary' title='Baixar PDF'>
                                        <i class='fas fa-download'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center mt-5 py-3 text-muted">
        <small>Desenvolvido com DOECA (Código Aberto)</small>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#tabelaDiario').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                order: [[ 1, "desc" ]] // Ordena pela data decrescente
            });
        });
    </script>
</body>
</html>