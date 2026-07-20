# Taches.md

## Livraison 1 - 20/07/2026 : 08h00-13h00 (Tag v1)

### Mickaellah & Idealy (ensemble - 08h00 à 08h30)
- Ont mis en place le projet CodeIgniter 4 (structure, config .env, connexion SQLite)
- Ont conçu ensemble le schéma de la base de données (tables + relations)
- Ont réparti le travail : Mickaellah = côté Opérateur, Idealy = côté Client

### Mickaellah (côté Opérateur)
- A créé base.sql avec les tables prefix, type_operation, bareme_frais et données de test
- A ajouté dans base.sql les tables clients, comptes, transactions et données de test
- A développé PrefixModel et le Controller "Configuration des préfixes" (CRUD des préfixes valables)
- A développé TypeOperationModel (gestion des types dépôt/retrait/transfert)
- A développé BaremeFraisModel (CRUD des tranches de montant/frais, modifiable)
- A créé la vue "Configuration barème" (formulaire admin)
- A développé le Controller "Situation des gains" (calcul des gains via frais retrait/transfert)
- A développé le Controller "Situation des comptes clients" (liste + soldes, vue admin)
- A créé la vue "Dashboard Opérateur" (récapitulatif gains + comptes clients)

### Idealy (côté Client)

- A développé ClientModel (recherche/création automatique de compte par numéro de téléphone)
- A développé ClientController (login automatique par numéro, sans inscription préalable)
- A développé TransactionModel (structure dépôt/retrait/transfert)
- A créé la vue "Solde" (affichage du solde du client connecté)
- A développé le Controller "Dépôt automatique" (crédit du compte)
- A développé le Controller "Retrait automatique" (débit + application du barème de frais)
- A développé le Controller "Transfert" (débit/crédit entre deux clients)
- A créé la vue "Historique des transactions" (liste + filtre par type)

### Mickaellah & Idealy (ensemble - 12h45 à 13h00)
- Ont testé le fonctionnement croisé (retrait applique bien le barème créé par Mickaellah)
- Ont vérifié le fichier base.sql final
- Ont poussé le code et publié le tag v1

---
