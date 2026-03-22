# Migrations : Corrections Base de Données

## Problèmes Courants

### 1. Table 'global_supplements' n'existe pas
```
SQLSTATE[42S02]: Base table or view not found: 1146 La table 'billys_fastfood.global_supplements' n'existe pas
```

### 2. Colonne 'price_double' inconnue
```
SQLSTATE[42S22]: Column not found: 1054 Champ 'price_double' inconnu dans field list
```

Ces erreurs surviennent si vous avez installé la base avant les dernières mises à jour.

## Solution Rapide

### Option 1 : Via phpMyAdmin (Recommandé)

1. Ouvrez **phpMyAdmin**
2. Sélectionnez la base de données `billys_fastfood`
3. Cliquez sur l'onglet **SQL**
4. Exécutez les migrations suivantes **dans l'ordre** :

**Migration 002 - Suppléments globaux :**
```sql
-- Copiez le contenu de storage/migrations/002_add_global_supplements.sql
```

**Migration 003 - Prix burgers :**
```sql
ALTER TABLE `products` 
ADD COLUMN IF NOT EXISTS `price_simple` DECIMAL(8,2) DEFAULT NULL AFTER `price`,
ADD COLUMN IF NOT EXISTS `price_double` DECIMAL(8,2) DEFAULT NULL AFTER `price_simple`;
```

### Option 2 : Via le script d'installation

1. Accédez à `http://localhost/Billys_tst/public/install.php`
2. Cliquez sur "Installer la base de données"
3. Le script détectera automatiquement les tables manquantes et les créera

### Option 3 : Ligne de commande MySQL

```bash
mysql -u root -p billys_fastfood < storage/migrations/002_add_global_supplements.sql
```

## Vérification

Après l'exécution, vérifiez que les tables ont été créées :

```sql
SHOW TABLES LIKE '%supplement%';
```

Vous devriez voir :
- `global_supplements`
- `product_supplements`

## Fonctionnalité

Le système de suppléments globaux permet de :
- Créer des suppléments réutilisables (Bacon, Fromage, etc.)
- Assigner ces suppléments à plusieurs produits
- Gérer les prix de manière centralisée
- Activer/désactiver des suppléments globalement

Accès : **Admin Dashboard** → **Suppléments** (`/admin/supplements`)

## Note

La table `settings` et la page de paramètres ont été supprimées du projet. Les prix des burgers (simple/double) sont maintenant gérés directement via les colonnes `price_simple` et `price_double` de la table `products`.
