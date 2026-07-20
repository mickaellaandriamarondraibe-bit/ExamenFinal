<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Barèmes de frais</h2>

    <a href="<?= base_url('operateur/baremes-frais/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Ajouter
    </a>

</div>


<table class="table table-bordered table-striped align-middle">

    <thead>
        <tr>
            <th>ID</th>
            <th>Type d'opération</th>
            <th>Opérateur</th>
            <th>Montant minimum</th>
            <th>Montant maximum</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
    </thead>


    <tbody>

        <?php if (!empty($baremes)): ?>

            <?php foreach ($baremes as $bareme): ?>

                <tr>

                    <td>
                        <?= esc($bareme['id']) ?>
                    </td>


                    <td>
                        <?= esc($bareme['type_operation']) ?>
                    </td>


                    <td>

                        <?php if (!empty($bareme['autre_operateur'])): ?>

                            <span class="badge bg-info">
                                <?= esc($bareme['autre_operateur']) ?>
                            </span>

                        <?php else: ?>

                            <span class="badge bg-success">
                                Interne
                            </span>

                        <?php endif; ?>

                    </td>


                    <td>
                        <?= number_format($bareme['montant_min'], 0, ',', ' ') ?>
                        Ar
                    </td>


                    <td>
                        <?= number_format($bareme['montant_max'], 0, ',', ' ') ?>
                        Ar
                    </td>


                    <td>
                        <?= number_format($bareme['frais'], 0, ',', ' ') ?>
                        Ar
                    </td>


                    <td>

                        <a href="<?= base_url('operateur/baremes-frais/edit/' . $bareme['id']) ?>"
                           class="btn btn-warning btn-sm">

                            <i class="bi bi-pencil"></i>
                            Modifier

                        </a>


                        <a href="<?= base_url('operateur/baremes-frais/delete/' . $bareme['id']) ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce barème ?')">

                            <i class="bi bi-trash"></i>
                            Supprimer

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>


        <?php else: ?>

            <tr>

                <td colspan="7" class="text-center">

                    Aucun barème trouvé.

                </td>

            </tr>

        <?php endif; ?>


    </tbody>

</table>


<?= $this->endSection() ?>