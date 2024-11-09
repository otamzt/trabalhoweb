CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    telefone VARCHAR(15),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para informações de contato recebidas através do site
CREATE TABLE contato (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para informações sobre as obras
CREATE TABLE obras (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    localizacao VARCHAR(200),
    data_inicio DATE,
    data_fim DATE,
    status VARCHAR(50) CHECK (status IN ('Em andamento', 'Concluída', 'Planejada'))
);

CREATE SEQUENCE seq_id_usuarios START 1;
ALTER TABLE usuarios ALTER COLUMN id SET DEFAULT nextval('seq_id_usuarios');

CREATE SEQUENCE seq_id_contato START 1;
ALTER TABLE contato ALTER COLUMN id SET DEFAULT nextval('seq_id_contato');

CREATE SEQUENCE seq_id_obras START 1;
ALTER TABLE obras ALTER COLUMN id SET DEFAULT nextval('seq_id_obras');
