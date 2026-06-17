-- ============================================================
-- Direction des Ressources Humaines (DRH)
-- Script de création de la base de données
-- MySQL / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS drh_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE drh_db;

-- ------------------------------------------------------------
-- Table: admins (utilisateurs administrateurs)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(150),
    role ENUM('admin', 'superadmin') DEFAULT 'admin',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE UNIQUE INDEX idx_admins_login ON admins(login);

-- ------------------------------------------------------------
-- Table: activity_logs (journaux d'activité)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT DEFAULT 0,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: actualites (actualités, événements, communiqués)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS actualites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    image VARCHAR(255),
    category ENUM('actualite', 'evenement', 'communique') DEFAULT 'actualite',
    status ENUM('publie', 'brouillon') DEFAULT 'brouillon',
    featured BOOLEAN DEFAULT FALSE,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: documents (documents téléchargeables)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_size VARCHAR(20),
    file_type VARCHAR(50),
    category VARCHAR(100),
    downloads INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: services (présentation des divisions)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    description TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'building',
    missions TEXT,
    chef_name VARCHAR(150),
    chef_title VARCHAR(150),
    order_num INT DEFAULT 0,
    status ENUM('actif', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: messages (messages du formulaire de contact)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    lu BOOLEAN DEFAULT FALSE,
    replied BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: settings (paramètres du site)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table: pages (pages personnalisables)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT,
    meta_description VARCHAR(255),
    status ENUM('publie', 'brouillon') DEFAULT 'brouillon',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Insertion des données initiales
-- ------------------------------------------------------------

-- Admin par défaut (mot de passe: admin123)
INSERT INTO admins (username, password, email, full_name, role)
VALUES ('admin', '$2y$12$gVCJgSMhL0S8YUxuR6HT8u/pH4zAhs89K326or7tTGUHZxrssgd5G', 'contact@drh-securite.gouv', 'Administrateur DRH', 'superadmin');

-- Paramètres par défaut
INSERT INTO settings (key_name, value) VALUES
('site_name', 'Direction des Ressources Humaines'),
('site_description', 'Site officiel de la Direction des Ressources Humaines du Ministère de la Sécurité et de la Protection Civile'),
('site_email', 'contact@drh-securite.gouv'),
('site_phone', '+225 XX XX XX XX'),
('site_address', 'Abidjan, Côte d\'Ivoire'),
('directeur_name', 'Monsieur le Directeur'),
('directeur_title', 'Directeur des Ressources Humaines'),
('directeur_message', 'Bienvenue sur le portail officiel de la Direction des Ressources Humaines. Notre équipe est engagée au service des agents et du personnel du ministère.');

-- Services par défaut
INSERT INTO services (title, subtitle, description, icon, missions, order_num) VALUES
(
    'Division de la Gestion des Carrières',
    'Suivi et accompagnement des parcours professionnels',
    'La Division de la Gestion des Carrières assure le suivi administratif individuel des agents, la tenue des dossiers, l\'avancement, la promotion et la mobilité professionnelle.',
    'user-tie',
    'Gestion des dossiers individuels;Suivi des avancements et promotions;Gestion des mutations et affectations;Délivrance des attestations administratives',
    1
),
(
    'Division de la Formation, de l\'Emploi et des Compétences',
    'Développement des ressources humaines',
    'Cette division élabore et met en œuvre la politique de formation continue, gère les programmes de renforcement des capacités et assure la gestion prévisionnelle des emplois et compétences.',
    'graduation-cap',
    'Élaboration du plan de formation;Organisation des sessions de formation;Gestion des stages et bourses;Analyse des besoins en compétences',
    2
),
(
    'Centre de Documentation et d\'Informatique',
    'Gestion de l\'information et des systèmes',
    'Le Centre de Documentation et d\'Informatique assure la gestion documentaire, l\'archivage, la maintenance des systèmes d\'information et le support technique au sein de la direction.',
    'server',
    'Gestion de la documentation technique;Archivage des dossiers;Maintenance informatique;Support technique aux utilisateurs',
    3
),
(
    'Division de la Solde et des Émoluments',
    'Traitement des rémunérations',
    'La Division de la Solde et des Émoluments est responsable du calcul et du traitement des salaires, indemnités et avantages sociaux du personnel du ministère.',
    'calculator',
    'Calcul et traitement des salaires;Gestion des indemnités;Suivi des avantages sociaux;Traitement des déclarations fiscales',
    4
);

-- Exemple d'actualité
INSERT INTO actualites (title, slug, content, excerpt, category, status, featured) VALUES
(
    'Bienvenue sur le nouveau portail RH',
    'bienvenue-nouveau-portail-rh',
    '<p>Nous sommes heureux de vous annoncer le lancement de notre nouveau portail RH. Ce site a été conçu pour vous offrir un accès simplifié à toutes les informations et services relevant des ressources humaines au sein de notre ministère.</p><p>Vous y trouverez toutes les actualités, les documents administratifs, ainsi que les informations utiles concernant la gestion de votre carrière.</p>',
    'Découvrez le nouveau portail officiel de la Direction des Ressources Humaines, conçu pour mieux vous servir.',
    'actualite',
    'publie',
    TRUE
);
