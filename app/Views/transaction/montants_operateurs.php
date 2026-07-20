<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2 class="mb-4">
    Situation des montants à envoyer
</h2>

<div class="card">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Opérateur</th>
                    <th>Montant total dû (Ar)</th>
                </tr>

            </thead>

            <tbody>

            <?php if (!empty($montants)): ?>

                <?php foreach ($montants as $montant): ?>

                    <tr>

                        <td>
                            <?= esc($montant['nom']) ?>
                        </td>

                        <td>
                            <?= number_format($montant['montant_total'], 0, ',', ' ') ?>
                            Ar
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="2" class="text-center">
                        Aucun transfert inter-opérateur.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>