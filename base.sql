CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    actif INTEGER NOT NULL DEFAULT 1
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

INSERT INTO prefixes (prefixe) VALUES
('033'),
('037');

INSERT INTO types_operations (libelle) VALUES
('DEPOT'),
('RETRAIT'),
('TRANSFERT');