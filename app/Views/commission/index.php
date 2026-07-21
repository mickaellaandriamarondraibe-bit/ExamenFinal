<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h2 class="mb-1">Commissions inter-opérateurs</h2>
                            <p class="text-muted mb-0">Gérez les pourcentages appliqués aux transferts externes.</p>
                        </div>

                        <a href="<?= base_url('operateur/commissions/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Nouvelle commission
                        </a>

                    </div>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Opérateur</th>
                                    <th>Pourcentage</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($commissions)) : ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Aucune commission configurée.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($commissions as $c) : ?>
                                        <tr>
                                            <td><?= esc($c['autre_operateur'] ?? $c['autre_operateur_id']) ?></td>
                                            <td><?= esc($c['pourcentage']) ?> %</td>
                                            <td class="text-end">
                                                <a href="<?= base_url('operateur/commissions/edit/' . $c['id']) ?>" class="btn btn-sm btn-light">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= base_url('operateur/commissions/delete/' . $c['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette commission ?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>