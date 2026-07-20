<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2 class="mb-4">Situation des gains</h2>

<div class="card mb-4">
    <div class="card-body">
        <h4>
            Total des gains :
            <?= number_format($totalGains['total_gains'] ?? 0, 0, ',', ' ') ?> Ar
        </h4>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Type d'opération</th>
            <th>Gain (Ar)</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($gainsParType as $gain): ?>
            <tr>
                <td><?= esc($gain['libelle']) ?></td>
                <td><?= number_format($gain['total'], 0, ',', ' ') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>