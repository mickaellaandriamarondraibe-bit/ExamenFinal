<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion opérateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">Connexion opérateur</h2>

                    <?php if (session()->getFlashdata('error')): ?>

                        <div class="alert alert-danger">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>

                    <?php endif; ?>

                    <form action="<?= base_url('operateur/login') ?>" method="post">

                        <?= csrf_field() ?>

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= old('email') ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Mot de passe</label>

                            <input
                                type="password"
                                name="mot_de_passe"
                                class="form-control"
                                required
                            >

                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Se connecter
                        </button>

                    </form>

                    <div class="text-center mt-3">

                        <a href="<?= base_url('/') ?>">
                            Connexion client
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>