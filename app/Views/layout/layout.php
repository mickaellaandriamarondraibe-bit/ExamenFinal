<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?? 'Mobile Money' ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS --><link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<script src="<?= base_url('assets/js/app.js') ?>"></script>

</head>

<body>

    <?= $this->include('layout/sidebar') ?>

    <div class="main-wrapper">

        <?= $this->include('layout/header') ?>

        <main class="content">

            <?= $this->renderSection('content') ?>

        </main>

        <?= $this->include('layout/footer') ?>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS -->
    <script src="<?= base_url('js/app.js') ?>"></script>

</body>

</html>