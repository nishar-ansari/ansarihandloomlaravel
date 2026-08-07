<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Data model correction: per Ansari_Handloom_Data_Structure.docx section 6,
     * images belong to the SKU (the actual sellable color/variant), never to the
     * Product (the shared design). This migration adds sku_id to product_images,
     * backfills it from the existing product_id + image_attribute_values tagging,
     * then drops the now-redundant product_id column and tagging pivot table.
     *
     * It also adds product_skus.is_default so storefront listings and the admin
     * "cover" image have a single, explicit SKU to represent the product.
     */
    public function up(): void
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->after('status');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->foreignId('sku_id')->nullable()->after('product_id')
                ->constrained('product_skus')->onDelete('cascade');
        });

        // --- Backfill is_default: one SKU per product, prefer the lowest id ---
        $products = DB::table('products')->pluck('id');
        foreach ($products as $productId) {
            $firstSkuId = DB::table('product_skus')
                ->where('product_id', $productId)
                ->orderBy('id')
                ->value('id');

            if ($firstSkuId) {
                DB::table('product_skus')->where('id', $firstSkuId)->update(['is_default' => true]);
            }
        }

        // --- Backfill product_images.sku_id from existing tagging/fallback ---
        $images = DB::table('product_images')->get();
        foreach ($images as $image) {
            $skusForProduct = DB::table('product_skus')
                ->where('product_id', $image->product_id)
                ->pluck('id');

            $targetSkuId = null;

            if ($skusForProduct->count() === 1) {
                $targetSkuId = $skusForProduct->first();
            } elseif ($skusForProduct->count() > 1) {
                $tagIds = DB::table('image_attribute_values')
                    ->where('product_image_id', $image->id)
                    ->pluck('attribute_value_id');

                if ($tagIds->isNotEmpty()) {
                    foreach ($skusForProduct as $skuId) {
                        $skuValueIds = DB::table('sku_attribute_values')
                            ->where('sku_id', $skuId)
                            ->pluck('attribute_value_id');

                        if ($tagIds->diff($skuValueIds)->isEmpty()) {
                            $targetSkuId = $skuId;
                            break;
                        }
                    }
                }

                // No tag match (or untagged image) - fall back to the default SKU
                if (!$targetSkuId) {
                    $targetSkuId = DB::table('product_skus')
                        ->where('product_id', $image->product_id)
                        ->where('is_default', true)
                        ->value('id') ?? $skusForProduct->first();
                }
            }

            if ($targetSkuId) {
                DB::table('product_images')->where('id', $image->id)->update(['sku_id' => $targetSkuId]);
            } else {
                // Orphaned image (product has no SKU at all) - safe to discard
                DB::table('product_images')->where('id', $image->id)->delete();
            }
        }

        // Guarantee every SKU that has photos has exactly one marked primary -
        // the old product-level is_primary flags don't map 1:1 onto the new
        // per-SKU grouping (e.g. a non-red variant could end up with zero
        // primary photos if only the red product photo was ever flagged).
        $skuIds = DB::table('product_images')->whereNotNull('sku_id')->distinct()->pluck('sku_id');
        foreach ($skuIds as $skuId) {
            $hasPrimary = DB::table('product_images')->where('sku_id', $skuId)->where('is_primary', true)->exists();
            if (!$hasPrimary) {
                $firstImageId = DB::table('product_images')->where('sku_id', $skuId)->orderBy('sort_order')->value('id');
                if ($firstImageId) {
                    DB::table('product_images')->where('id', $firstImageId)->update(['is_primary' => true]);
                }
            }
        }

        Schema::dropIfExists('image_attribute_values');

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->unsignedBigInteger('sku_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('image_attribute_values', function (Blueprint $table) {
            $table->foreignId('product_image_id')->constrained('product_images')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            $table->primary(['product_image_id', 'attribute_value_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('id')->constrained('products')->onDelete('cascade');
        });

        $images = DB::table('product_images')->get();
        foreach ($images as $image) {
            $productId = DB::table('product_skus')->where('id', $image->sku_id)->value('product_id');
            if ($productId) {
                DB::table('product_images')->where('id', $image->id)->update(['product_id' => $productId]);
            }
        }

        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');
        });

        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
