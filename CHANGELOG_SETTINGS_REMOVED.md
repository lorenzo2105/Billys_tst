# Changelog - Suppression de la fonctionnalité Paramètres

## Date : 22 mars 2026

### ❌ Fonctionnalités Supprimées

La page **Paramètres** (`/admin/settings`) et toute la fonctionnalité associée ont été retirées du projet.

### 📝 Fichiers Supprimés

- ✅ `app/Views/admin/settings.php` - Vue de la page paramètres
- ✅ `app/Models/Setting.php` - Modèle de gestion des paramètres
- ✅ `storage/migrations/006_create_settings_table.sql` - Migration de la table settings

### 🔧 Fichiers Modifiés

| Fichier | Modification |
|---------|-------------|
| `routes/web.php` | Suppression des routes `/admin/settings` et `/admin/settings/update` |
| `app/Views/layouts/admin.php` | Suppression du lien "⚙️ Paramètres" du menu sidebar |
| `app/Controllers/AdminController.php` | Suppression des méthodes `settings()` et `updateSettings()` + import `Setting` |
| `storage/migrations/FIX_ALL_MISSING_COLUMNS.sql` | Suppression de la création de la table `settings` |
| `README.md` | Ajout de la mention "Suppléments globaux" dans les fonctionnalités admin |
| `MIGRATION_SUPPLEMENTS.md` | Ajout d'une note sur la suppression de la table settings |

### 💡 Alternative

Les prix des burgers (simple/double) qui étaient gérés via les paramètres sont maintenant directement stockés dans les colonnes `price_simple` et `price_double` de la table `products`.

**Gestion des prix burgers :**
- Accédez à **Admin** → **Produits** → **Modifier un produit**
- Remplissez les champs "Prix Burger Simple" et "Prix Burger Double"
- Ces valeurs sont maintenant spécifiques à chaque produit

### ✅ Menu Admin Actuel

Le menu admin contient maintenant :
- 📊 Dashboard
- 🍔 Produits
- 📁 Catégories
- 🏪 Restaurants
- 📋 Commandes
- 🍟 Suppléments

### 🗄️ Base de Données

**Important :** Si vous avez une table `settings` existante dans votre base de données, vous pouvez la supprimer en toute sécurité :

```sql
DROP TABLE IF EXISTS `settings`;
```

Elle n'est plus utilisée par l'application.
