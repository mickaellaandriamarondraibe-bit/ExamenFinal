<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-xl-6">

            <div class="card">

                <div class="card-body p-4">

                    <div class="mb-4">

                        <h2 class="mb-1">
                            Nouvelle commission
                        </h2>

                        <p class="text-muted mb-0">
                            Définissez le pourcentage de commission pour un opérateur externe.
                        </p>

                    </div>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form
                        action="<?= base_url('/operateur/commissions/store') ?>"
                        method="post"
                    >

                        <?= csrf_field() ?>

                        <div class="mb-4">

                            <label
                                for="operateur_id"
                                class="form-label"
                            >
                                Opérateur externe
                            </label>

                            <select
                                id="operateur_id"
                                name="operateur_id"
                                class="form-select"
                                required
                            >
                                <option value="">-- Sélectionner un opérateur --</option>
                                <?php foreach ($operateurs as $operateur) : ?>
                                    <option
                                        value="<?= esc($operateur['id']) ?>"
                                        <?= old('operateur_id') == $operateur['id'] ? 'selected' : '' ?>
                                    >
                                        <?= esc($operateur['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="mb-4">

                            <label
                                for="pourcentage"
                                class="form-label"
                            >
                                Pourcentage (%)
                            </label>

                            <input
                                type="number"
                                id="pourcentage"
                                name="pourcentage"
                                class="form-control"
                                placeholder="Exemple : 2.5"
                                step="0.01"
                                min="0"
                                max="100"
                                value="<?= esc(old('pourcentage')) ?>"
                                required
                            >

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a 
                                href="<?= base_url('operateur/commissions') ?>"
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