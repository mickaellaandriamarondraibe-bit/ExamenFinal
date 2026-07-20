<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Configuration des préfixes</h3>

        <a href="<?= base_url('prefixes/create') ?>" class="btn btn-primary">
            Nouveau préfixe
        </a>
    </div>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Préfixe</th>
                <th>Etat</th>
                <th width="180">Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach($prefixes as $prefix): ?>

            <tr>

                <td><?= $prefix['id'] ?></td>

                <td><?= $prefix['prefixe'] ?></td>

                <td>
                    <?= $prefix['actif'] ? 'Actif' : 'Inactif' ?>
                </td>

                <td>

                    <a href="<?= base_url('prefixes/edit/'.$prefix['id']) ?>" class="btn btn-warning btn-sm">
                        Modifier
                    </a>

                    <a href="<?= base_url('prefixes/delete/'.$prefix['id']) ?>" class="btn btn-danger btn-sm">
                        Supprimer
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?= $this->endSection() ?>