<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ajouter un barème</h2>
    </div>


    <form action="<?= base_url('operateur/baremes-frais/store') ?>" method="post">

        <?= csrf_field() ?>


        <div class="mb-3">

            <label class="form-label">
                Type d'opération
            </label>


            <select 
                name="type_operation_id" 
                class="form-select" 
                required
            >

                <option value="">
                    Choisir
                </option>


                <?php foreach ($types as $type): ?>

                    <option value="<?= $type['id'] ?>">
                        <?= esc($type['libelle']) ?>
                    </option>

                <?php endforeach; ?>


            </select>

        </div>



        <div class="mb-3">

            <label class="form-label">
                Opérateur concerné
            </label>


            <select 
                name="autre_operateur_id" 
                class="form-select"
            >

                <option value="">
                    Notre opérateur (Interne)
                </option>


                <?php foreach ($operateurs as $operateur): ?>

                    <option value="<?= $operateur['id'] ?>">

                        <?= esc($operateur['nom']) ?>

                    </option>

                <?php endforeach; ?>


            </select>


            <small class="text-muted">
                Laisser vide pour un barème interne.
            </small>

        </div>




        <div class="mb-3">

            <label class="form-label">
                Montant minimum
            </label>


            <input 
                type="number" 
                name="montant_min" 
                class="form-control"
                min="0"
                required
            >

        </div>




        <div class="mb-3">

            <label class="form-label">
                Montant maximum
            </label>


            <input 
                type="number" 
                name="montant_max" 
                class="form-control"
                min="0"
                required
            >

        </div>




        <div class="mb-3">

            <label class="form-label">
                Frais
            </label>


            <input 
                type="number" 
                name="frais" 
                class="form-control"
                min="0"
                required
            >

        </div>




        <div class="d-flex gap-2">

            <button type="submit" class="btn btn-primary">

                <i class="bi bi-check-lg"></i>
                Enregistrer

            </button>


            <a 
                href="<?= base_url('operateur/baremes-frais') ?>" 
                class="btn btn-secondary"
            >

                Annuler

            </a>

        </div>


    </form>


</div>


<?= $this->endSection() ?>