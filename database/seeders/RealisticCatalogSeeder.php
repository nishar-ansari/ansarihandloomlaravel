<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductSku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Adds a realistic, browsable catalog spread (sarees, suits, dupatta, dress
 * material) on top of whatever is already in the database. Every lookup uses
 * firstOrCreate/updateOrCreate so this is safe to run more than once and
 * won't touch existing rows, orders, or accounts.
 *
 * No real product photography exists yet, so each SKU gets a generated
 * placeholder image (solid color + label) instead of a broken/misleading
 * photo - swap these out via the admin panel once real photos are ready.
 */
class RealisticCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::where('slug', 'ansari-handloom')->first();

        // --- Attributes & values ---------------------------------------
        $colorAttr = Attribute::where('code', 'color')->firstOrFail();
        $patternAttr = Attribute::where('code', 'pattern')->firstOrFail();
        $fabricAttr = Attribute::where('code', 'fabric')->firstOrFail();

        $suitStyleAttr = Attribute::firstOrCreate(
            ['code' => 'suit_style'],
            ['name' => 'Suit Style', 'input_type' => 'dropdown', 'is_required' => false, 'is_searchable' => true, 'is_filterable' => true, 'is_variant' => false, 'sort_order' => 6, 'status' => 'active']
        );

        $lengthAttr = Attribute::firstOrCreate(
            ['code' => 'length_meters'],
            ['name' => 'Length (Meters)', 'input_type' => 'decimal', 'is_required' => false, 'is_searchable' => false, 'is_filterable' => false, 'is_variant' => false, 'sort_order' => 7, 'status' => 'active']
        );

        $colors = collect([
            'Crimson Red' => '#DC2626', 'Royal Blue' => '#2563EB', 'Forest Green' => '#15803D', 'Blush Pink' => '#EC4899',
            'Maroon' => '#7B1E3A', 'Mustard Yellow' => '#D4A017', 'Teal' => '#0F766E', 'Purple' => '#6D28D9', 'Orange' => '#EA580C',
        ])->map(fn ($hex, $name) => AttributeValue::firstOrCreate(
            ['attribute_id' => $colorAttr->id, 'value' => $name],
            ['color_code' => $hex, 'status' => 'active']
        ));

        $patterns = collect(['Bagh Print', 'Plain', 'Printed', 'Zari Woven', 'Embroidered', 'Floral Booti'])
            ->map(fn ($name) => AttributeValue::firstOrCreate(['attribute_id' => $patternAttr->id, 'value' => $name], ['status' => 'active']));

        $fabricValues = collect(['Pure Banarasi Silk', 'Organic Mul Cotton', 'Raw Silk', 'Kanjivaram Silk', 'Pure Silk', 'Chanderi Silk Cotton', 'Maheshwari Silk Cotton', 'Pure Georgette', 'Pure Chiffon', 'Cotton Rayon'])
            ->map(fn ($name) => AttributeValue::firstOrCreate(['attribute_id' => $fabricAttr->id, 'value' => $name], ['status' => 'active']));

        $suitStyleValues = collect(['Anarkali Suit', 'Punjabi / Patiala Suit', 'Straight Cut Suit'])
            ->map(fn ($name) => AttributeValue::firstOrCreate(['attribute_id' => $suitStyleAttr->id, 'value' => $name], ['status' => 'active']));

        $fabric = fn (string $name) => $fabricValues->firstWhere('value', $name);
        $pattern = fn (string $name) => $patterns->firstWhere('value', $name);
        $suitStyle = fn (string $name) => $suitStyleValues->firstWhere('value', $name);
        $color = fn (string $name) => $colors->get($name);

        // --- Attribute sets ----------------------------------------------
        $sareeSet = AttributeSet::where('name', 'Saree Attribute Set')->firstOrFail();
        $suitSet = AttributeSet::where('name', 'Suit Attribute Set')->firstOrFail();
        $suitSet->attributes()->syncWithoutDetaching([$suitStyleAttr->id]);

        $dupattaSet = AttributeSet::firstOrCreate(['name' => 'Dupatta Attribute Set'], ['status' => 'active']);
        $dupattaSet->attributes()->syncWithoutDetaching([$colorAttr->id, $patternAttr->id, $fabricAttr->id]);

        $dressMaterialSet = AttributeSet::firstOrCreate(['name' => 'Dress Material Attribute Set'], ['status' => 'active']);
        $dressMaterialSet->attributes()->syncWithoutDetaching([$colorAttr->id, $patternAttr->id, $fabricAttr->id, $lengthAttr->id]);

        // --- Categories (kept to two levels, per the data-structure doc) --
        $sarees = Category::where('slug', 'sarees')->firstOrFail();
        $suits = Category::where('slug', 'suits')->firstOrFail();
        $dupattaCat = Category::where('slug', 'dupattas')->firstOrFail();
        $dressMaterialCat = Category::where('slug', 'dress-materials')->firstOrFail();

        $silkSarees = Category::firstOrCreate(
            ['name' => 'Silk Sarees', 'parent_id' => $sarees->id],
            ['slug' => 'silk-sarees-' . Str::random(6), 'sort_order' => 1, 'status' => 'active']
        );
        $cottonSarees = Category::firstOrCreate(
            ['name' => 'Cotton Sarees', 'parent_id' => $sarees->id],
            ['slug' => 'cotton-sarees-' . Str::random(6), 'sort_order' => 2, 'status' => 'active']
        );
        $readyToWearSuits = Category::firstOrCreate(
            ['name' => 'Ready-to-wear Suits', 'parent_id' => $suits->id],
            ['slug' => 'ready-to-wear-suits-' . Str::random(6), 'sort_order' => 1, 'status' => 'active']
        );

        // --- Catalog spread ------------------------------------------------
        $catalog = [
            // Sarees
            ['name' => 'Kanjivaram Silk Saree - Temple Border', 'category' => $silkSarees, 'set' => $sareeSet, 'fabric' => 'Kanjivaram Silk', 'pattern' => 'Zari Woven', 'desc' => 'A rich Kanjivaram silk saree with a traditional temple-motif border, handwoven by South Indian master weavers using pure mulberry silk and zari thread.', 'price' => 8500, 'skus' => ['Maroon', 'Forest Green']],
            ['name' => 'Handloom Cotton Saree - Daily Weave', 'category' => $cottonSarees, 'set' => $sareeSet, 'fabric' => 'Organic Mul Cotton', 'pattern' => 'Printed', 'desc' => 'A lightweight, breathable handloom cotton saree woven for everyday comfort, finished with a simple printed border - ideal for office and daily wear.', 'price' => 1450, 'skus' => ['Blush Pink', 'Mustard Yellow']],
            ['name' => 'Pure Silk Saree - Contemporary Weave', 'category' => $silkSarees, 'set' => $sareeSet, 'fabric' => 'Pure Silk', 'pattern' => 'Plain', 'desc' => 'A minimal, contemporary pure silk saree with a soft drape and understated sheen, styled for festive evenings without heavy embellishment.', 'price' => 4200, 'skus' => ['Teal', 'Purple']],
            ['name' => 'Chanderi Silk Cotton Saree - Floral Booti', 'category' => $silkSarees, 'set' => $sareeSet, 'fabric' => 'Chanderi Silk Cotton', 'pattern' => 'Floral Booti', 'desc' => 'A Chanderi silk-cotton blend saree from Madhya Pradesh, known for its sheer texture and delicate hand-woven floral booti motifs.', 'price' => 3800, 'skus' => ['Royal Blue', 'Blush Pink']],
            ['name' => 'Maheshwari Silk Cotton Saree - Zari Border', 'category' => $silkSarees, 'set' => $sareeSet, 'fabric' => 'Maheshwari Silk Cotton', 'pattern' => 'Zari Woven', 'desc' => 'A Maheshwari silk-cotton saree woven on traditional pit looms, featuring the region\'s signature reversible zari border and checkered body.', 'price' => 3600, 'skus' => ['Mustard Yellow', 'Orange']],

            // Suits
            ['name' => 'Anarkali Suit Set - Georgette Embroidered', 'category' => $readyToWearSuits, 'set' => $suitSet, 'fabric' => 'Pure Georgette', 'pattern' => 'Embroidered', 'suit_style' => 'Anarkali Suit', 'desc' => 'A flowing floor-length Anarkali suit set in embroidered georgette with a flared silhouette, paired with a matching dupatta - built for festive and wedding-season wear.', 'price' => 3200, 'skus' => ['Maroon', 'Teal']],
            ['name' => 'Punjabi Patiala Suit Set - Cotton Printed', 'category' => $readyToWearSuits, 'set' => $suitSet, 'fabric' => 'Organic Mul Cotton', 'pattern' => 'Printed', 'suit_style' => 'Punjabi / Patiala Suit', 'desc' => 'A classic Punjabi Patiala suit set with pleated salwar and printed cotton kurta, comfortable for daily wear and casual outings.', 'price' => 1850, 'skus' => ['Royal Blue', 'Forest Green']],
            ['name' => 'Straight Cut Suit Set - Rayon Solid', 'category' => $readyToWearSuits, 'set' => $suitSet, 'fabric' => 'Cotton Rayon', 'pattern' => 'Plain', 'suit_style' => 'Straight Cut Suit', 'desc' => 'A tailored straight-cut suit set in solid rayon fabric with a clean silhouette, suited for office wear and everyday styling.', 'price' => 1650, 'skus' => ['Purple', 'Crimson Red']],

            // Dupatta
            ['name' => 'Chiffon Dupatta - Zari Border', 'category' => $dupattaCat, 'set' => $dupattaSet, 'fabric' => 'Pure Chiffon', 'pattern' => 'Zari Woven', 'desc' => 'A sheer chiffon dupatta finished with a delicate zari border, light enough to pair with both sarees and suits.', 'price' => 650, 'skus' => ['Blush Pink', 'Mustard Yellow']],
            ['name' => 'Cotton Printed Dupatta - Bagh Print', 'category' => $dupattaCat, 'set' => $dupattaSet, 'fabric' => 'Organic Mul Cotton', 'pattern' => 'Bagh Print', 'desc' => 'A breathable cotton dupatta hand block-printed in a traditional Bagh pattern, ideal for everyday layering.', 'price' => 450, 'skus' => ['Forest Green', 'Orange']],

            // Dress Material
            ['name' => 'Cotton Dress Material Set - Unstitched', 'category' => $dressMaterialCat, 'set' => $dressMaterialSet, 'fabric' => 'Organic Mul Cotton', 'pattern' => 'Printed', 'length' => '2.5', 'desc' => 'An unstitched 3-piece cotton dress material set (top, bottom, dupatta) with a printed finish, ready for custom tailoring.', 'price' => 1100, 'skus' => ['Teal', 'Crimson Red']],
            ['name' => 'Georgette Embroidered Dress Material Set', 'category' => $dressMaterialCat, 'set' => $dressMaterialSet, 'fabric' => 'Pure Georgette', 'pattern' => 'Embroidered', 'length' => '3.0', 'desc' => 'An unstitched georgette dress material set with embroidered panels, suited for festive tailoring.', 'price' => 1950, 'skus' => ['Royal Blue', 'Purple']],
        ];

        foreach ($catalog as $item) {
            $slug = Str::slug($item['name']);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'category_id' => $item['category']->id,
                    'brand_id' => $brand?->id,
                    'attribute_set_id' => $item['set']->id,
                    'description' => $item['desc'],
                    'short_description' => Str::limit($item['desc'], 90),
                    'status' => 'active',
                ]
            );

            if ($fabricVal = $fabric($item['fabric'])) {
                ProductAttributeValue::firstOrCreate(['product_id' => $product->id, 'attribute_value_id' => $fabricVal->id]);
            }
            if (isset($item['suit_style']) && $styleVal = $suitStyle($item['suit_style'])) {
                ProductAttributeValue::firstOrCreate(['product_id' => $product->id, 'attribute_value_id' => $styleVal->id]);
            }
            if (isset($item['length'])) {
                ProductAttributeValue::firstOrCreate(['product_id' => $product->id, 'text_value' => $item['length']], ['text_value' => $item['length']]);
            }

            $skuPrefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $item['name']), 0, 10));

            foreach ($item['skus'] as $i => $colorName) {
                $colorVal = $color($colorName);
                $patternVal = $pattern($item['pattern']);
                $skuCode = $skuPrefix . '-' . strtoupper(Str::slug($colorName, ''));

                $sku = ProductSku::firstOrCreate(
                    ['sku_code' => $skuCode],
                    [
                        'product_id' => $product->id,
                        'selling_price' => $item['price'] + ($i * 150),
                        'mrp' => round(($item['price'] + ($i * 150)) * 1.3),
                        'cost_price' => round($item['price'] * 0.55),
                        'stock' => 10 + ($i * 4),
                        'low_stock_alert' => 3,
                        'status' => 'active',
                        'is_default' => $i === 0,
                    ]
                );

                $attrIds = array_filter([$colorVal?->id, $patternVal?->id]);
                if ($attrIds) {
                    $sku->attributeValues()->syncWithoutDetaching($attrIds);
                }

                if ($sku->images()->count() === 0) {
                    $filename = $this->makePlaceholderImage($item['name'], $colorName, $colorVal?->color_code ?? '#5B1123');
                    ProductImage::create([
                        'sku_id' => $sku->id,
                        'image_path' => $filename,
                        'title' => $item['name'] . ' - ' . $colorName,
                        'alt_text' => $item['name'] . ' in ' . $colorName,
                        'is_primary' => true,
                    ]);
                }
            }
        }
    }

    /**
     * Generates a simple labeled placeholder photo so the catalog has a
     * visual thumbnail per SKU instead of a broken image. Filenames are
     * prefixed "seed_" so they're easy to spot and replace later.
     */
    private function makePlaceholderImage(string $productName, string $colorName, string $hex): string
    {
        $width = 800;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);

        [$r, $g, $b] = sscanf($hex, "#%02x%02x%02x");
        $bg = imagecolorallocate($image, (int) $r, (int) $g, (int) $b);
        imagefill($image, 0, 0, $bg);

        $white = imagecolorallocate($image, 255, 255, 255);
        $overlay = imagecolorallocatealpha($image, 0, 0, 0, 60);
        imagefilledrectangle($image, 0, $height - 160, $width, $height, $overlay);

        $lines = explode("\n", wordwrap($productName, 28, "\n"));
        $y = $height - 140;
        foreach ($lines as $line) {
            imagestring($image, 5, 20, $y, $line, $white);
            $y += 20;
        }
        imagestring($image, 5, 20, $y + 10, 'Color: ' . $colorName, $white);
        imagestring($image, 3, 20, 20, 'ANSARI HANDLOOM - PLACEHOLDER PHOTO', $white);

        $filename = 'seed_' . Str::random(10) . '.jpg';
        imagejpeg($image, public_path('images/' . $filename), 85);
        imagedestroy($image);

        return $filename;
    }
}
