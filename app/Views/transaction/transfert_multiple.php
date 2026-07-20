<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Transfert multiple</title>

    <link
        rel="stylesheet"
        href="<?= base_url('css/style.css') ?>"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f7f6f2;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .mobile-container {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            margin: 0 auto;
            padding-bottom: 90px;
            background: #ffffff;
            border-left: 1px solid #eeeeee;
            border-right: 1px solid #eeeeee;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            background: #00543f;
            color: #ffffff;
        }

        .back-button {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 22px;
            text-decoration: none;
        }

        .page-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .page-content {
            padding: 20px;
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.5;
        }

        .alert-error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .alert-success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #55756b;
            font-size: 13px;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 14px;
            border: 1px solid #dedbd5;
            border-radius: 10px;
            background: #ffffff;
            color: #1f2937;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            border-color: #00543f;
            box-shadow: 0 0 0 3px rgba(0, 84, 63, 0.10);
        }

        .input-montant {
            padding-right: 48px;
        }

        .input-suffix {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            color: #55756b;
            font-size: 13px;
        }

        .recipients-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 11px;
        }

        .recipients-header .form-label {
            margin: 0;
        }

        .add-button {
            padding: 8px 11px;
            border: 1px solid #00543f;
            border-radius: 8px;
            background: #ffffff;
            color: #00543f;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .recipient-row {
            display: grid;
            grid-template-columns: 32px minmax(0, 1fr) 38px;
            align-items: center;
            gap: 9px;
            margin-bottom: 10px;
        }

        .recipient-index {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e5f3ee;
            color: #00543f;
            font-size: 13px;
            font-weight: 700;
        }

        .remove-button {
            width: 38px;
            height: 42px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fff5f5;
            color: #ef4444;
            font-size: 20px;
            cursor: pointer;
        }

        .remove-button:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .helper-text {
            display: block;
            margin-top: 4px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.45;
        }

        .error-text {
            min-height: 15px;
            margin-top: 5px;
            color: #dc2626;
            font-size: 12px;
        }

        .commission-option {
            margin: 18px 0 8px;
        }

        .checkbox-line {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #55756b;
            font-size: 13px;
            cursor: pointer;
        }

        .checkbox-line input {
            width: 17px;
            height: 17px;
            accent-color: #00543f;
        }

        .commission-description {
            margin: 8px 0 0;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }

        .summary-card {
            margin-top: 16px;
            padding: 16px;
            border-radius: 12px;
            background: #def3ec;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 12px;
            color: #55756b;
            font-size: 13px;
        }

        .summary-row strong {
            color: #4b665e;
            font-size: 13px;
        }

        .summary-divider {
            margin: 12px 0;
            border: 0;
            border-top: 1px dashed rgba(0, 84, 63, 0.18);
        }

        .summary-total {
            margin-bottom: 0;
            color: #111827;
            font-weight: 700;
        }

        .summary-total strong {
            color: #f50046;
            font-size: 15px;
        }

        .distribution-card {
            display: none;
            margin-top: 12px;
            padding: 13px;
            border: 1px solid #d7ebe4;
            border-radius: 10px;
            background: #f7fcfa;
        }

        .distribution-title {
            margin: 0 0 10px;
            color: #00543f;
            font-size: 13px;
            font-weight: 700;
        }

        .distribution-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e6efec;
            font-size: 12px;
        }

        .distribution-row:last-child {
            border-bottom: 0;
        }

        .distribution-phone {
            color: #55756b;
        }

        .distribution-amount {
            color: #111827;
            font-weight: 700;
            text-align: right;
        }

        .submit-button {
            width: 100%;
            height: 51px;
            margin-top: 16px;
            border: 0;
            border-radius: 10px;
            background: #00543f;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #004333;
        }

        .simple-transfer-button {
            width: 100%;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            border: 1px solid #dedbd5;
            border-radius: 10px;
            background: #ffffff;
            color: #111827;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }

        .bottom-navigation {
            position: fixed;
            bottom: 0;
            left: 50%;
            z-index: 50;
            width: 100%;
            max-width: 430px;
            height: 70px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            transform: translateX(-50%);
            border-top: 1px solid #e5e7eb;
            background: #ffffff;
        }

        .navigation-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            color: #6b827a;
            font-size: 10px;
            text-decoration: none;
        }

        .navigation-icon {
            font-size: 17px;
            line-height: 1;
        }

        .navigation-item.active {
            color: #00543f;
            font-weight: 700;
        }

        @media (min-width: 431px) {
            .mobile-container {
                box-shadow: 0 0 18px rgba(15, 23, 42, 0.05);
            }
        }
    </style>
</head>

<body>

<div class="mobile-container">

    <header class="page-header">
        <a
            href="<?= site_url('transfert') ?>"
            class="back-button"
            aria-label="Retour"
        >
            ‹
        </a>

        <h1>Transfert multiple</h1>
    </header>

    <main class="page-content">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form
            action="<?= site_url('transfert-multiple') ?>"
            method="post"
            id="form-transfert-multiple"
        >
            <?= csrf_field() ?>

            <div class="form-group">
                <label
                    for="montant_total"
                    class="form-label"
                >
                    Montant total à transférer
                </label>

                <div class="input-wrapper">
                    <input
                        type="number"
                        name="montant_total"
                        id="montant_total"
                        class="form-input input-montant"
                        min="1"
                        step="1"
                        placeholder="Entrez le montant total"
                        value="<?= esc(old('montant_total')) ?>"
                        required
                    >

                    <span class="input-suffix">Ar</span>
                </div>

                <div
                    class="error-text"
                    id="erreur-montant"
                ></div>
            </div>

            <div class="form-group">

                <div class="recipients-header">
                    <label class="form-label">
                        Numéros des destinataires
                    </label>

                    <button
                        type="button"
                        class="add-button"
                        id="ajouter-destinataire"
                    >
                        + Ajouter
                    </button>
                </div>

                <div id="liste-destinataires">

                    <div class="recipient-row">
                        <div class="recipient-index">1</div>

                        <input
                            type="tel"
                            name="telephones[]"
                            class="form-input telephone-input"
                            maxlength="10"
                            inputmode="numeric"
                            placeholder="Ex : 032 98 765 43"
                            required
                        >

                        <button
                            type="button"
                            class="remove-button"
                            disabled
                            aria-label="Supprimer"
                        >
                            ×
                        </button>
                    </div>

                    <div class="recipient-row">
                        <div class="recipient-index">2</div>

                        <input
                            type="tel"
                            name="telephones[]"
                            class="form-input telephone-input"
                            maxlength="10"
                            inputmode="numeric"
                            placeholder="Ex : 032 12 345 67"
                            required
                        >

                        <button
                            type="button"
                            class="remove-button"
                            aria-label="Supprimer"
                        >
                            ×
                        </button>
                    </div>

                </div>

                <small class="helper-text">
                    Tous les destinataires doivent appartenir au même opérateur.
                </small>

                <div
                    class="error-text"
                    id="erreur-destinataires"
                ></div>
            </div>

            <div class="commission-option">

                <label class="checkbox-line">
                    <input
                        type="checkbox"
                        name="prise_en_charge_commission"
                        id="prise_en_charge_commission"
                        value="1"
                    >

                    <span>
                        Prendre en charge les commissions
                    </span>
                </label>

                <p class="commission-description">
                    Si cette option n’est pas cochée, chaque commission sera
                    retirée du montant reçu par le destinataire concerné.
                </p>
            </div>

            <div class="summary-card">

                <div class="summary-row">
                    <span>Destinataires</span>

                    <strong id="resume-nombre">
                        2
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Montant par destinataire</span>

                    <strong>
                        <span id="resume-montant-individuel">0</span> Ar
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Frais totaux</span>

                    <strong>
                        <span id="resume-frais">0</span> Ar
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Commissions totales</span>

                    <strong>
                        <span id="resume-commission">0</span> Ar
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Total reçu par les destinataires</span>

                    <strong>
                        <span id="resume-montant-recu">0</span> Ar
                    </strong>
                </div>

                <hr class="summary-divider">

                <div class="summary-row summary-total">
                    <span>Total débité</span>

                    <strong>
                        <span id="resume-total">0</span> Ar
                    </strong>
                </div>

                <div
                    class="distribution-card"
                    id="distribution-card"
                >
                    <p class="distribution-title">
                        Répartition
                    </p>

                    <div id="distribution-list"></div>
                </div>

            </div>

            <button
                type="submit"
                class="submit-button"
            >
                Confirmer le transfert multiple
            </button>

            <a
                href="<?= site_url('transfert') ?>"
                class="simple-transfer-button"
            >
                Transfert simple
            </a>

        </form>

    </main>

</div>

<nav class="bottom-navigation">

    <a
        href="<?= site_url('accueil') ?>"
        class="navigation-item"
    >
        <span class="navigation-icon">⌂</span>
        <span>Accueil</span>
    </a>

    <a
        href="<?= site_url('depot') ?>"
        class="navigation-item"
    >
        <span class="navigation-icon">↓</span>
        <span>Dépôt</span>
    </a>

    <a
        href="<?= site_url('retrait') ?>"
        class="navigation-item"
    >
        <span class="navigation-icon">↑</span>
        <span>Retrait</span>
    </a>

    <a
        href="<?= site_url('transfert') ?>"
        class="navigation-item active"
    >
        <span class="navigation-icon">⇄</span>
        <span>Transfert</span>
    </a>

    <a
        href="#"
        class="navigation-item"
    >
        <span class="navigation-icon">•••</span>
        <span>Plus</span>
    </a>

</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const listeDestinataires =
        document.getElementById('liste-destinataires');

    const boutonAjouter =
        document.getElementById('ajouter-destinataire');

    const montantInput =
        document.getElementById('montant_total');

    const priseEnChargeInput =
        document.getElementById('prise_en_charge_commission');

    const formulaire =
        document.getElementById('form-transfert-multiple');

    const csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    let minuteurCalcul = null;

    function formaterMontant(montant) {
        return new Intl.NumberFormat('fr-FR').format(
            Number(montant) || 0
        );
    }

    function obtenirTelephones() {
        return Array.from(
            document.querySelectorAll('.telephone-input')
        ).map(function (input) {
            return input.value.replace(/\D/g, '');
        });
    }

    function mettreAJourNumerotation() {
        const lignes =
            listeDestinataires.querySelectorAll('.recipient-row');

        lignes.forEach(function (ligne, index) {
            ligne.querySelector('.recipient-index').textContent =
                index + 1;

            ligne.querySelector('.remove-button').disabled =
                lignes.length <= 2;
        });

        document.getElementById('resume-nombre').textContent =
            lignes.length;
    }

    function ajouterDestinataire() {
        const ligne = document.createElement('div');

        ligne.className = 'recipient-row';

        ligne.innerHTML = `
            <div class="recipient-index"></div>

            <input
                type="tel"
                name="telephones[]"
                class="form-input telephone-input"
                maxlength="10"
                inputmode="numeric"
                placeholder="Ex : 032 98 765 43"
                required
            >

            <button
                type="button"
                class="remove-button"
                aria-label="Supprimer"
            >
                ×
            </button>
        `;

        listeDestinataires.appendChild(ligne);

        mettreAJourNumerotation();

        ligne.querySelector('.telephone-input').focus();
    }

    function reinitialiserResultats() {
        document.getElementById(
            'resume-frais'
        ).textContent = '0';

        document.getElementById(
            'resume-commission'
        ).textContent = '0';

        document.getElementById(
            'resume-montant-recu'
        ).textContent = '0';

        document.getElementById(
            'resume-total'
        ).textContent = '0';

        const carteRepartition =
            document.getElementById('distribution-card');

        carteRepartition.style.display = 'none';

        document.getElementById(
            'distribution-list'
        ).innerHTML = '';
    }

    function afficherRepartition(details, telephones) {
        const carte =
            document.getElementById('distribution-card');

        const liste =
            document.getElementById('distribution-list');

        liste.innerHTML = '';

        if (!Array.isArray(details) || details.length === 0) {
            carte.style.display = 'none';
            return;
        }

        details.forEach(function (detail, index) {
            const ligne = document.createElement('div');

            ligne.className = 'distribution-row';

            let montant = 0;
            let montantRecu = 0;

            if (typeof detail === 'object') {
                montant = detail.montant || 0;
                montantRecu =
                    detail.montant_recu ?? montant;
            } else {
                montant = detail;
                montantRecu = detail;
            }

            ligne.innerHTML = `
                <span class="distribution-phone">
                    ${telephones[index] || 'Destinataire ' + (index + 1)}
                </span>

                <span class="distribution-amount">
                    ${formaterMontant(montant)} Ar
                    <br>
                    reçu : ${formaterMontant(montantRecu)} Ar
                </span>
            `;

            liste.appendChild(ligne);
        });

        carte.style.display = 'block';
    }

    async function calculerTransfertMultiple() {
        const montantTotal =
            parseInt(montantInput.value, 10) || 0;

        const telephones =
            obtenirTelephones();

        const nombre =
            telephones.length;

        const montantMoyen =
            nombre > 0
                ? Math.floor(montantTotal / nombre)
                : 0;

        document.getElementById(
            'resume-nombre'
        ).textContent = nombre;

        document.getElementById(
            'resume-montant-individuel'
        ).textContent = formaterMontant(montantMoyen);

        const numerosValides =
            telephones.every(function (telephone) {
                return telephone.length === 10;
            });

        if (
            montantTotal <= 0 ||
            nombre < 2 ||
            !numerosValides
        ) {
            reinitialiserResultats();
            return;
        }

        const donnees = new FormData();

        donnees.append(
            'montant_total',
            montantTotal
        );

        donnees.append(
            'prise_en_charge_commission',
            priseEnChargeInput.checked ? 1 : 0
        );

        telephones.forEach(function (telephone) {
            donnees.append(
                'telephones[]',
                telephone
            );
        });

        donnees.append(
            csrfName,
            csrfHash
        );

        try {
            const reponse = await fetch(
                '<?= site_url('transfert-multiple/calculer') ?>',
                {
                    method: 'POST',
                    body: donnees,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const resultat = await reponse.json();

            if (resultat.csrf_hash) {
                csrfHash = resultat.csrf_hash;
            }

            if (
                !reponse.ok ||
                resultat.success === false
            ) {
                reinitialiserResultats();
                return;
            }

            document.getElementById(
                'resume-frais'
            ).textContent =
                formaterMontant(resultat.total_frais);

            document.getElementById(
                'resume-commission'
            ).textContent =
                formaterMontant(resultat.total_commission);

            document.getElementById(
                'resume-montant-recu'
            ).textContent =
                formaterMontant(resultat.total_montant_recu);

            document.getElementById(
                'resume-total'
            ).textContent =
                formaterMontant(resultat.total_a_debiter);

            const repartition =
                resultat.details ??
                resultat.montants_individuels ??
                [];

            afficherRepartition(
                repartition,
                telephones
            );
        } catch (erreur) {
            reinitialiserResultats();
        }
    }

    function planifierCalcul() {
        clearTimeout(minuteurCalcul);

        minuteurCalcul = setTimeout(
            calculerTransfertMultiple,
            350
        );
    }

    boutonAjouter.addEventListener(
        'click',
        function () {
            ajouterDestinataire();
            planifierCalcul();
        }
    );

    listeDestinataires.addEventListener(
        'click',
        function (event) {
            if (
                !event.target.classList.contains(
                    'remove-button'
                )
            ) {
                return;
            }

            const lignes =
                listeDestinataires.querySelectorAll(
                    '.recipient-row'
                );

            if (lignes.length <= 2) {
                return;
            }

            event.target
                .closest('.recipient-row')
                .remove();

            mettreAJourNumerotation();
            planifierCalcul();
        }
    );

    listeDestinataires.addEventListener(
        'input',
        function (event) {
            if (
                !event.target.classList.contains(
                    'telephone-input'
                )
            ) {
                return;
            }

            event.target.value =
                event.target.value
                    .replace(/\D/g, '')
                    .slice(0, 10);

            planifierCalcul();
        }
    );

    montantInput.addEventListener(
        'input',
        planifierCalcul
    );

    priseEnChargeInput.addEventListener(
        'change',
        planifierCalcul
    );

    formulaire.addEventListener(
        'submit',
        function (event) {
            const montant =
                parseInt(montantInput.value, 10) || 0;

            const telephones =
                obtenirTelephones();

            const erreurMontant =
                document.getElementById('erreur-montant');

            const erreurDestinataires =
                document.getElementById(
                    'erreur-destinataires'
                );

            erreurMontant.textContent = '';
            erreurDestinataires.textContent = '';

            let valide = true;

            if (montant <= 0) {
                erreurMontant.textContent =
                    'Veuillez saisir un montant valide.';

                valide = false;
            }

            if (telephones.length < 2) {
                erreurDestinataires.textContent =
                    'Ajoutez au moins deux destinataires.';

                valide = false;
            } else if (
                telephones.some(function (telephone) {
                    return telephone.length !== 10;
                })
            ) {
                erreurDestinataires.textContent =
                    'Chaque numéro doit contenir exactement 10 chiffres.';

                valide = false;
            } else if (
                new Set(telephones).size !== telephones.length
            ) {
                erreurDestinataires.textContent =
                    'Un même numéro ne peut pas être saisi plusieurs fois.';

                valide = false;
            }

            if (!valide) {
                event.preventDefault();
            }
        }
    );

    mettreAJourNumerotation();
});
</script>

</body>
</html>