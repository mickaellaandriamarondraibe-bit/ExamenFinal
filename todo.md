# Taches.md

## Livraison 1 - 20/07/2026 : 08h00-13h00

### 🕗 08h00 - 08h30 : Mickaellah & Idealy (ensemble)
- Mise en place du projet CodeIgniter 4 (structure, config `.env`, connexion SQLite)
- Conception commune du schéma de base de données (tables + relations)
- Répartition confirmée : **Mickaellah = côté Opérateur**, **Idealy = côté Client**

---

### 🕣 08h30 - 09h15

**Mickaellah**
- Création `base.sql` : tables `prefix`, `type_operation`, `bareme_frais` + données de test

**Michaellah**
- Ajout dans `base.sql` : tables `clients`, `comptes`, `transactions` + données de test

---

### 🕤 09h15 - 09h30 : Mickaellah & Idealy (ensemble)
- Fusion et test de `base.sql` complet (import SQLite, vérification)
- Mise en place du template Bootstrap commun (`layout.php`, navbar)

---

### 🕤 09h30 - 10h30

**Mickaellah**
- `PrefixModel` + Controller "Configuration des préfixes" (CRUD préfixes)
- `TypeOperationModel` (gestion dépôt/retrait/transfert)

**Idealy**
- `ClientModel` : recherche/création automatique de compte par numéro
- `ClientController` : login automatique par numéro de téléphone (sans inscription)

---

### 🕥 10h30 - 11h30

**Mickaellah**
- `BaremeFraisModel` (CRUD tranches de montant/frais)
- Vue "Configuration barème" (formulaire admin, modifiable)

**Idealy**
- `TransactionModel` (structure dépôt/retrait/transfert)
- Vue "Solde" : affichage du solde du client connecté

---

### 🕦 11h30 - 12h15

**Mickaellah**
- Controller "Situation des gains" (calcul gains via frais retrait/transfert)
- Controller "Situation des comptes clients" (liste + soldes, vue admin)

**Idealy**
- Controller "Dépôt automatique" (crédit du compte)
- Controller "Retrait automatique" (débit + application du barème de frais)

---

### 🕛 12h15 - 12h45

**Mickaellah**
- Vue "Dashboard Opérateur" (récapitulatif gains + comptes clients)

**Idealy**
- Vue "Historique des transactions" (liste + filtre par type)
- Controller "Transfert" (débit/crédit entre deux clients)

---

### 🕧 12h45 - 13h00 : Mickaellah & Idealy (ensemble)
- Tests croisés (le retrait applique bien le barème créé par Mickaellah)
- Vérification finale de `base.sql`
- Push final + création et publication du tag **v1**

---

## Résumé de la charge (équilibrée)

| Étudiant | Blocs de travail solo | Durée solo totale |
|---|---|---|
| Mickaellah | 5 blocs | ~3h30 |
| Idealy | 5 blocs | ~3h30 |
| Ensemble | 3 moments | ~1h00 |