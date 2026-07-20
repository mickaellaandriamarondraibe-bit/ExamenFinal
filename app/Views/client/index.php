<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Liste des clients</h1>

        <p class="page-subtitle">
            Consultation des clients enregistrés
        </p>
    </div>
</div>

<div class="content-card">

    <div class="d-flex justify-content-between mb-3">

        <input
            type="text"
            class="form-control search-box"
            placeholder="Rechercher par téléphone"
        >

        <button class="btn btn-outline-secondary">
            <i class="bi bi-funnel"></i>
            Filtres
        </button>

    </div>

    <div class="table-wrapper">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Téléphone</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($clients as $client): ?>

                    <tr>
                        <td><?= $client['id'] ?></td>

                        <td>
                            <?= esc($client['telephone']) ?>
                        </td>

                        <td>
                            <?= esc($client['date_creation'] ?? '-') ?>
                        </td>

                        <td>
                            <a
                                href="<?= base_url('client/detail/' . $client['id']) ?>"
                                class="btn btn-sm btn-outline-success"
                            >
                                <i class="bi bi-eye"></i>
                                Voir
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?= $this->endSection() ?>