CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE autre_operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL
);



CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    autre_operateur_id INTEGER ,
    actif INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (autre_operateur_id) REFERENCES autre_operateur(id)
);


CREATE TABLE commissions_inter_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    autre_operateur_id INTEGER NOT NULL,
    pourcentage REAL NOT NULL,
    FOREIGN KEY (autre_operateur_id) REFERENCES autre_operateur(id)
);

CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);

CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id)
);

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix_id INTEGER NOT NULL,
    telephone TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (prefix_id)
        REFERENCES prefixes(id)
);

CREATE TABLE comptes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    numero_compte TEXT NOT NULL UNIQUE,
    solde REAL NOT NULL DEFAULT 0,
    statut TEXT NOT NULL DEFAULT 'ACTIF',

    FOREIGN KEY (client_id)
        REFERENCES clients(id)
);

CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    compte_source_id INTEGER,
    compte_destination_id INTEGER,
    montant REAL NOT NULL,
    frais REAL NOT NULL DEFAULT 0,
    statut TEXT NOT NULL DEFAULT 'SUCCES',
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (type_operation_id)
        REFERENCES types_operations(id),

    FOREIGN KEY (compte_source_id)
        REFERENCES comptes(id),

    FOREIGN KEY (compte_destination_id)
        REFERENCES comptes(id)
);





-- Autres opérateurs
INSERT INTO autre_operateur (nom) VALUES
('Orange'),
('Airtel');

-- Préfixes des autres opérateurs
INSERT INTO prefixes (prefixe, autre_operateur_id) VALUES
('032', 1),  -- Orange
('031', 2);  -- Airtel

-- Commissions inter-opérateurs
INSERT INTO commissions_inter_operateurs (autre_operateur_id, pourcentage) VALUES
(1, 2.0),   -- 2% vers Orange
(2, 2.5);   -- 2.5% vers Airtel

INSERT INTO types_operations (libelle) VALUES
('DEPOT'),
('RETRAIT'),
('TRANSFERT');

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


INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais)
VALUES
-- RETRAIT
(2, 0, 100000, 500),
(2, 100001, 1000000, 1000),

-- TRANSFERT
(3, 0, 100000, 1000),
(3, 100001, 1000000, 2000);


