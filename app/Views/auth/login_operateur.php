<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Connexion opérateur</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: #f4f6f5;
            color: #14241f;
            font-family: Arial, sans-serif;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 920px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            overflow: hidden;
            border: 1px solid #dde4df;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 50px rgba(20, 36, 31, 0.12);
        }

        .login-brand {
            padding: 42px;
            background: #063f2f;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 440px;
        }

        .logo {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.14);
            margin-bottom: 22px;
        }

        .login-brand h1 {
            font-size: 30px;
            line-height: 1.15;
            margin-bottom: 12px;
        }

        .login-brand p {
            max-width: 260px;
            color: #d8eee6;
            font-size: 14px;
            line-height: 1.6;
        }

        .badge {
            width: fit-content;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            color: #d8eee6;
            font-size: 12px;
        }

        .login-form {
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            margin-bottom: 8px;
            color: #14241f;
            font-size: 28px;
        }

        .subtitle {
            margin-bottom: 28px;
            color: #64736d;
            font-size: 14px;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #f3b5b5;
            border-radius: 10px;
            background: #fff1f1;
            color: #b42323;
            font-size: 13px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            color: #37534a;
            font-size: 13px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid #d8dfdb;
            border-radius: 10px;
            background: #ffffff;
            color: #14241f;
            font-size: 15px;
        }

        .field input:focus {
            outline: none;
            border-color: #07694f;
            box-shadow: 0 0 0 3px rgba(7, 105, 79, 0.12);
        }

        .login-button {
            width: 100%;
            height: 50px;
            margin-top: 4px;
            border: 0;
            border-radius: 10px;
            background: #006b4f;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .login-button:hover {
            background: #045640;
        }

        .client-link {
            display: block;
            margin-top: 22px;
            color: #006b4f;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
        }

        .client-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 760px) {
            .login-page {
                padding: 16px;
            }

            .login-card {
                grid-template-columns: 1fr;
            }

            .login-brand {
                min-height: auto;
                padding: 28px;
            }

            .badge {
                margin-top: 24px;
            }

            .login-form {
                padding: 28px;
            }
        }
    </style>
</head>

<body>
    <main class="login-page">
        <section class="login-card">
            <div class="login-brand">
                <div>
                    <div class="logo">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="5" y="2" width="14" height="20" rx="2"></rect>
                            <path d="M9 7h6"></path>
                            <path d="M9 11h6"></path>
                            <path d="M12 18h.01"></path>
                        </svg>
                    </div>
                    <h1>Mobile Money<br>Opérateur</h1>
                    <p>Accès réservé à l'administration des clients, comptes, barèmes et transactions.</p>
                </div>

                <div class="badge">Console sécurisée</div>
            </div>

            <div class="login-form">
                <h2>Connexion</h2>
                <p class="subtitle">Connectez-vous avec votre compte opérateur.</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('operateur/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= esc(old('email', 'admin@gmail.com')) ?>" required>
                    </div>

                    <div class="field">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" value="admin123" required>
                    </div>

                    <button type="submit" class="login-button">Se connecter</button>
                </form>

                <a href="<?= base_url('/') ?>" class="client-link">Connexion client</a>
            </div>
        </section>
    </main>
</body>

</html>
