<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-xl-6">

            <div class="card">

                <div class="card-body p-4">

                    <div class="mb-4">

                        <h2 class="mb-1">
                            Modifier un opérateur
                        </h2>

                        <p class="text-muted mb-0">
                            Modifiez le nom de l'opérateur.
                        </p>

                    </div>

                    <form
                        action="<?= base_url('operateur/autres_operateurs/update/' . $operateur['id']) ?>"
                        method="post"
                    >

                        <?= csrf_field() ?>

                        <div class="mb-4">

                            <label
                                for="nom"
                                class="form-label"
                            >
                                Nom
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                class="form-control"
                                value="<?= esc($operateur['nom']) ?>"
                                required
                            >

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="<?= base_url('operateur/autres_operateurs') ?>"
                                class="btn btn-light"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-check-lg"></i>
                                Mettre à jour
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>