<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Types d’opérations</h2>

            <p class="text-muted mb-0">
                Gestion des types d’opérations du système
            </p>
        </div>

        <a
            href="<?= base_url('operateur/types-operations/create') ?>"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg"></i>
            Nouveau type
        </a>

    </div>

    <?php if (session()->getFlashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?= esc(session()->getFlashdata('success')) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?= esc(session()->getFlashdata('error')) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($types)): ?>

                            <?php foreach ($types as $type): ?>

                                <tr>

                                    <td>
                                        <?= esc($type['id']) ?>
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            <?= esc($type['libelle']) ?>
                                        </span>
                                    </td>

                                    <td class="text-end">

                                        <a
                                            href="<?= base_url(
                                                'operateur/types-operations/edit/' .
                                                $type['id']
                                            ) ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                            Modifier
                                        </a>

                                        <a
                                            href="<?= base_url(
                                                'types-operations/delete/' .
                                                $type['id']
                                            ) ?>"
                                            class="btn btn-danger btn-sm btn-delete"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Supprimer
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >
                                    Aucun type d’opération enregistré.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>