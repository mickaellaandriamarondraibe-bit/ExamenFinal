<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-xl-6">

            <div class="card">

                <div class="card-body p-4">

                    <div class="mb-4">

                        <h2 class="mb-1">
                            Nouveau type d’opération
                        </h2>

                        <p class="text-muted mb-0">
                            Ajoutez un nouveau type d’opération.
                        </p>

                    </div>

                    <form
                        action="<?= base_url('/operateur/autres_operateurs/store') ?>"
                        method="post"
                    >

                        <?= csrf_field() ?>

                        <div class="mb-4">

                            <label
                                for="libelle"
                                class="form-label"
                            >
                                Nom
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                class="form-control"
                                placeholder="Exemple : Orange"
                                value="<?= esc(old('libelle')) ?>"
                                required
                            >

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="<?= base_url('operateurs/autres_operateurs') ?>"
                                class="btn btn-light"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-check-lg"></i>
                                Enregistrer
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>