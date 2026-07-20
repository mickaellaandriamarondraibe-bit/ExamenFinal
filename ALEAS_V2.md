# Aléas possibles pour le sujet V2

## 1. Plafond journalier par client

### Demande possible du prof

Un client ne peut pas transférer plus de `2 000 000 Ar` par jour.

### Idée

On additionne tous les transferts faits aujourd'hui par le compte source.

Exemple de requête :

```sql
SELECT SUM(montant)
FROM transactions
WHERE compte_source_id = 1
AND type_operation_id = 3
AND DATE(date_transaction) = '2026-07-21';
```

### Dans `TransactionModel.php`

Créer une fonction :

```php
public function getTotalTransfertJour($compteId)
{
    return $this
        ->selectSum('montant', 'total')
        ->where('compte_source_id', $compteId)
        ->where('type_operation_id', 3)
        ->where('DATE(date_transaction)', date('Y-m-d'))
        ->first();
}
```

### Dans `TransactionController.php`

Créer une fonction :

```php
private function verifierPlafondJournalier($transactionModel, $compteId, $montant)
{
    $resultat = $transactionModel->getTotalTransfertJour($compteId);
    $totalJour = $resultat['total'] ?? 0;

    if ($totalJour + $montant > 2000000) {
        return 'Tu as atteint le montant maximum de transfert pour aujourd hui. Reessaie demain.';
    }

    return null;
}
```

Puis dans `enregistrerTransfert()`, avant d'enregistrer :

```php
$erreurPlafond = $this->verifierPlafondJournalier($transactionModel, $source['id'], $montant);

if ($erreurPlafond) {
    return $this->retourErreur($erreurPlafond);
}
```

### Dans la vue

Si la vue affiche deja les erreurs avec `session()->getFlashdata('error')`, il n'y a rien d'autre a faire.


## 2. Transfert multiple avec montant different par destinataire

### Demande possible du prof

Au lieu de diviser un montant total, chaque destinataire doit avoir son propre montant.

### Idée

Dans la vue, chaque ligne aura :

- un numero
- un montant

Exemple :

```html
<input type="text" name="telephones[]" placeholder="Numero">
<input type="number" name="montants[]" placeholder="Montant">
```

### Dans `TransactionController.php`

On recupere les deux tableaux :

```php
$telephones = $this->request->getPost('telephones');
$montants = $this->request->getPost('montants');
```

Puis on parcourt :

```php
foreach ($telephones as $index => $telephone) {
    $montant = $montants[$index];

    if ($montant <= 0) {
        return $this->retourErreur('Montant invalide pour un destinataire');
    }
}
```

### Ce qu'il faut changer

Il faut supprimer la logique qui fait :

```php
$montantParDestinataire = $montantTotal / $nombreDestinataires;
```

Et utiliser le montant de chaque ligne.


## 3. Annuler une transaction

### Demande possible du prof

L'operateur peut annuler une transaction.

### Idée

On ajoute un statut :

```text
SUCCES
ANNULE
```

Si on annule :

- on remet l'argent au compte source
- on retire l'argent du compte destination
- on met la transaction en `ANNULE`

### Dans `TransactionController.php`

Créer une fonction :

```php
public function annulerTransaction($id)
{
    $transactionModel = new TransactionModel();
    $compteModel = new CompteModel();

    $transaction = $transactionModel->find($id);

    if (!$transaction) {
        return redirect()->back()->with('error', 'Transaction introuvable');
    }

    if ($transaction['statut'] == 'ANNULE') {
        return redirect()->back()->with('error', 'Transaction deja annulee');
    }

    $source = $compteModel->find($transaction['compte_source_id']);
    $destination = $compteModel->find($transaction['compte_destination_id']);

    $totalDebite = $transaction['montant'] + $transaction['frais'] + $transaction['commission'];

    $compteModel->update($source['id'], [
        'solde' => $source['solde'] + $totalDebite
    ]);

    $compteModel->update($destination['id'], [
        'solde' => $destination['solde'] - $transaction['montant_recu']
    ]);

    $transactionModel->update($id, [
        'statut' => 'ANNULE'
    ]);

    return redirect()->back()->with('success', 'Transaction annulee');
}
```

### Attention

Pour un depot ou un retrait, la logique d'annulation n'est pas exactement la meme.
Donc il faut verifier le `type_operation_id`.


## 4. Commission differente selon le montant

### Demande possible du prof

Orange prend `2%` jusqu'a `500 000 Ar`, puis `3%` au-dessus.

### Idée

La table actuelle `commissions_inter_operateurs` contient seulement :

```text
autre_operateur_id
pourcentage
```

Il faut ajouter des colonnes :

```sql
montant_min
montant_max
```

### Exemple de nouvelle table

```sql
CREATE TABLE commissions_inter_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    autre_operateur_id INTEGER NOT NULL,
    montant_min INTEGER NOT NULL,
    montant_max INTEGER NOT NULL,
    pourcentage REAL NOT NULL
);
```

### Dans `CommissionInterOperateurModel.php`

Créer une fonction :

```php
public function getCommissionByMontant($autreOperateurId, $montant)
{
    return $this
        ->where('autre_operateur_id', $autreOperateurId)
        ->where('montant_min <=', $montant)
        ->where('montant_max >=', $montant)
        ->first();
}
```

### Dans `TransactionController.php`

Dans la fonction qui calcule la commission, remplacer la recherche simple par :

```php
$commissionData = $commissionModel->getCommissionByMontant($autreOperateurId, $montant);
```


## 5. Solde minimum obligatoire

### Demande possible du prof

Apres un transfert ou un retrait, le compte doit toujours garder au moins `1 000 Ar`.

### Idée

Avant de debiter, on verifie :

```php
$soldeApresOperation = $compte['solde'] - $total;
```

Si le solde restant est inferieur a `1000`, on refuse.

### Dans `TransactionController.php`

Créer une fonction :

```php
private function verifierSoldeMinimum($compte, $total)
{
    $soldeMinimum = 1000;
    $soldeRestant = $compte['solde'] - $total;

    if ($soldeRestant < $soldeMinimum) {
        return 'Operation refusee. Il faut garder au moins 1 000 Ar sur le compte.';
    }

    return null;
}
```

Puis dans `enregistrerTransfert()` ou `enregistrerRetrait()` :

```php
$erreurSoldeMinimum = $this->verifierSoldeMinimum($source, $couts['total']);

if ($erreurSoldeMinimum) {
    return $this->retourErreur($erreurSoldeMinimum);
}
```

Pour le retrait, utiliser :

```php
$erreurSoldeMinimum = $this->verifierSoldeMinimum($compte, $total);
```


## 6. Heure limite pour faire un transfert

### Demande possible du prof

Les transferts sont autorises seulement entre `06:00` et `22:00`.

### Idee

Avant d'enregistrer le transfert, on verifie l'heure actuelle.

### Dans `TransactionController.php`

Créer une fonction :

```php
private function verifierHeureTransfert()
{
    $heure = date('H:i');

    if ($heure < '06:00' || $heure > '22:00') {
        return 'Les transferts sont disponibles seulement entre 06:00 et 22:00.';
    }

    return null;
}
```

Puis dans `enregistrerTransfert()` :

```php
$erreurHeure = $this->verifierHeureTransfert();

if ($erreurHeure) {
    return $this->retourErreur($erreurHeure);
}
```

On peut faire pareil dans `enregistrerTransfertMultiple()`.


## 7. Nombre maximum de destinataires

### Demande possible du prof

Dans un transfert multiple, on ne peut pas envoyer a plus de `5` destinataires.

### Idee

On compte les numeros dans le tableau `$telephones`.

### Dans `TransactionController.php`

Dans la fonction de validation du transfert multiple, ajouter :

```php
if (count($telephones) > 5) {
    return 'Vous ne pouvez pas envoyer a plus de 5 destinataires.';
}
```

### Dans la vue

On peut aussi bloquer le bouton `+ Ajouter` quand il y a deja 5 lignes.

Exemple :

```javascript
if (document.querySelectorAll('.destinataire-ligne').length >= 5) {
    alert('Maximum 5 destinataires');
    return;
}
```


## 8. Frais reduits pour les gros montants

### Demande possible du prof

Si le montant est superieur a `1 000 000 Ar`, on reduit les frais de transfert de `50%`.

### Idee

On calcule d'abord le frais normal, puis on applique la reduction.

### Dans `TransactionController.php`

Après avoir trouvé `$fraisTransfert` :

```php
if ($montant > 1000000) {
    $fraisTransfert = $fraisTransfert / 2;
}
```

### Variante plus propre

Créer une petite fonction :

```php
private function appliquerReductionGrosMontant($montant, $frais)
{
    if ($montant > 1000000) {
        return $frais / 2;
    }

    return $frais;
}
```

Puis utiliser :

```php
$fraisTransfert = $this->appliquerReductionGrosMontant($montant, $fraisTransfert);
```


## 9. Bloquer un compte client

### Demande possible du prof

Si le compte est `BLOQUE`, le client ne peut plus faire de depot, retrait ou transfert.

### Idee

La table `comptes` a deja une colonne `statut`.

On verifie le statut avant chaque operation.

### Dans `TransactionController.php`

Créer une fonction :

```php
private function verifierCompteActif($compte)
{
    if ($compte['statut'] != 'ACTIF') {
        return 'Votre compte est bloque. Operation impossible.';
    }

    return null;
}
```

Puis dans `enregistrerTransfert()` :

```php
$erreurCompte = $this->verifierCompteActif($source);

if ($erreurCompte) {
    return $this->retourErreur($erreurCompte);
}
```

Faire pareil dans `enregistrerDepot()` et `enregistrerRetrait()`.


## 10. Rechercher les transactions par date côté opérateur

### Demande possible du prof

L'operateur veut filtrer les transactions entre deux dates.

### Idee

Ajouter deux champs dans la vue :

- date debut
- date fin

Puis filtrer dans le controleur.

### Dans `TransactionController.php`

Dans la fonction `index()` côté operateur :

```php
$dateDebut = $this->request->getGet('date_debut');
$dateFin = $this->request->getGet('date_fin');

$transactionModel = new TransactionModel();
$query = $transactionModel->orderBy('date_transaction', 'DESC');

if ($dateDebut != '') {
    $query->where('DATE(date_transaction) >=', $dateDebut);
}

if ($dateFin != '') {
    $query->where('DATE(date_transaction) <=', $dateFin);
}

$data = [
    'title' => 'Liste des transactions',
    'transactions' => $query->findAll(),
];
```

### Dans la vue `transaction/all.php`

Ajouter un formulaire en haut :

```html
<form method="get">
    <input type="date" name="date_debut">
    <input type="date" name="date_fin">
    <button type="submit">Filtrer</button>
</form>
```


# Resume rapide

Les aléas possibles sont :

1. plafond journalier
2. montant different par destinataire
3. annulation de transaction
4. commission selon tranche de montant
5. solde minimum obligatoire
6. heure limite pour faire un transfert
7. nombre maximum de destinataires
8. frais reduits pour gros montant
9. compte client bloque
10. recherche des transactions par date

Dans presque tous les cas, il faut faire :

1. une fonction dans le Model pour chercher les donnees
2. une fonction simple dans le Controller pour verifier
3. un `if` avant l'enregistrement
4. un message d'erreur dans la vue
