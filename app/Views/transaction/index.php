<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2>Transactions</h2>

        <p class="text-muted mb-0">
            Liste de toutes les transactions effectuées
        </p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Compte source</th>
                <th>Compte destination</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Frais</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($transactions)): ?>

                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td>
                            <?= esc($transaction['id']) ?>
                        </td>

                        <td>
                            <?= esc($transaction['compte_source_id'] ?? '-') ?>
                        </td>

                        <td>
                            <?= esc($transaction['compte_destination_id'] ?? '-') ?>
                        </td>

                        <td>
                            <?= esc($transaction['type_operation_id']) ?>
                        </td>

                        <td>
                            <?= number_format($transaction['montant'], 0, ',', ' ') ?> Ar
                        </td>

                        <td>
                            <?= number_format($transaction['frais'], 0, ',', ' ') ?> Ar
                        </td>

                        <td>
                            <?= esc($transaction['date_transaction']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="text-center">
                        Aucune transaction trouvée.
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>