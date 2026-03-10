# 🍔 Billy's Fast Food - Application Web

Application web complète pour un restaurant Fast Food avec commandes en ligne, dashboard cuisine (KDS) et administration complète.

## 🚀 Installation

### Prérequis
- **WampServer** (ou XAMPP) avec PHP 8.0+ et MySQL
- Module Apache `mod_rewrite` activé

### Étapes

1. **Base de données** : Ouvrir phpMyAdmin et exécuter le fichier SQL :
   ```
   storage/migrations/001_create_database.sql
   ```
   Cela crée la base `billys_fastfood` avec toutes les tables et données de démonstration.

2. **Configuration** : Vérifier le fichier `.env` à la racine du projet :
   ```
   DB_HOST=127.0.0.1
   DB_DATABASE=billys_fastfood
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Apache mod_rewrite** : S'assurer que `mod_rewrite` est activé dans WampServer.

4. **Accéder au site** : `http://localhost/Billys_tst/public/`

## 👥 Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@billys.com | password |
| Cuisine | cuisine@billys.com | password |
| Client | client@billys.com | password |

> **Note** : Le hash dans la migration correspond au mot de passe `password`. Changez les mots de passe en production.

## 📁 Architecture du projet

```
Billys_tst/
├── app/
│   ├── Controllers/        # Contrôleurs MVC + API
│   ├── Core/               # Framework (Router, Database, Auth, Session, CSRF...)
│   ├── Models/             # Modèles de données (ORM léger)
│   └── Views/              # Templates PHP
│       ├── layouts/        # Layouts (main, admin, kitchen)
│       ├── home/           # Page d'accueil
│       ├── menu/           # Menu et détail produit
│       ├── cart/           # Panier
│       ├── auth/           # Login / Register
│       ├── account/        # Compte client
│       ├── admin/          # Dashboard admin
│       ├── kitchen/        # Dashboard cuisine (KDS)
│       └── errors/         # Pages d'erreur
├── public/                 # Point d'entrée web
│   ├── index.php           # Front controller
│   ├── .htaccess           # Réécriture URL
│   └── assets/
│       ├── css/            # Stylesheets
│       ├── js/             # JavaScript
│       └── uploads/        # Images uploadées
├── routes/
│   └── web.php             # Définition des routes
├── storage/
│   ├── migrations/         # Schéma SQL
│   └── logs/               # Logs applicatifs
├── .env                    # Configuration environnement
├── .htaccess               # Redirection vers public/
└── README.md
```

## 🎯 Fonctionnalités

### Frontend Client
- **Accueil** avec sélection parmi 3 restaurants
- **Menu dynamique** avec catégories, filtres, prix, statuts
- **Fiche produit** avec options personnalisables
- **Panier** dynamique (JS) avec modification quantités
- **Commande en ligne** avec validation
- **Compte utilisateur** avec historique des commandes

### Dashboard Cuisine (KDS)
- Affichage temps réel des commandes (AJAX polling 5s)
- Gestion des statuts : Nouvelle → En préparation → Prête → Terminée
- Filtrage par restaurant
- Notification sonore pour nouvelles commandes
- Interface plein écran optimisée

### Dashboard Admin
- **Tableau de bord** avec statistiques (CA, commandes, etc.)
- **CRUD Produits** : ajouter, modifier, supprimer, upload images, activer/désactiver
- **CRUD Catégories** : gestion complète avec ordre d'affichage
- **Gestion restaurants** : modifier informations, activer/désactiver
- **Suivi commandes** : liste complète avec détails

## 🔐 Sécurité

- PDO avec requêtes préparées (anti SQL injection)
- Protection XSS (htmlspecialchars sur toutes les sorties)
- Protection CSRF (token par session)
- Hash des mots de passe (password_hash / password_verify)
- Sessions sécurisées (httponly, samesite, régénération ID)
- Middleware d'authentification par rôle (admin, kitchen, client)
- Validation serveur stricte
- Configuration via `.env` (pas de credentials en dur)

## 🏪 Multi-restaurants

3 points de vente avec :
- Disponibilités produits différentes par restaurant
- Commandes associées à un restaurant spécifique
- Panier lié à un seul restaurant à la fois

## 📱 Responsive

- **Mobile-first** : optimisé pour téléphone
- **Tablet** : grilles 2 colonnes (600px+)
- **Desktop** : navigation complète, grilles 3-4 colonnes (900px+)
- Lazy loading des images
- CSS Flexbox + Grid

## 🛠️ Stack technique

| Technologie | Usage |
|------------|-------|
| PHP 8+ (POO) | Backend, MVC, routing, API |
| MySQL | Base de données relationnelle |
| HTML5 | Structure des pages |
| CSS3 | Design responsive mobile-first |
| JavaScript ES6 | Panier, AJAX, UI dynamique |
| PDO | Accès base de données sécurisé |
