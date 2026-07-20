<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Comptes clients</h2>

    <a href="<?= base_url('operateur/compte/create') ?>" class="btn btn-primary">
        Ajouter
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numéro de compte</th>
                <th>Solde</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($comptes)): ?>

                <?php foreach ($comptes as $compte): ?>
                    <tr>
                        <td><?= esc($compte['id']) ?></td>

                        <td>
                            <?= esc($compte['numero_compte']) ?>
                        </td>

                        <td>
                            <?= number_format($compte['solde'], 0, ',', ' ') ?> Ar
                        </td>

                        <td>
                            <?php if ($compte['actif'] == 1): ?>
                                <span class="badge bg-success">
                                    Actif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    Inactif
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="<?= base_url('operateur/compte/historique/' . $compte['id']) ?>" class="btn btn-info btn-sm">
                                Voir
                            </a>

                            <a href="<?= base_url('operateur/compte/edit/' . $compte['id']) ?>" class="btn btn-warning btn-sm">
                                Modifier
                            </a>

                            <a href="<?= base_url('operateur/compte/delete/' . $compte['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce compte ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" class="text-center">
                        Aucun compte trouvé.
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>