<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<h2>Modifier un barème</h2>

<form action="<?= base_url('operateur/baremes-frais/update/' . $bareme['id']) ?>" method="post">

    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Type d'opération</label>

        <select name="type_operation_id" class="form-select" required>
            <?php foreach ($types as $type): ?>
                <option value="<?= $type['id'] ?>" <?= $type['id'] == $bareme['type_operation_id'] ? 'selected' : '' ?>>
                    <?= esc($type['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Montant minimum</label>

        <input type="number" name="montant_min" class="form-control" value="<?= $bareme['montant_min'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Montant maximum</label>

        <input type="number" name="montant_max" class="form-control" value="<?= $bareme['montant_max'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Frais</label>

        <input type="number" name="frais" class="form-control" value="<?= $bareme['frais'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">
        Modifier
    </button>

    <a href="<?= base_url('operateur/baremes-frais') ?>" class="btn btn-secondary">
        Annuler
    </a>

</form>

<?= $this->endSection() ?>