<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2 class="mb-3">Modifier un compte</h2>

<form action="<?= base_url('operateur/compte/update/' . $compte['id']) ?>" method="post">

    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Client</label>

        <select name="client_id" class="form-select" required>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id'] ?>" <?= $client['id'] == $compte['client_id'] ? 'selected' : '' ?>>
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
            value="<?= esc($compte['numero_compte']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Solde</label>

        <input
            type="number"
            name="solde"
            class="form-control"
            value="<?= $compte['solde'] ?>"
            min="0"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">État</label>

        <select name="actif" class="form-select" required>
            <option value="1" <?= $compte['actif'] == 1 ? 'selected' : '' ?>>
                Actif
            </option>

            <option value="0" <?= $compte['actif'] == 0 ? 'selected' : '' ?>>
                Inactif
            </option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Modifier
    </button>

    <a href="<?= base_url('operateur/compte') ?>" class="btn btn-secondary">
        Annuler
    </a>

</form>

<?= $this->endSection() ?>