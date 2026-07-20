<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Barèmes de frais</h2>

    <a href="<?= base_url('operateur/baremes-frais/create') ?>" class="btn btn-primary">
        Ajouter
    </a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type d'opération</th>
            <th>Montant minimum</th>
            <th>Montant maximum</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($baremes as $bareme): ?>
            <tr>
                <td><?= $bareme['id'] ?></td>
                <td><?= esc($bareme['type_operation']) ?></td>
                <td><?= $bareme['montant_min'] ?></td>
                <td><?= $bareme['montant_max'] ?></td>
                <td><?= $bareme['frais'] ?></td>

                <td>
                    <a href="<?= base_url('operateur/baremes-frais/edit/' . $bareme['id']) ?>" class="btn btn-warning btn-sm">
                        Modifier
                    </a>

                    <a href="<?= base_url('operateur/baremes-frais/delete/' . $bareme['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce barème ?')">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>