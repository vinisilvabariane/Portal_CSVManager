CREATE DATABASE PortalMultiGarantia;

USE PortalMultiGarantia;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY key,
    username VARCHAR(50) NOT NULL UNIQUE,
    orgao VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    cnpj VARCHAR(14) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'membro',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tabela_seriais (
    NotaFiscal VARCHAR(20) NOT NULL,
    DataFaturamento DATE NOT NULL,
    Cliente VARCHAR(100) NOT NULL,
    Serial VARCHAR(50) NOT NULL,
    Imei VARCHAR(20),
    SKU VARCHAR(20) NOT NULL,
    DataFinalGarantia DATE NOT NULL,
    Status VARCHAR(20) NOT NULL,
    PRIMARY KEY (NotaFiscal, Serial)
);

CREATE TABLE tabela_garantia (
    Cliente VARCHAR(100) NOT NULL,
    SKU VARCHAR(20) NOT NULL,
    TempoGarantiaMeses INT NOT NULL,
    Bateria BOOLEAN NOT NULL,
    PRIMARY KEY (Cliente, SKU)
);

