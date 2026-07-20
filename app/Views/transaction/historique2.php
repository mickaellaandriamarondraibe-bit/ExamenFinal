<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Historique du compte</h1>

        <p class="page-subtitle">
            Liste des transactions effectuées sur le compte
            <?= esc($compte['numero_compte']) ?>
        </p>
    </div>

    <a href="<?= base_url('operateur/compte') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
        Retour
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="content-card">

    <div class="mb-3">
        <strong>Numéro de compte :</strong>
        <?= esc($compte['numero_compte']) ?>

        <br>

        <strong>Solde actuel :</strong>
        <?= number_format($compte['solde'], 0, ',', ' ') ?> Ar
    </div>

    <div class="table-wrapper">

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sens</th>
                    <th>Compte source</th>
                    <th>Compte destination</th>
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
                                <?php if ($transaction['sens'] === 'in'): ?>
                                    <span class="badge bg-success">
                                        Entrée
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        Sortie
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= esc($transaction['compte_source_id'] ?? '-') ?>
                            </td>

                            <td>
                                <?= esc($transaction['compte_destination_id'] ?? '-') ?>
                            </td>

                            <td>
                                <?= number_format(
                                    $transaction['montant'],
                                    0,
                                    ',',
                                    ' '
                                ) ?> Ar
                            </td>

                            <td>
                                <?= number_format(
                                    $transaction['frais'],
                                    0,
                                    ',',
                                    ' '
                                ) ?> Ar
                            </td>

                            <td>
                                <?= esc($transaction['date_transaction']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center">
                            Aucune transaction trouvée pour ce compte.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<?= $this->endSection() ?>