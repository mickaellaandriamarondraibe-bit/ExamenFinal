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
      <svg
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
      >
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


    <?php if (session()->getFlashdata('success')) : ?>

      <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>

    <?php endif; ?>


    <form action="<?= site_url('transfert') ?>" method="post">

      <?= csrf_field() ?>
      

      <div class="field">

        <label for="telephone">
          Numéro destinataire
        </label>

        <div class="input-wrap">

          <input
            type="text"
            name="telephone"
            id="telephone"
            placeholder="Ex : 037 98 765 43"
            value="<?= esc(old('telephone')) ?>"
            required
          >

        </div>

      </div>


      <div class="field">

        <label for="montant">
          Montant à transférer
        </label>

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

          <span class="suffix">
            Ar
          </span>

        </div>

      </div>


      <div class="field">

        <label for="prise_en_charge_commission">

          <input
            type="checkbox"
            name="prise_en_charge_commission"
            id="prise_en_charge_commission"
            value="1"
            <?= old('prise_en_charge_commission') ? 'checked' : '' ?>
          >

          Prendre en charge la commission

        </label>

        <small>
          Si cette option n’est pas cochée, la commission sera retirée du montant reçu.
        </small>

      </div>


      <div class="card">

        <div class="detail-row">

          <span>
            Frais
          </span>

          <span id="frais">
            0 Ar
          </span>

        </div>


        <div class="detail-row">

          <span>
            Commission
          </span>

          <span id="commission">
            0 Ar
          </span>

        </div>


        <div class="detail-row">

          <span>
            Le destinataire recevra
          </span>

          <span id="recevra">
            0 Ar
          </span>

        </div>


        <div class="detail-row total">

          <span>
            Total débité
          </span>

          <span class="val" id="total">
            0 Ar
          </span>

        </div>

      </div>


      <button type="submit" class="btn-primary">
        Confirmer le transfert
      </button>
      <a href="<?= site_url('transfert-multiple') ?>" class="btn-secondary">
  Transfert multiple
</a>

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

const fraisElement = document.getElementById('frais');

const commissionElement = document.getElementById('commission');

const recevraElement = document.getElementById('recevra');

const totalElement = document.getElementById('total');


let timer;


function formatAr(valeur)
{
  return Number(valeur || 0).toLocaleString('fr-FR') + ' Ar';
}


function remettreAZero()
{
  fraisElement.textContent = '0 Ar';

  commissionElement.textContent = '0 Ar';

  recevraElement.textContent = '0 Ar';

  totalElement.textContent = '0 Ar';
}


function calculerTransfert()
{
  clearTimeout(timer);

  const montant = Number(montantInput.value);

  const telephone = telephoneInput.value.replace(/\D/g, '');

  const priseEnChargeCommission =
    priseEnChargeInput.checked ? 1 : 0;


  if (montant <= 0 || telephone.length < 3)
  {
    remettreAZero();

    return;
  }


  timer = setTimeout(function () {

    const donnees = new URLSearchParams();

    donnees.append('montant', montant);

    donnees.append('telephone', telephone);
    donnees.append('type_operation_id', 3);
    donnees.append(
      'prise_en_charge_commission',
      priseEnChargeCommission
    );

    donnees.append(
      '<?= csrf_token() ?>',
      '<?= csrf_hash() ?>'
    );


    fetch('<?= site_url('calculer-frais') ?>', {

      method: 'POST',

      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },

      body: donnees.toString()

    })

    .then(function (response) {

      return response.json();

    })

    .then(function (data) {

      if (data.error)
      {
        remettreAZero();

        return;
      }


      fraisElement.textContent =
        formatAr(data.frais);


      commissionElement.textContent =
        formatAr(data.commission);


      recevraElement.textContent =
        formatAr(data.montant_recu);


      totalElement.textContent =
        formatAr(data.total);

    })

    .catch(function () {

      remettreAZero();

    });

  }, 400);
}


montantInput.addEventListener(
  'input',
  calculerTransfert
);


telephoneInput.addEventListener(
  'input',
  calculerTransfert
);


priseEnChargeInput.addEventListener(
  'change',
  calculerTransfert
);

</script>

</body>
</html>