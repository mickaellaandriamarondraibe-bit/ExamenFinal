<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2 class="mb-3">Ajouter un compte</h2>

<form action="<?= base_url('compte/store') ?>" method="post">

    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Client</label>

        <select name="client_id" class="form-select" required>
            <option value="">Choisir un client</option>

            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id'] ?>">
                    <?= esc($client['telephone']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Numéro de compte</label>

        <input
            type="text"
            name="numero_compte"
            class="form-control"
            placeholder="Exemple : 0331234567"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Solde</label>

        <input
            type="number"
            name="solde"
            class="form-control"
            value="0"
            min="0"
            required
        >
    </div>

    <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>

    <a href="<?= base_url('compte') ?>" class="btn btn-secondary">
        Annuler
    </a>

</form>

<?= $this->endSection() ?>