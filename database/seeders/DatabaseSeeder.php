<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AttributeSet;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductSku;
use App\Models\ProductImage;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\BankAccount;
use App\Models\Coupon;
use App\Models\BlogPost;
use App\Models\Banner;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'System Owner with full access'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator access'],
            ['name' => 'Store Manager', 'slug' => 'store-manager', 'description' => 'Manages store sales and staff'],
        ];

        $roleModels = [];
        foreach ($roles as $role) {
            $roleModels[$role['slug']] = Role::create($role);
        }

        // 2. Seed Permissions
        $permissions = [
            ['name' => 'Manage Catalog', 'slug' => 'catalog.manage', 'module' => 'Catalog', 'description' => 'Create, edit, delete products, categories, brands'],
            ['name' => 'Manage Inventory', 'slug' => 'inventory.manage', 'module' => 'Inventory', 'description' => 'Manage stock, stock transfers, adjustments'],
            ['name' => 'Manage Orders', 'slug' => 'orders.manage', 'module' => 'Orders', 'description' => 'View and process orders'],
            ['name' => 'Manage Users', 'slug' => 'users.manage', 'module' => 'Users', 'description' => 'Manage system staff users and roles'],
        ];

        foreach ($permissions as $permission) {
            $permModel = Permission::create($permission);
            $roleModels['super-admin']->permissions()->attach($permModel);
            $roleModels['admin']->permissions()->attach($permModel);
        }

        // 3. Seed Users
        $superAdminUser = User::create([
            'employee_code' => 'EMP-001',
            'name' => 'Nishar Ansari',
            'email' => 'admin@ansarihandloom.com',
            'phone' => '9876543210',
            'password' => bcrypt('admin123'),
            'role_id' => $roleModels['super-admin']->id,
            'status' => 'active',
        ]);

        // 4. Seed Categories
        $saree = Category::create(['name' => 'Sarees', 'slug' => 'sarees', 'sort_order' => 1]);
        $suit = Category::create(['name' => 'Suits', 'slug' => 'suits', 'sort_order' => 2]);
        $dressMaterial = Category::create(['name' => 'Dress Materials', 'slug' => 'dress-materials', 'sort_order' => 3]);
        $dupatta = Category::create(['name' => 'Dupattas', 'slug' => 'dupattas', 'sort_order' => 4]);
        $lehenga = Category::create(['name' => 'Lehengas', 'slug' => 'lehengas', 'sort_order' => 5]);

        // 5. Seed Brands
        $ansariBrand = Brand::create(['name' => 'Ansari Handloom', 'slug' => 'ansari-handloom', 'description' => 'Original handloom weaver creations']);
        $technexesBrand = Brand::create(['name' => 'Technexes Premium', 'slug' => 'technexes-premium', 'description' => 'Curated modern wear designs']);

        // 6. Seed Global Attributes
        $colorAttr = Attribute::create(['name' => 'Colour', 'code' => 'color', 'input_type' => 'color_picker', 'is_variant' => 1, 'status' => 'active']);
        $patternAttr = Attribute::create(['name' => 'Pattern', 'code' => 'pattern', 'input_type' => 'dropdown', 'is_variant' => 1, 'status' => 'active']);
        $borderAttr = Attribute::create(['name' => 'Border', 'code' => 'border', 'input_type' => 'dropdown', 'is_variant' => 1, 'status' => 'active']);
        $fabricAttr = Attribute::create(['name' => 'Fabric', 'code' => 'fabric', 'input_type' => 'dropdown', 'is_variant' => 0, 'status' => 'active']);
        $washCareAttr = Attribute::create(['name' => 'Wash Care', 'code' => 'wash_care', 'input_type' => 'textarea', 'is_variant' => 0, 'status' => 'active']);

        // Seed Predefined Values
        $valRed = AttributeValue::create(['attribute_id' => $colorAttr->id, 'value' => 'Crimson Red', 'color_code' => '#DC2626']);
        $valBlue = AttributeValue::create(['attribute_id' => $colorAttr->id, 'value' => 'Royal Blue', 'color_code' => '#2563EB']);
        $valGreen = AttributeValue::create(['attribute_id' => $colorAttr->id, 'value' => 'Forest Green', 'color_code' => '#15803D']);
        $valPink = AttributeValue::create(['attribute_id' => $colorAttr->id, 'value' => 'Blush Pink', 'color_code' => '#EC4899']);

        $valBagh = AttributeValue::create(['attribute_id' => $patternAttr->id, 'value' => 'Bagh Print']);
        $valPlain = AttributeValue::create(['attribute_id' => $patternAttr->id, 'value' => 'Plain']);
        $valPrinted = AttributeValue::create(['attribute_id' => $patternAttr->id, 'value' => 'Printed']);

        $valZari = AttributeValue::create(['attribute_id' => $borderAttr->id, 'value' => 'Gold Zari Brocade']);
        $valThin = AttributeValue::create(['attribute_id' => $borderAttr->id, 'value' => 'Thin Border']);

        $valSilk = AttributeValue::create(['attribute_id' => $fabricAttr->id, 'value' => 'Pure Banarasi Silk']);
        $valCotton = AttributeValue::create(['attribute_id' => $fabricAttr->id, 'value' => 'Organic Mul Cotton']);
        $valRawSilk = AttributeValue::create(['attribute_id' => $fabricAttr->id, 'value' => 'Raw Silk']);

        // 7. Seed Attribute Sets
        $sareeSet = AttributeSet::create(['name' => 'Saree Attribute Set', 'status' => 'active']);
        $sareeSet->attributes()->attach([$colorAttr->id, $patternAttr->id, $borderAttr->id, $fabricAttr->id, $washCareAttr->id]);

        $suitSet = AttributeSet::create(['name' => 'Suit Attribute Set', 'status' => 'active']);
        $suitSet->attributes()->attach([$colorAttr->id, $patternAttr->id, $fabricAttr->id, $washCareAttr->id]);

        // 8. Seed Products (Styles)
        $product1 = Product::create([
            'name' => 'Traditional Banarasi Silk Saree',
            'slug' => 'traditional-banarasi-silk-saree',
            'category_id' => $saree->id,
            'brand_id' => $ansariBrand->id,
            'attribute_set_id' => $sareeSet->id,
            'description' => 'A beautifully handwoven traditional Banarasi Silk Saree with exquisite Zari work, direct from our weavers. The rich weave and intricate details make it perfect for weddings and festive occasions.',
            'short_description' => 'Handwoven Banarasi Silk Saree with gold/silver Zari work.',
            'status' => 'active',
        ]);

        // Product 1 Global Attribute Values
        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'attribute_value_id' => $valSilk->id,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'text_value' => 'Dry clean only to preserve fine silk weave.',
        ]);

        $product2 = Product::create([
            'name' => 'Designer Cotton Salwar Suit',
            'slug' => 'designer-cotton-salwar-suit',
            'category_id' => $suit->id,
            'brand_id' => $technexesBrand->id,
            'attribute_set_id' => $suitSet->id,
            'description' => 'A stylish and comfortable Pure Cotton Salwar Suit set with matching dupatta. Suitable for office, daily wear, or casual outings.',
            'short_description' => 'Comfortable pure cotton salwar suit set.',
            'status' => 'active',
        ]);

        // Product 2 Global Attribute Values
        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_value_id' => $valCotton->id,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'text_value' => 'Gentle hand wash with cold water.',
        ]);

        // 9. Seed Product SKUs (Sellable Variants)
        $sku1Red = ProductSku::create([
            'product_id' => $product1->id,
            'sku_code' => 'BAN-SAREE-RED-BAGH',
            'selling_price' => 5500.00,
            'mrp' => 7500.00,
            'cost_price' => 3500.00,
            'stock' => 15,
            'low_stock_alert' => 5,
            'barcode' => '890123456001',
            'weight' => 850.00,
            'length' => 35.00,
            'width' => 25.00,
            'height' => 5.00,
            'status' => 'active',
        ]);
        $sku1Red->attributeValues()->attach([$valRed->id, $valBagh->id, $valZari->id]);

        $sku1Blue = ProductSku::create([
            'product_id' => $product1->id,
            'sku_code' => 'BAN-SAREE-BLUE-BAGH',
            'selling_price' => 5800.00,
            'mrp' => 7800.00,
            'cost_price' => 3700.00,
            'stock' => 8,
            'low_stock_alert' => 3,
            'barcode' => '890123456002',
            'weight' => 860.00,
            'length' => 35.00,
            'width' => 25.00,
            'height' => 5.00,
            'status' => 'active',
        ]);
        $sku1Blue->attributeValues()->attach([$valBlue->id, $valBagh->id, $valZari->id]);

        $sku2Green = ProductSku::create([
            'product_id' => $product2->id,
            'sku_code' => 'COT-SUIT-GRN-PLAIN',
            'selling_price' => 2200.00,
            'mrp' => 3000.00,
            'cost_price' => 1200.00,
            'stock' => 25,
            'low_stock_alert' => 10,
            'barcode' => '890123456003',
            'weight' => 450.00,
            'length' => 28.00,
            'width' => 20.00,
            'height' => 4.00,
            'status' => 'active',
        ]);
        $sku2Green->attributeValues()->attach([$valGreen->id, $valPlain->id]);

        // 10. Seed Images with Multi-dimensional Mapping
        $imgRed = ProductImage::create([
            'product_id' => $product1->id,
            'image_path' => 'saree_red.jpg',
            'title' => 'Red Banarasi Saree Front',
            'alt_text' => 'Red Banarasi Silk Saree',
            'sort_order' => 1,
            'is_primary' => 1,
        ]);
        $imgRed->attributeValues()->attach([$valRed->id, $valBagh->id]);

        $imgBlue = ProductImage::create([
            'product_id' => $product1->id,
            'image_path' => 'saree_blue.jpg',
            'title' => 'Blue Banarasi Saree Front',
            'alt_text' => 'Blue Banarasi Silk Saree',
            'sort_order' => 2,
            'is_primary' => 0,
        ]);
        $imgBlue->attributeValues()->attach([$valBlue->id, $valBagh->id]);

        $imgSuit = ProductImage::create([
            'product_id' => $product2->id,
            'image_path' => 'suit_green.jpg',
            'title' => 'Green Salwar Suit',
            'alt_text' => 'Green Cotton Salwar Suit',
            'sort_order' => 1,
            'is_primary' => 1,
        ]);
        $imgSuit->attributeValues()->attach([$valGreen->id, $valPlain->id]);

        // 11. Seed Customers
        $customer1 = Customer::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9888877777',
            'password' => bcrypt('password'),
            'status' => 'active'
        ]);

        // 12. Seed Orders
        $order1 = Order::create([
            'customer_id' => $customer1->id,
            'order_number' => 'ORD-2026-0001',
            'total_amount' => 11000.00,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'shipping_address' => 'Flat 102, Shanti Vihar, Mumbai, MH - 400001'
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_sku_id' => $sku1Red->id,
            'quantity' => 2,
            'price' => 5500.00
        ]);

        // 13. Seed Vendors
        $vendor1 = Vendor::create([
            'name' => 'Banarasi Silk Looms Ltd',
            'contact_name' => 'Ramesh Weaver',
            'email' => 'ramesh@banarasilooms.com',
            'phone' => '9822233344',
            'address' => 'Weaver Colony, Varanasi, UP',
            'gstin' => '09AAAAA1111A1Z1',
            'status' => 'active'
        ]);

        // 14. Seed Warehouses
        $warehouse1 = Warehouse::create([
            'name' => 'Central Varanasi Depot',
            'location' => 'Main Weaver Hub, Varanasi',
            'status' => 'active'
        ]);

        WarehouseStock::create(['warehouse_id' => $warehouse1->id, 'product_sku_id' => $sku1Red->id, 'stock' => 15]);

        // 15. Seed Bank Accounts
        BankAccount::create([
            'bank_name' => 'State Bank of India',
            'account_name' => 'Ansari Handloom Current Account',
            'account_number' => '123456789012',
            'branch' => 'Varanasi Main Branch',
            'balance' => 450000.00,
            'status' => 'active'
        ]);

        // 16. Seed Coupons
        Coupon::create([
            'code' => 'ANSARI10',
            'type' => 'percentage',
            'value' => 10.00,
            'min_cart_value' => 1000.00,
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+3 months')),
            'is_active' => 1
        ]);

        // 17. Seed Banners
        Banner::create([
            'title' => 'Banarasi Silk Masterpieces',
            'subtitle' => 'Handwoven with pure silk threads directly from weavers.',
            'image_path' => 'saree_red.jpg',
            'click_url' => '/shop?category=sarees',
            'sort_order' => 1,
            'is_active' => 1
        ]);

        // 18. Seed Blogs
        BlogPost::create([
            'title' => 'The Art of Banarasi Silk Weaving',
            'slug' => 'the-art-of-banarasi-silk-weaving',
            'content' => 'Banarasi weaving is an ancient craft passed down through generations. Known for gold and silver Zari work, it requires deep focus and precision.',
            'image' => 'saree_blue.jpg',
            'author_id' => $superAdminUser->id,
            'status' => 'published'
        ]);

        // 19. Seed Reviews
        Review::create([
            'product_id' => $product1->id,
            'customer_id' => $customer1->id,
            'rating' => 5,
            'review_text' => 'Absolutely stunning Banarasi saree.',
            'is_approved' => 1
        ]);
    }
}
