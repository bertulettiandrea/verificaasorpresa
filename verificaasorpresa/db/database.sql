-- Creazione del database
CREATE DATABASE IF NOT EXISTS verificaasorpresa;
USE verificaasorpresa;

-- Struttura tabella Fornitori
CREATE TABLE Fornitori (
    fid VARCHAR(50) PRIMARY KEY,
    fnome VARCHAR(100) NOT NULL,
    indirizzo VARCHAR(255)
);

-- Struttura tabella Pezzi
CREATE TABLE Pezzi (
    pid VARCHAR(50) PRIMARY KEY,
    pnome VARCHAR(100) NOT NULL,
    colore VARCHAR(50)
);

-- Struttura tabella Catalogo
CREATE TABLE Catalogo (
    fid VARCHAR(50),
    pid VARCHAR(50),
    costo DECIMAL(10, 2),
    PRIMARY KEY (fid, pid),
    FOREIGN KEY (fid) REFERENCES Fornitori(fid) ON DELETE CASCADE,
    FOREIGN KEY (pid) REFERENCES Pezzi(pid) ON DELETE CASCADE
);

-- Inserimento dati di test (opzionale, utile per testare gli endpoint)
INSERT INTO Fornitori (fid, fnome, indirizzo) VALUES 
('F1', 'Acme', 'Via Roma 1'),
('F2', 'Global Corp', 'Via Milano 2'),
('F3', 'Rossi Forniture', 'Via Napoli 3');

INSERT INTO Pezzi (pid, pnome, colore) VALUES 
('P1', 'Bullone', 'Rosso'),
('P2', 'Vite', 'Verde'),
('P3', 'Ingranaggio', 'Rosso'),
('P4', 'Piastra', 'Blu');

INSERT INTO Catalogo (fid, pid, costo) VALUES 
('F1', 'P1', 10.50),
('F1', 'P3', 25.00),
('F2', 'P1', 12.00),
('F2', 'P2', 8.00),
('F3', 'P1', 9.50),
('F3', 'P2', 7.50),
('F1', 'P2', 15.00);