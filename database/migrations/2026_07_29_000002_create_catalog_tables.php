<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('image', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('logo', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 3. Attribute Sets
        Schema::create('attribute_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 4. Attributes
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->enum('input_type', [
                'text', 'textarea', 'number', 'decimal', 'dropdown', 
                'multiselect', 'checkbox', 'radio', 'switch', 'date', 
                'time', 'color_picker', 'file'
            ])->default('dropdown');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_variant')->default(false);
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 5. Attribute Values
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->string('value', 255);
            $table->string('color_code', 20)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 6. Attribute Set Attributes (Pivot)
        Schema::create('attribute_set_attributes', function (Blueprint $table) {
            $table->foreignId('attribute_set_id')->constrained('attribute_sets')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->primary(['attribute_set_id', 'attribute_id']);
        });

        // 7. Products (Base Styles)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('attribute_set_id')->constrained('attribute_sets')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('tags')->nullable(); // comma-separated
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
        });

        // 8. Product Attribute Values (Global non-variant values)
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->nullable()->constrained('attribute_values')->onDelete('cascade');
            $table->text('text_value')->nullable();
            $table->timestamps();
        });

        // 9. Product SKUs (Sellable Variants)
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku_code', 100)->unique();
            $table->string('barcode', 100)->nullable()->unique();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(5);
            $table->decimal('weight', 8, 2)->nullable(); // grams
            $table->decimal('length', 6, 2)->nullable();
            $table->decimal('width', 6, 2)->nullable();
            $table->decimal('height', 6, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 10. SKU Attribute Values (Pivot mapping variant parameters)
        Schema::create('sku_attribute_values', function (Blueprint $table) {
            $table->foreignId('sku_id')->constrained('product_skus')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            $table->primary(['sku_id', 'attribute_value_id']);
        });

        // 11. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('image_path', 255);
            $table->string('title', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // 12. Image Attribute Values (Multi-dimensional variant filtering)
        Schema::create('image_attribute_values', function (Blueprint $table) {
            $table->foreignId('product_image_id')->constrained('product_images')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            $table->primary(['product_image_id', 'attribute_value_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_attribute_values');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('sku_attribute_values');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('products');
        Schema::dropIfExists('attribute_set_attributes');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_sets');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
