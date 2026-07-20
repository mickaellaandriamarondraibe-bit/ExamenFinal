<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Transfert</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="phone">
  <div class="app-header simple">
    <a href="<?= site_url('accueil') ?>" class="back-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </a>
    <h1>Transfert</h1>
  </div>

  <div class="app-content">
    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('transfert') ?>" method="post">
      <?= csrf_field() ?>

      <div class="field">
        <label for="telephone">Numéro destinataire</label>

        <div class="input-wrap">
          <input
            type="text"
            name="telephone"
            id="telephone"
            placeholder="Ex : 032 98 765 43"
            value="<?= esc(old('telephone')) ?>"
            required
          >
        </div>
      </div>

      <div class="field">
        <label for="montant">Montant à transférer</label>

        <div class="input-wrap">
          <input
            type="number"
            name="montant"
            id="montant"
            min="1"
            placeholder="Entrez le montant"
            value="<?= esc(old('montant')) ?>"
            required
          >

          <span class="suffix">Ar</span>
        </div>
      </div>

      <div class="field">
        <label class="commission-option">
          <input
            type="checkbox"
            name="prise_en_charge_commission"
            id="prise_en_charge_commission"
            value="1"
            <?= old('prise_en_charge_commission') ? 'checked' : '' ?>
          >

          <span>
            Prendre en charge la commission inter-opérateur
          </span>
        </label>

        <small class="field-help">
          Si cette option n’est pas cochée, la commission sera retirée du montant reçu par le destinataire.
        </small>
      </div>

      <div
        id="message-operateur"
        class="alert"
        style="display: none;"
      ></div>

      <div class="card">
        <div class="detail-row">
          <span>Frais de transfert</span>
          <span id="frais">0 Ar</span>
        </div>

        <div class="detail-row">
          <span>Commission inter-opérateur</span>
          <span id="commission">0 Ar</span>
        </div>

        <div class="detail-row">
          <span>Le destinataire recevra</span>
          <span id="recevra">0 Ar</span>
        </div>

        <div class="detail-row total">
          <span>Total débité</span>
          <span class="val" id="total">0 Ar</span>
        </div>
      </div>

      <button type="submit" class="btn-primary">
        Confirmer le transfert
      </button>
    </form>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'transfert']) ?>
</div>

<script>
const montantInput = document.getElementById('montant');
const telephoneInput = document.getElementById('telephone');
const priseEnChargeInput = document.getElementById(
  'prise_en_charge_commission'
);

const fraisEl = document.getElementById('frais');
const commissionEl = document.getElementById('commission');
const recevraEl = document.getElementById('recevra');
const totalEl = document.getElementById('total');
const messageOperateurEl = document.getElementById(
  'message-operateur'
);

let timer;

function formatAr(nombre) {
  const valeur = Number(nombre) || 0;

  return new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 0
  }).format(valeur) + ' Ar';
}

function nettoyerTelephone(telephone) {
  return telephone.replace(/\D/g, '');
}

function reinitialiserCalcul() {
  fraisEl.textContent = '0 Ar';
  commissionEl.textContent = '0 Ar';
  recevraEl.textContent = '0 Ar';
  totalEl.textContent = '0 Ar';

  messageOperateurEl.textContent = '';
  messageOperateurEl.style.display = 'none';
}

function calculerFrais() {
  clearTimeout(timer);

  const montant = parseFloat(montantInput.value) || 0;
  const telephone = nettoyerTelephone(telephoneInput.value);

  const priseEnChargeCommission = priseEnChargeInput.checked
    ? 1
    : 0;

  if (montant <= 0) {
    reinitialiserCalcul();
    return;
  }

  /*
   * On attend au moins les trois chiffres du préfixe.
   * Le backend vérifiera ensuite que le numéro complet est valide.
   */
  if (telephone.length < 3) {
    reinitialiserCalcul();
    return;
  }

  timer = setTimeout(() => {
    const body = new URLSearchParams();

    body.append('montant', montant);
    body.append('telephone', telephone);
    body.append('type_operation_id', 3);
    body.append(
      'prise_en_charge_commission',
      priseEnChargeCommission
    );

    body.append(
      '<?= csrf_token() ?>',
      '<?= csrf_hash() ?>'
    );

    fetch('<?= site_url('calculer-frais') ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: body.toString()
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Erreur lors du calcul');
        }

        return response.json();
      })
      .then(data => {
        if (data.error) {
          reinitialiserCalcul();

          messageOperateurEl.textContent = data.error;
          messageOperateurEl.className = 'alert alert-error';
          messageOperateurEl.style.display = 'block';

          return;
        }

        fraisEl.textContent = formatAr(data.frais);
        commissionEl.textContent = formatAr(data.commission);
        recevraEl.textContent = formatAr(data.montant_recu);
        totalEl.textContent = formatAr(data.total);

        if (data.autre_operateur) {
          messageOperateurEl.textContent =
            'Transfert vers ' +
            data.nom_operateur +
            ' — commission : ' +
            data.pourcentage_commission +
            ' %';

          messageOperateurEl.className = 'alert';
          messageOperateurEl.style.display = 'block';
        } else {
          messageOperateurEl.textContent =
            'Transfert vers un numéro de notre opérateur';

          messageOperateurEl.className = 'alert';
          messageOperateurEl.style.display = 'block';
        }
      })
      .catch(() => {
        reinitialiserCalcul();

        messageOperateurEl.textContent =
          'Impossible de calculer les frais actuellement';

        messageOperateurEl.className = 'alert alert-error';
        messageOperateurEl.style.display = 'block';
      });
  }, 400);
}

montantInput.addEventListener('input', calculerFrais);
telephoneInput.addEventListener('input', calculerFrais);
priseEnChargeInput.addEventListener('change', calculerFrais);
</script>

</body>
</html>