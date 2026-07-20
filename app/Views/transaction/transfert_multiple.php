<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>

<div class="transfer-page">

    <div class="page-header">
        <div>
            <h1>Transfert multiple</h1>
            <p>
                Envoyez un montant total à plusieurs destinataires
                appartenant au même opérateur.
            </p>
        </div>

        <a
            href="<?= site_url('client/transfert') ?>"
            class="btn btn-outline"
        >
            Transfert simple
        </a>
    </div>

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
        action="<?= site_url('client/transfert-multiple') ?>"
        method="post"
        id="form-transfert-multiple"
    >
        <?= csrf_field() ?>

        <div class="transfer-layout">

            <!-- Formulaire principal -->
            <div class="card transfer-form-card">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-number">1</div>

                        <div>
                            <h2>Montant à répartir</h2>
                            <p>
                                Le montant sera partagé entre tous les
                                destinataires.
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="montant_total">
                            Montant total
                        </label>

                        <div class="input-with-suffix">
                            <input
                                type="number"
                                name="montant_total"
                                id="montant_total"
                                min="1"
                                step="1"
                                placeholder="Exemple : 30 000"
                                value="<?= old('montant_total') ?>"
                                required
                            >

                            <span>Ar</span>
                        </div>

                        <small class="field-error" id="erreur-montant"></small>
                    </div>
                </div>

                <div class="separator"></div>

                <div class="form-section">
                    <div class="section-header section-header-action">
                        <div class="section-header-left">
                            <div class="section-number">2</div>

                            <div>
                                <h2>Destinataires</h2>
                                <p>
                                    Tous les numéros doivent appartenir
                                    au même opérateur.
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn btn-outline btn-small"
                            id="ajouter-destinataire"
                        >
                            + Ajouter
                        </button>
                    </div>

                    <div id="liste-destinataires">

                        <div class="recipient-row">
                            <div class="recipient-number">
                                1
                            </div>

                            <div class="recipient-field">
                                <label>Numéro du destinataire</label>

                                <input
                                    type="tel"
                                    name="telephones[]"
                                    class="telephone-input"
                                    maxlength="10"
                                    placeholder="032 XX XXX XX"
                                    inputmode="numeric"
                                    required
                                >
                            </div>

                            <button
                                type="button"
                                class="btn-remove"
                                title="Supprimer ce destinataire"
                                disabled
                            >
                                ×
                            </button>
                        </div>

                        <div class="recipient-row">
                            <div class="recipient-number">
                                2
                            </div>

                            <div class="recipient-field">
                                <label>Numéro du destinataire</label>

                                <input
                                    type="tel"
                                    name="telephones[]"
                                    class="telephone-input"
                                    maxlength="10"
                                    placeholder="032 XX XXX XX"
                                    inputmode="numeric"
                                    required
                                >
                            </div>

                            <button
                                type="button"
                                class="btn-remove"
                                title="Supprimer ce destinataire"
                            >
                                ×
                            </button>
                        </div>

                    </div>

                    <small class="field-error" id="erreur-destinataires"></small>
                </div>

                <div class="separator"></div>

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-number">3</div>

                        <div>
                            <h2>Commission inter-opérateur</h2>
                            <p>
                                Choisissez qui supportera les commissions.
                            </p>
                        </div>
                    </div>

                    <label class="commission-option">
                        <input
                            type="checkbox"
                            name="prise_en_charge_commission"
                            id="prise_en_charge_commission"
                            value="1"
                        >

                        <span class="custom-checkbox"></span>

                        <span class="commission-content">
                            <strong>
                                Prendre en charge les commissions
                            </strong>

                            <small>
                                Les destinataires recevront le montant
                                réparti en totalité.
                            </small>
                        </span>
                    </label>

                    <div class="commission-information">
                        <strong>Case non cochée :</strong>
                        les commissions seront retirées des montants reçus
                        par les destinataires.
                    </div>
                </div>

            </div>

            <!-- Résumé -->
            <aside class="card summary-card">
                <div class="summary-header">
                    <h2>Résumé du transfert</h2>
                    <p>Les valeurs seront calculées automatiquement.</p>
                </div>

                <div class="summary-list">
                    <div class="summary-row">
                        <span>Destinataires</span>
                        <strong id="resume-nombre">2</strong>
                    </div>

                    <div class="summary-row">
                        <span>Montant total</span>

                        <strong>
                            <span id="resume-montant-total">0</span> Ar
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Montant moyen par personne</span>

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
                        <span>Total reçu</span>

                        <strong>
                            <span id="resume-montant-recu">0</span> Ar
                        </strong>
                    </div>
                </div>

                <div class="summary-total">
                    <span>Total à débiter</span>

                    <strong>
                        <span id="resume-total">0</span> Ar
                    </strong>
                </div>

                <div
                    class="distribution-details"
                    id="distribution-details"
                >
                    <h3>Répartition</h3>

                    <div id="distribution-list">
                        <p class="empty-distribution">
                            Saisissez le montant et les numéros pour
                            afficher la répartition.
                        </p>
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-submit"
                    id="bouton-envoyer"
                >
                    Effectuer le transfert multiple
                </button>
            </aside>

        </div>
    </form>

</div>

<style>
    .transfer-page {
        max-width: 1180px;
        margin: 0 auto;
        padding: 28px 20px;
        color: #1f2937;
    }

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #6b7280;
        line-height: 1.5;
    }

    .transfer-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 380px;
        gap: 24px;
        align-items: start;
    }

    .card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .transfer-form-card {
        padding: 26px;
    }

    .form-section {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .section-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .section-header-action {
        justify-content: space-between;
    }

    .section-header-left {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .section-number {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
    }

    .section-header h2 {
        margin: 0 0 5px;
        font-size: 18px;
    }

    .section-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .separator {
        height: 1px;
        background: #e5e7eb;
        margin: 28px 0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label,
    .recipient-field label {
        color: #374151;
        font-size: 14px;
        font-weight: 600;
    }

    input[type="number"],
    input[type="tel"] {
        width: 100%;
        box-sizing: border-box;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        background: #ffffff;
        color: #111827;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    input[type="number"]:focus,
    input[type="tel"]:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .input-with-suffix {
        position: relative;
    }

    .input-with-suffix input {
        padding-right: 55px;
    }

    .input-with-suffix > span {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
    }

    .recipient-row {
        display: grid;
        grid-template-columns: 36px minmax(0, 1fr) 38px;
        gap: 12px;
        align-items: end;
        padding: 14px;
        margin-bottom: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 11px;
        background: #f9fafb;
    }

    .recipient-number {
        width: 32px;
        height: 32px;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        font-weight: 700;
        color: #4b5563;
    }

    .recipient-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .btn-remove {
        width: 38px;
        height: 42px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        background: #fff1f2;
        color: #dc2626;
        font-size: 24px;
        cursor: pointer;
    }

    .btn-remove:hover:not(:disabled) {
        background: #fee2e2;
    }

    .btn-remove:disabled {
        cursor: not-allowed;
        opacity: 0.4;
    }

    .commission-option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border: 1px solid #dbeafe;
        border-radius: 11px;
        background: #f8fbff;
        cursor: pointer;
    }

    .commission-option input {
        display: none;
    }

    .custom-checkbox {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 1px;
        border: 2px solid #9ca3af;
        border-radius: 5px;
        background: #ffffff;
        position: relative;
    }

    .commission-option input:checked + .custom-checkbox {
        background: #4f46e5;
        border-color: #4f46e5;
    }

    .commission-option input:checked + .custom-checkbox::after {
        content: "";
        position: absolute;
        left: 5px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid #ffffff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .commission-content {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .commission-content small {
        color: #6b7280;
        line-height: 1.4;
    }

    .commission-information {
        padding: 12px 14px;
        border-radius: 9px;
        background: #fffbeb;
        color: #92400e;
        font-size: 13px;
        line-height: 1.5;
    }

    .summary-card {
        position: sticky;
        top: 20px;
        padding: 22px;
    }

    .summary-header {
        margin-bottom: 20px;
    }

    .summary-header h2 {
        margin: 0 0 6px;
        font-size: 19px;
    }

    .summary-header p {
        margin: 0;
        color: #6b7280;
        font-size: 13px;
    }

    .summary-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-size: 14px;
    }

    .summary-row span {
        color: #6b7280;
    }

    .summary-row strong {
        text-align: right;
        color: #111827;
    }

    .summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 20px;
        padding: 17px 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-total span {
        font-weight: 600;
    }

    .summary-total strong {
        color: #4f46e5;
        font-size: 22px;
    }

    .distribution-details {
        margin-top: 20px;
    }

    .distribution-details h3 {
        margin: 0 0 12px;
        font-size: 15px;
    }

    .distribution-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 13px;
    }

    .distribution-item:last-child {
        border-bottom: 0;
    }

    .distribution-phone {
        color: #4b5563;
    }

    .distribution-amount {
        font-weight: 700;
    }

    .empty-distribution {
        margin: 0;
        color: #9ca3af;
        font-size: 13px;
        line-height: 1.5;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary {
        border: 1px solid #4f46e5;
        background: #4f46e5;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #4338ca;
    }

    .btn-outline {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
    }

    .btn-outline:hover {
        background: #f9fafb;
    }

    .btn-small {
        padding: 8px 12px;
        font-size: 13px;
    }

    .btn-submit {
        width: 100%;
        margin-top: 22px;
        padding: 13px;
    }

    .alert {
        margin-bottom: 18px;
        padding: 13px 16px;
        border-radius: 9px;
        font-size: 14px;
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

    .field-error {
        min-height: 17px;
        color: #dc2626;
        font-size: 12px;
    }

    @media (max-width: 900px) {
        .transfer-layout {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
        }

        .section-header-action {
            flex-direction: column;
        }

        .recipient-row {
            grid-template-columns: 32px minmax(0, 1fr) 38px;
            padding: 10px;
        }
    }
</style>

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

    const form =
        document.getElementById('form-transfert-multiple');

    const csrfName =
        '<?= csrf_token() ?>';

    let csrfHash =
        '<?= csrf_hash() ?>';

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

    function mettreAJourNumeros() {
        const lignes =
            listeDestinataires.querySelectorAll('.recipient-row');

        lignes.forEach(function (ligne, index) {
            ligne.querySelector('.recipient-number').textContent =
                index + 1;

            const boutonSupprimer =
                ligne.querySelector('.btn-remove');

            boutonSupprimer.disabled = lignes.length <= 2;
        });

        document.getElementById('resume-nombre').textContent =
            lignes.length;
    }

    function creerDestinataire() {
        const ligne = document.createElement('div');

        ligne.className = 'recipient-row';

        ligne.innerHTML = `
            <div class="recipient-number"></div>

            <div class="recipient-field">
                <label>Numéro du destinataire</label>

                <input
                    type="tel"
                    name="telephones[]"
                    class="telephone-input"
                    maxlength="10"
                    placeholder="032 XX XXX XX"
                    inputmode="numeric"
                    required
                >
            </div>

            <button
                type="button"
                class="btn-remove"
                title="Supprimer ce destinataire"
            >
                ×
            </button>
        `;

        listeDestinataires.appendChild(ligne);

        mettreAJourNumeros();

        ligne.querySelector('.telephone-input').focus();
    }

    function afficherRepartition(montants, telephones) {
        const conteneur =
            document.getElementById('distribution-list');

        conteneur.innerHTML = '';

        if (!Array.isArray(montants) || montants.length === 0) {
            conteneur.innerHTML = `
                <p class="empty-distribution">
                    Saisissez le montant et les numéros pour afficher
                    la répartition.
                </p>
            `;

            return;
        }

        montants.forEach(function (montant, index) {
            const ligne = document.createElement('div');

            ligne.className = 'distribution-item';

            const telephone =
                telephones[index] || 'Destinataire ' + (index + 1);

            ligne.innerHTML = `
                <span class="distribution-phone">
                    ${telephone}
                </span>

                <span class="distribution-amount">
                    ${formaterMontant(montant)} Ar
                </span>
            `;

            conteneur.appendChild(ligne);
        });
    }

    function reinitialiserResume() {
        document.getElementById('resume-frais').textContent = '0';
        document.getElementById('resume-commission').textContent = '0';
        document.getElementById('resume-montant-recu').textContent = '0';
        document.getElementById('resume-total').textContent = '0';
    }

    async function calculerTransfertMultiple() {
        const montantTotal =
            parseInt(montantInput.value, 10) || 0;

        const telephones =
            obtenirTelephones();

        const nombreDestinataires =
            telephones.length;

        document.getElementById('resume-nombre').textContent =
            nombreDestinataires;

        document.getElementById('resume-montant-total').textContent =
            formaterMontant(montantTotal);

        const montantMoyen =
            nombreDestinataires > 0
                ? Math.floor(montantTotal / nombreDestinataires)
                : 0;

        document.getElementById(
            'resume-montant-individuel'
        ).textContent = formaterMontant(montantMoyen);

        if (
            montantTotal <= 0 ||
            nombreDestinataires < 2 ||
            telephones.some(function (telephone) {
                return telephone.length !== 10;
            })
        ) {
            reinitialiserResume();
            afficherRepartition([], telephones);
            return;
        }

        const donnees = new FormData();

        donnees.append('montant_total', montantTotal);

        donnees.append(
            'prise_en_charge_commission',
            priseEnChargeInput.checked ? 1 : 0
        );

        telephones.forEach(function (telephone) {
            donnees.append('telephones[]', telephone);
        });

        donnees.append(csrfName, csrfHash);

        try {
            const reponse = await fetch(
                '<?= site_url('client/transfert-multiple/calculer') ?>',
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

            if (!reponse.ok || resultat.success === false) {
                reinitialiserResume();
                afficherRepartition([], telephones);
                return;
            }

            document.getElementById('resume-frais').textContent =
                formaterMontant(resultat.total_frais);

            document.getElementById('resume-commission').textContent =
                formaterMontant(resultat.total_commission);

            document.getElementById(
                'resume-montant-recu'
            ).textContent =
                formaterMontant(resultat.total_montant_recu);

            document.getElementById('resume-total').textContent =
                formaterMontant(resultat.total_a_debiter);

            afficherRepartition(
                resultat.montants_individuels,
                telephones
            );
        } catch (erreur) {
            reinitialiserResume();
        }
    }

    boutonAjouter.addEventListener('click', function () {
        creerDestinataire();
        calculerTransfertMultiple();
    });

    listeDestinataires.addEventListener('click', function (event) {
        if (!event.target.classList.contains('btn-remove')) {
            return;
        }

        const lignes =
            listeDestinataires.querySelectorAll('.recipient-row');

        if (lignes.length <= 2) {
            return;
        }

        event.target.closest('.recipient-row').remove();

        mettreAJourNumeros();
        calculerTransfertMultiple();
    });

    listeDestinataires.addEventListener('input', function (event) {
        if (!event.target.classList.contains('telephone-input')) {
            return;
        }

        event.target.value =
            event.target.value.replace(/\D/g, '').slice(0, 10);

        calculerTransfertMultiple();
    });

    montantInput.addEventListener(
        'input',
        calculerTransfertMultiple
    );

    priseEnChargeInput.addEventListener(
        'change',
        calculerTransfertMultiple
    );

    form.addEventListener('submit', function (event) {
        const montantTotal =
            parseInt(montantInput.value, 10) || 0;

        const telephones =
            obtenirTelephones();

        let valide = true;

        document.getElementById('erreur-montant').textContent = '';
        document.getElementById(
            'erreur-destinataires'
        ).textContent = '';

        if (montantTotal <= 0) {
            document.getElementById('erreur-montant').textContent =
                'Veuillez saisir un montant total valide.';

            valide = false;
        }

        if (telephones.length < 2) {
            document.getElementById(
                'erreur-destinataires'
            ).textContent =
                'Vous devez saisir au moins deux destinataires.';

            valide = false;
        }

        if (
            telephones.some(function (telephone) {
                return telephone.length !== 10;
            })
        ) {
            document.getElementById(
                'erreur-destinataires'
            ).textContent =
                'Chaque numéro doit contenir exactement 10 chiffres.';

            valide = false;
        }

        if (
            new Set(telephones).size !== telephones.length
        ) {
            document.getElementById(
                'erreur-destinataires'
            ).textContent =
                'Un même numéro ne peut pas apparaître plusieurs fois.';

            valide = false;
        }

        if (!valide) {
            event.preventDefault();
        }
    });

    mettreAJourNumeros();
});
</script>

<?= $this->endSection() ?>