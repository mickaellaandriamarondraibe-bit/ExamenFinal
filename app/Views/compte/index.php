<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Comptes clients</h2>

    <a href="<?= base_url('compte/create') ?>" class="btn btn-primary">
        Ajouter
    </a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Numéro de compte</th>
            <th>Solde</th>
            <th>État</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($comptes as $compte): ?>
            <tr>
                <td><?= $compte['id'] ?></td>

                <td>
                    <?= esc($compte['nom']) ?>
                    <?= esc($compte['prenom']) ?>
                </td>

                <td><?= esc($compte['numero_compte']) ?></td>

                <td><?= $compte['solde'] ?> Ar</td>

                <td>
                    <?= $compte['actif'] == 1 ? 'Actif' : 'Inactif' ?>
                </td>

                <td>
                    <a
                        href="<?= base_url('compte/edit/' . $compte['id']) ?>"
                        class="btn btn-warning btn-sm"
                    >
                        Modifier
                    </a>

                    <a
                        href="<?= base_url('compte/delete/' . $compte['id']) ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Supprimer ce compte ?')"
                    >
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>