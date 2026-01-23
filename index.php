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

    <style>
        /* Estilo Google-like para a barra de pesquisa */
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .google-search-bar {
            border-radius: 50px;
            /* Bordas bem arredondadas */
            padding: 12px 20px 12px 50px;
            /* Espaço para o ícone */
            border: 1px solid #dfe1e5;
            box-shadow: 0 1px 6px rgba(32, 33, 36, 0.28);
            transition: all 0.3s;
            font-size: 1.1rem;
        }

        .google-search-bar:focus {
            box-shadow: 0 1px 6px rgba(32, 33, 36, 0.4);
            border-color: transparent;
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
            font-size: 1.2rem;
        }

        /* Ajuste do card para combinar com o admin */
        .card-custom {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 px-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-book-open"></i> DOECA</a>
            <div class="d-flex">
                <a href="admin/login.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-lock"></i> Área Administrativa
                </a>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="text-center mb-5 mt-4">
            <h2 class="text-primary mb-4 fw-bold">Consulta de Diários Oficiais</h2>

            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="customSearch" class="form-control google-search-bar"
                    placeholder="Pesquise por número, ano ou data...">
            </div>
        </div>

        <div class="card card-custom bg-white">
            <div class="card-body p-4">
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
                                            <a href='visualizar.php?id={$row['id']}' class='btn btn-sm btn-info text-white me-1' title='Visualizar'>
                                                <i class='fas fa-eye'></i> Ler Edição
                                            </a>
                                            <a href='arquivo.php?id={$row['id']}' download class='btn btn-sm btn-secondary' title='Baixar PDF'>
                                                <i class='fas fa-download'></i> Baixar Edição
                                            </a>
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

    <footer class="text-center mt-5 py-4 text-muted">
        <small>© <?php echo date('Y'); ?> Adriano Lerner Biesek | Prefeitura Municipal de Castro (PR)<br>Feito com <i
                class="fa fa-heart text-danger"></i> para o serviço público.</small>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializa o DataTables
            var table = $('#tabelaDiario').DataTable({
                // 'lrtip' define os elementos que aparecem
                // removido o 'f' para usar a busca customizada
                dom: '<"row mb-3"<"col-md-6"l>>rtip',
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                order: [
                    [1, "desc"]
                ] // Ordena pela data de publicação decrescente
            });

            // Lógica da Barra de Pesquisa Personalizada "Google Style"
            $('#customSearch').on('keyup', function () {
                table.search(this.value).draw();
            });
        });
    </script>
</body>

</html>