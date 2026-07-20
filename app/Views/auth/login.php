<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>

<?php if (session()->getFlashdata('error')) : ?>
    <div style="color: red;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('login') ?>" method="post">
    <label for="telephone">Entrer votre numéro de téléphone</label>

    <input
        type="text"
        name="telephone"
        id="telephone"
        value="<?= old('telephone') ?>"
    >

    <input type="submit" value="Se connecter">
</form>
</body>
</html>