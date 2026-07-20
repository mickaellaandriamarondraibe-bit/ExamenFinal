<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Configuration des préfixes</h1>
        <div class="page-subtitle">
            Gestion des préfixes téléphoniques autorisés
        </div>
    </div>

    <a href="<?= base_url('prefixes/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouveau préfixe
    </a>
</div>

<div class="content-card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Préfixe</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($prefixes as $prefixe): ?>
                    <tr>
                        <td><?= $prefixe['id'] ?></td>

                        <td><?= esc($prefixe['prefixe']) ?></td>

                        <td>
                            <?php if ($prefixe['actif'] == 1): ?>
                                <span class="status-active">Actif</span>
                            <?php else: ?>
                                <span class="status-inactive">Inactif</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a
                                href="<?= base_url('prefixes/edit/' . $prefixe['id']) ?>"
                                class="btn btn-warning btn-sm"
                            >
                                <i class="bi bi-pencil"></i>
                                Modifier
                            </a>

                            <a
                                href="<?= base_url('prefixes/delete/' . $prefixe['id']) ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Supprimer ce préfixe ?')"
                            >
                                <i class="bi bi-trash"></i>
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>