PRAGMA foreign_keys = ON;


-- =========================================================
-- OPÉRATEUR PRINCIPAL / ADMINISTRATEUR
-- =========================================================

CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);


-- =========================================================
-- AUTRES OPÉRATEURS
-- =========================================================

CREATE TABLE autre_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);


-- =========================================================
-- PRÉFIXES TÉLÉPHONIQUES
--
-- autre_operateur_id = NULL :
-- préfixe de notre propre opérateur
--
-- autre_operateur_id renseigné :
-- préfixe d'un autre opérateur
-- =========================================================

CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    autre_operateur_id INTEGER DEFAULT NULL,
    actif INTEGER NOT NULL DEFAULT 1,

    CHECK (actif IN (0, 1)),

    FOREIGN KEY (autre_operateur_id)
        REFERENCES autre_operateur(id)
);


-- =========================================================
-- COMMISSIONS INTER-OPÉRATEURS
-- =========================================================

CREATE TABLE commissions_inter_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    autre_operateur_id INTEGER NOT NULL UNIQUE,
    pourcentage REAL NOT NULL,

    CHECK (pourcentage >= 0),

    FOREIGN KEY (autre_operateur_id)
        REFERENCES autre_operateur(id)
);


-- =========================================================
-- TYPES D'OPÉRATIONS
-- =========================================================

CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);


-- =========================================================
-- BARÈMES DE FRAIS
--
-- Les frais sont ceux de notre opérateur.
-- Les autres opérateurs utilisent seulement une commission.
-- =========================================================

CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    autre_operateur_id INTEGER DEFAULT NULL,
    montant_min INTEGER NOT NULL,
    montant_max INTEGER NOT NULL,
    frais INTEGER NOT NULL DEFAULT 0,

    CHECK (montant_min >= 0),
    CHECK (montant_max >= montant_min),
    CHECK (frais >= 0),

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id),

    FOREIGN KEY (autre_operateur_id)
        REFERENCES autre_operateur(id)
);

CREATE TABLE reduction(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reduction REAL NOT NULL
)

INSERT INTO reduction (reduction) VALUES (2);


-- =========================================================
-- CLIENTS
-- =========================================================

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix_id INTEGER NOT NULL,
    telephone TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (prefix_id)
        REFERENCES prefixes(id)
);


-- =========================================================
-- COMPTES
-- =========================================================

CREATE TABLE comptes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    numero_compte TEXT NOT NULL UNIQUE,
    solde INTEGER NOT NULL DEFAULT 0,
    statut TEXT NOT NULL DEFAULT 'ACTIF',

    CHECK (solde >= 0),
    CHECK (statut IN ('ACTIF', 'INACTIF', 'BLOQUE')),

    FOREIGN KEY (client_id)
        REFERENCES clients(id)
);


-- =========================================================
-- TRANSACTIONS
-- =========================================================

CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    compte_source_id INTEGER DEFAULT NULL,
    compte_destination_id INTEGER DEFAULT NULL,

    -- Montant demandé par le client
    montant INTEGER NOT NULL,

    -- Montant réellement reçu par le destinataire
    montant_recu INTEGER DEFAULT NULL,

    -- Frais fixes venant du barème
    frais INTEGER NOT NULL DEFAULT 0,

    -- Montant de la commission inter-opérateur
    commission INTEGER NOT NULL DEFAULT 0,

    -- 0 : commission retirée du montant reçu
    -- 1 : commission payée en supplément par l'expéditeur
    prise_en_charge_commission INTEGER NOT NULL DEFAULT 0,

    -- NULL si transfert interne
    autre_operateur_id INTEGER DEFAULT NULL,

    statut TEXT NOT NULL DEFAULT 'SUCCES',
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,

    CHECK (montant > 0),
    CHECK (montant_recu IS NULL OR montant_recu >= 0),
    CHECK (frais >= 0),
    CHECK (commission >= 0),
    CHECK (prise_en_charge_commission IN (0, 1)),
    CHECK (statut IN ('SUCCES', 'ECHEC', 'EN_ATTENTE')),

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id),

    FOREIGN KEY (compte_source_id)
        REFERENCES comptes(id),

    FOREIGN KEY (compte_destination_id)
        REFERENCES comptes(id),

    FOREIGN KEY (autre_operateur_id)
        REFERENCES autre_operateur(id)
);


-- =========================================================
-- INSERTION DES AUTRES OPÉRATEURS
-- =========================================================

INSERT INTO autre_operateur (nom)
VALUES
('Orange'),
('Airtel');


-- =========================================================
-- INSERTION DES PRÉFIXES
--
-- 033 et 037 : notre opérateur
-- 032 : Orange
-- 031 : Airtel
-- =========================================================

INSERT INTO prefixes (
    prefixe,
    autre_operateur_id,
    actif
)
VALUES
('033', NULL, 1),
('037', NULL, 1),
('032', 1, 1),
('031', 2, 1);


-- =========================================================
-- COMMISSIONS INTER-OPÉRATEURS
--
-- Orange : 2 %
-- Airtel : 2,5 %
-- =========================================================

INSERT INTO commissions_inter_operateurs (
    autre_operateur_id,
    pourcentage
)
VALUES
(1, 2.0),
(2, 2.5);


-- =========================================================
-- TYPES D'OPÉRATIONS
-- =========================================================

INSERT INTO types_operations (libelle)
VALUES
('DEPOT'),
('RETRAIT'),
('TRANSFERT');


-- =========================================================
-- ADMINISTRATEUR
-- =========================================================

INSERT INTO operateurs (
    nom,
    email,
    mot_de_passe
)
VALUES (
    'Administrateur',
    'admin@gmail.com',
    '$2y$10$/y57SG79ljn7/Z/u2maVhelpRLOVLvtrxKbLfRxA//mgSVgRNmOn2'
);


-- =========================================================
-- BARÈMES DE DÉPÔT
--
-- Le dépôt est gratuit.
-- autre_operateur_id = NULL car le dépôt est effectué
-- directement dans notre système.
-- =========================================================

INSERT INTO baremes_frais (
    type_operation_id,
    autre_operateur_id,
    montant_min,
    montant_max,
    frais
)
VALUES
(1, NULL, 1, 100000, 0),
(1, NULL, 100001, 1000000, 0),
(1, NULL, 1000001, 5000000, 0);


-- =========================================================
-- BARÈMES DE RETRAIT DE NOTRE OPÉRATEUR
-- =========================================================

INSERT INTO baremes_frais (
    type_operation_id,
    autre_operateur_id,
    montant_min,
    montant_max,
    frais
)
VALUES
(2, NULL, 1, 100000, 500),
(2, NULL, 100001, 1000000, 1000),
(2, NULL, 1000001, 5000000, 3000);


-- =========================================================
-- BARÈMES DE TRANSFERT
--
-- Même barème pour un transfert interne ou vers un autre opérateur.
-- Si le destinataire est chez un autre opérateur, une commission
-- est ajoutée avec la table commissions_inter_operateurs.
-- =========================================================

INSERT INTO baremes_frais (
    type_operation_id,
    autre_operateur_id,
    montant_min,
    montant_max,
    frais
)
VALUES
(3, NULL, 1, 100000, 1000),
(3, NULL, 100001, 1000000, 2000),
(3, NULL, 1000001, 5000000, 4000);
