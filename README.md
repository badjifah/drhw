# DRH - Direction des Ressources Humaines

Site vitrine officiel de la **Direction des Ressources Humaines du Ministère de la Sécurité et de la Protection Civile**.

## Technologies

- **Frontend :** HTML5, CSS3, JavaScript, Tailwind CSS, Font Awesome
- **Backend :** PHP 8+ (orienté objet, sécurisé)
- **Base de données :** MariaDB / MySQL
- **Serveur :** Apache

## Fonctionnalités

### Site public
- Page d'accueil avec présentation dynamique
- À propos (missions, vision, valeurs, historique)
- Nos services (4 divisions)
- Actualités RH (annonces, événements, communiqués)
- Espace documents téléchargeables
- Formulaire de contact

### Administration
- Tableau de bord avec statistiques
- Gestion complète des actualités (CRUD)
- Gestion des documents (upload/delete)
- Gestion des services
- Consultation des messages
- Paramètres du site modifiables

## Installation

### 1. Prérequis
- Apache 2.4+
- PHP 8.0+
- MariaDB 10+ ou MySQL 8+
- Extension PHP : PDO, mysqli, mbstring, intl

### 2. Base de données
```bash
# Créer la base de données et importer le schéma
mysql -u root -p < database/drh.sql
```

### 3. Configuration
Éditer `includes/config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'drh_db');
define('DB_USER', 'root');
define('DB_PASS', 'votre_mot_de_passe');
```

### 4. Déploiement Apache
```apache
# Dans votre fichier de configuration Apache ou .htaccess
DocumentRoot "/chemin/vers/drhw"
<Directory "/chemin/vers/drhw">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

### 5. Permissions
```bash
# Donner les droits d'écriture pour les uploads
chmod 755 assets/uploads
chmod 755 assets/uploads/actualites
chmod 755 assets/uploads/documents
```

### 6. Accès
- **Site public :** http://localhost/drhw
- **Administration :** http://localhost/drhw/admin
- **Identifiants par défaut :**
  - Utilisateur : `admin`
  - Mot de passe : `admin123`

## Structure du projet

```
drhw/
├── index.php              # Page d'accueil
├── about.php              # À propos
├── services.php           # Services
├── actualites.php         # Actualités
├── actualite.php          # Article détaillé
├── documents.php          # Documents
├── contact.php            # Contact
├── .htaccess              # Configuration Apache
├── assets/
│   ├── css/style.css      # Styles personnalisés
│   ├── js/main.js         # JavaScript public
│   ├── js/admin.js        # JavaScript admin
│   └── uploads/           # Fichiers uploadés
├── includes/
│   ├── config.php         # Configuration
│   ├── Database.php       # Classe PDO
│   ├── Session.php        # Gestion session
│   ├── functions.php      # Fonctions utilitaires
│   ├── header.php         # En-tête public
│   └── footer.php         # Pied de page public
├── admin/
│   ├── index.php          # Tableau de bord
│   ├── login.php          # Connexion
│   ├── logout.php         # Déconnexion
│   ├── actualites.php     # Gestion actualités
│   ├── actualite_form.php # Formulaire actualité
│   ├── documents.php      # Gestion documents
│   ├── document_form.php  # Formulaire document
│   ├── services.php       # Gestion services
│   ├── service_form.php   # Formulaire service
│   ├── messages.php       # Messages reçus
│   ├── settings.php       # Paramètres
│   └── includes/
│       ├── admin_header.php
│       └── admin_footer.php
├── api/
│   ├── contact.php        # Traitement formulaire
│   └── download.php       # Téléchargement fichier
└── database/
    └── drh.sql            # Schéma et données initiales
```

## Sécurité

- Connexion administrateur protégée par mot de passe (bcrypt)
- Protection contre les injections SQL (requêtes préparées PDO)
- Validation et sanitisation de toutes les entrées utilisateur
- Sessions sécurisées (HttpOnly, SameSite)
- Protection des fichiers sensibles (.htaccess)
- Uploads sécurisés (vérification extension/taille)
- Headers de sécurité (X-Content-Type-Options, X-Frame-Options)

## Personnalisation

### Couleurs
Les couleurs principales sont définies via Tailwind CSS :
- Primaire : `blue-900` (#1e3a5f)
- Accent : `amber-500` (#f59e0b)

### Contenu statique
Les pages HTML statiques (about, services, etc.) peuvent être modifiées directement dans les fichiers `.php` correspondants.

## Licence

Projet institutionnel - Ministère de la Sécurité et de la Protection Civile
