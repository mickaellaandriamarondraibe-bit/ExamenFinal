<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2 class="mb-4">Situation des gains</h2>

<div class="row mb-4">

    <div class="col-md-6">

        <div class="card border-success">

            <div class="card-header">
                Gains internes
            </div>

            <div class="card-body">

                <h3>
                    <?= number_format($gainsInternes['total'] ?? 0, 0, ',', ' ') ?>
                    Ar
                </h3>

            </div>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header">
        Gains inter-opérateurs
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Opérateur</th>
                    <th>Frais</th>
                    <th>Commission</th>
                    <th>Gain total</th>
                </tr>
            </thead>

            <tbody>

            <?php if (!empty($gainsInterOperateurs)): ?>

                <?php foreach ($gainsInterOperateurs as $gain): ?>

                    <tr>
                        <td><?= esc($gain['nom']) ?></td>

                        <td>
                            <?= number_format($gain['total_frais'], 0, ',', ' ') ?> Ar
                        </td>

                        <td>
                            <?= number_format($gain['total_commission'], 0, ',', ' ') ?> Ar
                        </td>

                        <td>
                            <?= number_format($gain['gain_total'], 0, ',', ' ') ?> Ar
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4" class="text-center">
                        Aucun gain inter-opérateur.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>