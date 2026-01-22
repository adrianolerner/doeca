<?php
require 'config.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM edicoes WHERE id = ?");
$stmt->execute([$id]);
$edicao = $stmt->fetch();

if(!$edicao) die("Edição não encontrada.");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Leitura - Edição <?php echo $edicao['numero_edicao']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; overflow: hidden; }
        .iframe-container { height: calc(100vh - 60px); width: 100%; border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary px-3" style="height: 60px;">
        <span class="navbar-brand mb-0 h1">Edição nº <?php echo $edicao['numero_edicao']; ?> - <?php echo date('d/m/Y', strtotime($edicao['data_publicacao'])); ?></span>
        <a href="index.php" class="btn btn-light btn-sm">Voltar</a>
    </nav>
    
    <iframe src="uploads/<?php echo $edicao['arquivo_path']; ?>" class="iframe-container"></iframe>
</body>
</html>