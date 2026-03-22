<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\GlobalSupplement;

class AdminController extends Controller
{
    public function index(): void
    {
        $stats = (new Order())->getStats();
        $recentOrders = (new Order())->getAllWithDetails(10);

        $this->layout('admin.index', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'productCount' => (new Product())->count(),
            'categoryCount' => (new Category())->count(),
            'pageTitle' => 'Administration',
        ], 'layouts.admin');
    }

    public function products(): void
    {
        $this->layout('admin.products', [
            'products' => (new Product())->getAllWithCategory(),
            'pageTitle' => 'Gestion des Produits',
        ], 'layouts.admin');
    }

    public function createProduct(): void
    {
        $this->layout('admin.product-form', [
            'categories' => (new Category())->getActive(),
            'product' => null,
            'allSupplements' => (new GlobalSupplement())->getActive(),
            'assignedSupplementIds' => [],
            'menuPrices' => [],
            'pageTitle' => 'Nouveau Produit',
        ], 'layouts.admin');
    }

    public function storeProduct(): void
    {
        $data = $this->extractProductData();
        if (empty($data['name'])) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/product/create');
            return;
        }

        $imagePath = $this->handleImageUpload();
        $productModel = new Product();

        $createData = [
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'image' => $imagePath,
            'status' => $data['status'],
            'is_featured' => isset($data['is_featured']) ? 1 : 0,
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ];
        if (isset($data['price_double'])) {
            $createData['price_double'] = (float)$data['price_double'];
        }

        $productId = $productModel->create($createData);

        foreach ((new Restaurant())->getActive() as $r) {
            $productModel->updateAvailability((int)$productId, (int)$r['id'], true);
        }

        // Assign supplements
        $supplements = $this->input('supplements', []);
        if (!empty($supplements)) {
            (new GlobalSupplement())->assignToProduct((int)$productId, $supplements);
        }

        // Save burger menu options (Menu M / Menu L)
        $this->saveMenuOptions((int)$productId);

        Session::flash('success', 'Produit créé !');
        $this->redirect('/admin/products');
    }

    public function editProduct(string $id): void
    {
        $productModel = new Product();
        $product = $productModel->getWithDetails((int)$id);
        if (!$product) { $this->redirect('/admin/products'); return; }

        $db = \App\Core\Database::getInstance();
        $menuOptionRows = $db->fetchAll(
            "SELECT name, price_modifier FROM product_options WHERE product_id = :pid AND option_group = 'taille_menu' AND name IN ('Menu M', 'Menu L')",
            ['pid' => (int)$id]
        );
        $menuPrices = [];
        foreach ($menuOptionRows as $mo) {
            $menuPrices[$mo['name']] = (float)$mo['price_modifier'];
        }

        $supplementModel = new GlobalSupplement();
        $this->layout('admin.product-form', [
            'product' => $product,
            'categories' => (new Category())->getActive(),
            'allSupplements' => $supplementModel->getActive(),
            'assignedSupplementIds' => $supplementModel->getAssignedIds((int)$id),
            'restaurantAvailability' => $productModel->getAvailabilityPerRestaurant((int)$id),
            'menuPrices' => $menuPrices,
            'pageTitle' => 'Modifier: ' . $product['name'],
        ], 'layouts.admin');
    }

    public function updateProduct(string $id): void
    {
        $productModel = new Product();
        if (!$productModel->findById((int)$id)) { $this->redirect('/admin/products'); return; }

        $data = $this->extractProductData();
        $imagePath = $this->handleImageUpload();

        $updateData = [
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'description' => $data['description'],
            'price' => (float)$data['price'],
            'status' => $data['status'],
            'is_featured' => isset($data['is_featured']) ? 1 : 0,
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ];
        if ($imagePath) $updateData['image'] = $imagePath;
        if (isset($data['price_double'])) {
            $updateData['price_double'] = (float)$data['price_double'];
        }

        $productModel->update((int)$id, $updateData);

        // Update supplements assignment
        $supplements = $this->input('supplements', []);
        (new GlobalSupplement())->assignToProduct((int)$id, $supplements);

        // Update burger menu options (Menu M / Menu L)
        $this->saveMenuOptions((int)$id);

        // Update availability per restaurant
        $availableRestaurants = $this->input('available_restaurants', []);
        foreach ((new Restaurant())->findAll() as $r) {
            $isAvailable = in_array((string)$r['id'], (array)$availableRestaurants);
            $productModel->updateAvailability((int)$id, (int)$r['id'], $isAvailable);
        }

        Session::flash('success', 'Produit mis à jour !');
        $this->redirect('/admin/products');
    }

    public function deleteProduct(string $id): void
    {
        (new Product())->delete((int)$id);
        Session::flash('success', 'Produit supprimé.');
        $this->redirect('/admin/products');
    }

    public function toggleProduct(string $id): void
    {
        (new Product())->toggleStatus((int)$id);
        Session::flash('success', 'Statut modifié.');
        $this->redirect('/admin/products');
    }

    public function categories(): void
    {
        $this->layout('admin.categories', [
            'categories' => (new Category())->getWithProductCount(),
            'pageTitle' => 'Gestion des Catégories',
        ], 'layouts.admin');
    }

    public function storeCategory(): void
    {
        $name = $this->sanitize($this->input('name', ''));
        if (empty($name)) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/categories');
            return;
        }
        (new Category())->create([
            'name' => $name,
            'slug' => $this->generateSlug($name),
            'description' => $this->sanitize($this->input('description', '')),
            'image' => $this->handleImageUpload(),
            'sort_order' => (int)$this->input('sort_order', '0'),
            'is_active' => 1,
        ]);
        Session::flash('success', 'Catégorie créée !');
        $this->redirect('/admin/categories');
    }

    public function updateCategory(string $id): void
    {
        $name = $this->sanitize($this->input('name', ''));
        if (empty($name)) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/categories');
            return;
        }
        $upd = [
            'name' => $name,
            'slug' => $this->generateSlug($name),
            'description' => $this->sanitize($this->input('description', '')),
            'sort_order' => (int)$this->input('sort_order', '0'),
            'is_active' => $this->input('is_active') ? 1 : 0,
        ];
        $img = $this->handleImageUpload();
        if ($img) $upd['image'] = $img;
        (new Category())->update((int)$id, $upd);
        Session::flash('success', 'Catégorie mise à jour !');
        $this->redirect('/admin/categories');
    }

    public function deleteCategory(string $id): void
    {
        (new Category())->delete((int)$id);
        Session::flash('success', 'Catégorie supprimée.');
        $this->redirect('/admin/categories');
    }

    public function restaurants(): void
    {
        $this->layout('admin.restaurants', [
            'restaurants' => (new Restaurant())->findAll(),
            'pageTitle' => 'Points de Vente',
        ], 'layouts.admin');
    }

    public function updateRestaurant(string $id): void
    {
        (new Restaurant())->update((int)$id, [
            'name' => trim($this->input('name', '')),
            'address' => trim($this->input('address', '')),
            'phone' => trim($this->input('phone', '')),
            'opening_hours' => trim($this->input('opening_hours', '')),
            'is_active' => $this->input('is_active') ? 1 : 0,
        ]);
        Session::flash('success', 'Restaurant mis à jour !');
        $this->redirect('/admin/restaurants');
    }

    public function orders(): void
    {
        $restaurantId = isset($_GET['restaurant_id']) && (int)$_GET['restaurant_id'] > 0
            ? (int)$_GET['restaurant_id']
            : null;
        $this->layout('admin.orders', [
            'orders' => (new Order())->getAllWithDetails(200, $restaurantId),
            'restaurants' => (new Restaurant())->findAll(),
            'selectedRestaurantId' => $restaurantId,
            'pageTitle' => 'Gestion des Commandes',
        ], 'layouts.admin');
    }

    public function orderDetail(string $id): void
    {
        $order = (new Order())->getWithItems((int)$id);
        if (!$order) { $this->redirect('/admin/orders'); return; }
        $this->layout('admin.order-detail', [
            'order' => $order,
            'pageTitle' => 'Commande #' . $order['order_number'],
        ], 'layouts.admin');
    }

    public function storeOption(string $productId): void
    {
        $pid = (int)$productId;
        if (!(new Product())->findById($pid)) {
            $this->redirect('/admin/products');
            return;
        }

        $name = $this->sanitize($this->input('name', ''));
        if (empty($name)) {
            Session::flash('error', 'Le nom de l\'option est obligatoire.');
            $this->redirect('/admin/product/edit/' . $pid);
            return;
        }

        $group = $this->input('option_group', 'supplements');
        if ($group === 'custom') {
            $group = $this->sanitize($this->input('option_group_custom', 'custom'));
        }
        $group = preg_replace('/[^a-z0-9_]/', '_', strtolower($group)) ?: 'custom';

        $type  = $this->input('option_type', 'checkbox');
        $type  = in_array($type, ['radio', 'checkbox'], true) ? $type : 'checkbox';
        $price = (float)str_replace(',', '.', $this->input('price_modifier', '0'));

        \App\Core\Database::getInstance()->insert(
            "INSERT INTO product_options (product_id, name, price_modifier, option_group, option_type, is_active)
             VALUES (:pid, :name, :price, :grp, :type, 1)",
            ['pid' => $pid, 'name' => $name, 'price' => $price, 'grp' => $group, 'type' => $type]
        );

        Session::flash('success', 'Option ajoutée !');
        $this->redirect('/admin/product/edit/' . $pid);
    }

    public function updateOption(string $optionId): void
    {
        $db = \App\Core\Database::getInstance();
        $opt = $db->fetch("SELECT product_id FROM product_options WHERE id = :id", ['id' => (int)$optionId]);
        
        if (!$opt) {
            $this->redirect('/admin/products');
            return;
        }

        $name = $this->sanitize($this->input('name', ''));
        $priceModifier = $this->input('price_modifier', '0');

        if (empty($name)) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/product/edit/' . $opt['product_id']);
            return;
        }

        $db->execute(
            "UPDATE product_options SET name = :name, price_modifier = :price WHERE id = :id",
            [
                'name' => $name,
                'price' => (float)$priceModifier,
                'id' => (int)$optionId
            ]
        );

        Session::flash('success', 'Option mise à jour.');
        $this->redirect('/admin/product/edit/' . $opt['product_id']);
    }

    public function deleteOption(string $optionId): void
    {
        $db = \App\Core\Database::getInstance();
        $opt = $db->fetch("SELECT product_id FROM product_options WHERE id = :id", ['id' => (int)$optionId]);
        if ($opt) {
            $db->execute("DELETE FROM product_options WHERE id = :id", ['id' => (int)$optionId]);
            Session::flash('success', 'Option supprimée.');
            $this->redirect('/admin/product/edit/' . $opt['product_id']);
        } else {
            $this->redirect('/admin/products');
        }
    }

    private function saveMenuOptions(int $productId): void
    {
        $db = \App\Core\Database::getInstance();
        $menuOptions = [
            'Menu M' => $this->input('menu_m_price'),
            'Menu L' => $this->input('menu_l_price'),
        ];
        foreach ($menuOptions as $name => $price) {
            if ($price !== null && $price !== '') {
                $existing = $db->fetch(
                    "SELECT id FROM product_options WHERE product_id = :pid AND name = :name AND option_group = 'taille_menu'",
                    ['pid' => $productId, 'name' => $name]
                );
                if ($existing) {
                    $db->execute(
                        "UPDATE product_options SET price_modifier = :price WHERE id = :id",
                        ['price' => (float)$price, 'id' => $existing['id']]
                    );
                } else {
                    $db->insert(
                        "INSERT INTO product_options (product_id, name, price_modifier, option_group, is_active) VALUES (:pid, :name, :price, 'taille_menu', 1)",
                        ['pid' => $productId, 'name' => $name, 'price' => (float)$price]
                    );
                }
            } else {
                $db->execute(
                    "DELETE FROM product_options WHERE product_id = :pid AND name = :name AND option_group = 'taille_menu'",
                    ['pid' => $productId, 'name' => $name]
                );
            }
        }
    }

    private function extractProductData(): array
    {
        $data = [
            'name' => $this->sanitize($this->input('name', '')),
            'description' => $this->sanitize($this->input('description', '')),
            'price' => $this->input('price', '0'),
            'category_id' => $this->input('category_id', '0'),
            'status' => $this->input('status', 'available'),
            'is_featured' => $this->input('is_featured'),
            'sort_order' => $this->input('sort_order', '0'),
        ];

        // Add burger double price if provided
        $priceDouble = $this->input('price_double');
        if ($priceDouble !== null && $priceDouble !== '') {
            $data['price_double'] = $priceDouble;
        }

        return $data;
    }

    private function handleImageUpload(): ?string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES['image'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['type'], $allowed, true)) return null;
        if ($file['size'] > 5 * 1024 * 1024) return null;

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . $ext;
        $uploadDir = BASE_PATH . '/public/assets/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        return 'assets/uploads/' . $filename;
    }

    private function generateSlug(string $text): string
    {
        if (function_exists('transliterator_transliterate')) {
            $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text) ?: strtolower($text);
        } else {
            $text = strtolower($text);
            $text = str_replace(
                ['à','â','ä','é','è','ê','ë','î','ï','ô','ö','ù','û','ü','ÿ','ç','æ','œ'],
                ['a','a','a','e','e','e','e','i','i','o','o','u','u','u','y','c','ae','oe'],
                $text
            );
        }
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-') . '-' . substr(uniqid(), -4);
    }

    // ── Supplements Management ────────────────────────────────
    public function supplements(): void
    {
        $supplementModel = new GlobalSupplement();
        $this->layout('admin.supplements', [
            'supplements' => $supplementModel->getAll(),
            'pageTitle' => 'Suppléments',
        ], 'layouts.admin');
    }

    public function storeSupplement(): void
    {
        $name = $this->sanitize($this->input('name', ''));
        $price = $this->input('price', '0');

        if (empty($name)) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/supplements');
            return;
        }

        (new GlobalSupplement())->create([
            'name' => $name,
            'price' => (float)$price,
            'is_active' => 1
        ]);

        Session::flash('success', 'Supplément créé.');
        $this->redirect('/admin/supplements');
    }

    public function updateSupplement(string $id): void
    {
        $name = $this->sanitize($this->input('name', ''));
        $price = $this->input('price', '0');
        $isActive = $this->input('is_active', '1');

        if (empty($name)) {
            Session::flash('error', 'Le nom est obligatoire.');
            $this->redirect('/admin/supplements');
            return;
        }

        (new GlobalSupplement())->update((int)$id, [
            'name' => $name,
            'price' => (float)$price,
            'is_active' => (int)$isActive
        ]);

        Session::flash('success', 'Supplément mis à jour.');
        $this->redirect('/admin/supplements');
    }

    public function deleteSupplement(string $id): void
    {
        (new GlobalSupplement())->delete((int)$id);
        Session::flash('success', 'Supplément supprimé.');
        $this->redirect('/admin/supplements');
    }
}
