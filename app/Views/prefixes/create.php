<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

<h3>Ajouter un préfixe</h3>

<form action="<?= base_url('operateur/prefixes/store') ?>" method="post">

<?= csrf_field() ?>

<div class="mb-3">

<label>Préfixe</label>

<input
type="text"
name="prefixe"
class="form-control"
placeholder="Ex : 033"
required>

</div>

<div class="mb-3">

<label>Opérateur</label>

<select name="autre_operateur_id" class="form-select">

<option value="">Notre opérateur</option>

<?php foreach ($autresOperateurs as $operateur): ?>
<option value="<?= esc($operateur['id']) ?>">
<?= esc($operateur['nom']) ?>
</option>
<?php endforeach; ?>

</select>

<div class="form-text">
Choisir "Notre opérateur" pour les préfixes internes comme 033 ou 037.
</div>

</div>

<div class="mb-3">

<label>Etat</label>

<select name="actif" class="form-select">

<option value="1">Actif</option>
<option value="0">Inactif</option>

</select>

</div>

<button class="btn btn-success">
Enregistrer
</button>

<a href="<?= base_url('operateur/prefixes') ?>" class="btn btn-secondary">
Annuler
</a>

</form>

</div>

<?= $this->endSection() ?>
