<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2>Ajouter un barème</h2>

<form action="<?= base_url('operateur/baremes-frais/store') ?>" method="post">

    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Type d'opération</label>

        <select name="type_operation_id" class="form-select" required>
            <option value="">Choisir</option>

            <?php foreach ($types as $type): ?>
                <option value="<?= $type['id'] ?>">
                    <?= esc($type['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Montant minimum</label>

        <input type="number" name="montant_min" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Montant maximum</label>

        <input type="number" name="montant_max" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Frais</label>

        <input type="number" name="frais" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>

    <a href="<?= base_url('operateur/baremes-frais') ?>" class="btn btn-secondary">
        Annuler
    </a>

</form>

<?= $this->endSection() ?>