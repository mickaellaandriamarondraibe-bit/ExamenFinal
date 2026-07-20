<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

<h3>Modifier un préfixe</h3>

<form action="<?= base_url('prefixes/update/'.$prefix['id']) ?>" method="post">

<div class="mb-3">

<label>Préfixe</label>

<input
type="text"
name="prefixe"
class="form-control"
value="<?= $prefix['prefixe'] ?>"
required>

</div>

<div class="mb-3">

<label>Etat</label>

<select name="actif" class="form-select">

<option value="1" <?= $prefix['actif'] ? 'selected' : '' ?>>Actif</option>

<option value="0" <?= !$prefix['actif'] ? 'selected' : '' ?>>Inactif</option>

</select>

</div>

<button class="btn btn-primary">
Modifier
</button>

<a href="<?= base_url('prefixes') ?>" class="btn btn-secondary">
Annuler
</a>

</form>

</div>

<?= $this->endSection() ?>