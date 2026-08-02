@extends('layouts.app')

@section('title', $product->name . ' - Ansari Handloom')

@section('content')
@php
    // Group variant attributes (is_variant = 1)
    $variantOptions = [];
    $variantAttributes = $product->attributeSet->attributes->where('is_variant', 1)->sortBy('sort_order');
    
    // Only collect options that actually exist in the product's SKUs
    foreach ($variantAttributes as $attr) {
        $options = [];
        foreach ($product->skus as $sku) {
            foreach ($sku->attributeValues as $val) {
                if ($val->attribute_id == $attr->id) {
                    $options[$val->id] = $val->value;
                }
            }
        }
        if (!empty($options)) {
            $variantOptions[$attr->name] = [
                'id' => $attr->id,
                'name' => $attr->name,
                'options' => $options
            ];
        }
    }

    // Group global product attributes (is_variant = 0)
    $globalAttributes = $product->attributeSet->attributes->where('is_variant', 0)->sortBy('sort_order');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="row g-5">
        <!-- Image Gallery -->
        <div class="col-md-6 space-y-4">
            <div class="border border-luxury-gold/10 rounded overflow-hidden shadow-sm bg-white aspect-[4/3] relative">
                <img src="{{ $product->primaryImage ? asset('images/' . $product->primaryImage->image_path) : asset('images/saree_red.jpg') }}" 
                     id="main-product-image" 
                     class="w-full h-full object-cover transition duration-300" 
                     alt="{{ $product->name }}">
            </div>
            
            <!-- Thumbnail Carousel -->
            <div class="flex flex-wrap gap-4" id="thumbnails-container">
                <!-- Populated dynamically via JS -->
            </div>
        </div>

        <!-- Product Details Panel -->
        <div class="col-md-6 space-y-6">
            <div>
                <span class="text-xs uppercase tracking-widest text-luxury-gold font-bold block mb-1">
                    {{ $product->brand ? $product->brand->name : 'Handwoven' }}
                </span>
                <h1 class="text-3xl font-serif font-bold text-luxury-maroon">{{ $product->name }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="text-xs bg-luxury-beige/50 text-luxury-maroon px-3 py-1 rounded-full font-semibold">
                        {{ $product->category->name }}
                    </span>
                </div>
            </div>

            <!-- Price Display Area -->
            <div class="border-t border-b border-luxury-gold/10 py-4 space-y-1">
                <div class="flex items-baseline space-x-3">
                    <span class="text-3xl font-bold text-luxury-maroon" id="sku-price">₹{{ number_format($product->base_price, 2) }}</span>
                    <span class="text-sm line-through text-gray-400 font-semibold" id="sku-mrp"></span>
                    <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded border border-red-100 font-bold" id="sku-discount"></span>
                </div>
                <span class="text-xxs text-gray-400 font-semibold block">(Inclusive of all taxes & GST)</span>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-luxury-gold font-serif mb-2">Artisan's Description</h3>
                <p class="text-sm text-luxury-charcoal/70 leading-relaxed text-justify">{{ $product->description }}</p>
            </div>

            <!-- Variant Selector Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_sku_id" id="hidden-sku-id" value="">

                <!-- Variant Attribute Selection Groups -->
                @if(!empty($variantOptions))
                    <div class="space-y-4 border-t border-b border-luxury-gold/10 py-4" id="variant-selectors-container">
                        @foreach($variantOptions as $attrName => $data)
                            <div class="attr-group" data-attr-id="{{ $data['id'] }}" data-attr-name="{{ $attrName }}">
                                <h4 class="text-xs uppercase font-bold text-luxury-gold font-serif tracking-wider mb-2">Select {{ $attrName }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    @php $isFirst = true; @endphp
                                    @foreach($data['options'] as $valId => $valText)
                                        <label class="cursor-pointer attr-val-label" data-val-id="{{ $valId }}">
                                            <input type="radio" name="attr_{{ $data['id'] }}" value="{{ $valId }}" 
                                                   class="hidden attr-option-radio" 
                                                   {{ $isFirst ? 'checked' : '' }}>
                                            <span class="option-pill px-4 py-2 border rounded-full text-xs font-semibold bg-white text-luxury-charcoal border-gray-200 hover:border-luxury-gold transition block select-none">
                                                {{ $valText }}
                                            </span>
                                        </label>
                                        @php $isFirst = false; @endphp
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Stock and Specs Card -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Stock Availability:</span>
                        <strong id="sku-stock-status" class="text-success">In Stock</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Barcode / EAN:</span>
                        <span id="sku-barcode-text" class="font-mono text-luxury-charcoal">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Package Weight:</span>
                        <span id="sku-weight-text" class="text-luxury-charcoal">-</span>
                    </div>
                </div>

                <!-- Quantity and Button -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center border border-luxury-gold/30 rounded-full bg-white overflow-hidden w-32 shadow-sm">
                        <button type="button" class="px-3 py-2 text-luxury-maroon hover:bg-luxury-beige transition font-bold" id="qty-desc">-</button>
                        <input type="number" name="quantity" id="quantity-input" value="1" min="1" max="10" 
                               class="w-full text-center outline-none border-none font-bold text-sm bg-transparent">
                        <button type="button" class="px-3 py-2 text-luxury-maroon hover:bg-luxury-beige transition font-bold" id="qty-incr">+</button>
                    </div>
                    
                    <button type="submit" id="add-to-bag-btn" class="flex-grow bg-luxury-maroon hover:bg-luxury-maroonlight text-luxury-cream font-bold py-3.5 px-8 rounded-full uppercase tracking-wider text-sm transition shadow shadow-lg flex items-center justify-center space-x-2">
                        <i class="bi bi-cart-plus-fill"></i>
                        <span>Add to Shopping Bag</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Specifications & Global non-variant Attributes Details -->
    @if($globalAttributes->isNotEmpty())
        <div class="mt-12 border-t border-luxury-gold/10 pt-8">
            <h3 class="text-lg font-serif font-bold text-luxury-maroon mb-4"><i class="bi bi-gear-fill mr-2"></i> Product Specifications</h3>
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-6 max-w-2xl">
                <table class="table table-borderless text-sm mb-0">
                    <tbody>
                        @foreach($globalAttributes as $attr)
                            @php
                                // Fetch set value for this product
                                $prodAttrValue = $product->globalAttributeValues->first(function($pav) use ($attr) {
                                    return $pav->attributeValue && $pav->attributeValue->attribute_id == $attr->id;
                                });
                                $textValue = $product->globalAttributeValues->first(function($pav) use ($attr) {
                                    return !$pav->attributeValue && $pav->text_value !== null;
                                });
                                
                                $displayVal = '';
                                if ($prodAttrValue) {
                                    $displayVal = $prodAttrValue->attributeValue->value;
                                } elseif ($textValue) {
                                    $displayVal = $textValue->text_value;
                                }
                            @endphp
                            @if(!empty($displayVal))
                                <tr class="border-b border-gray-100">
                                    <td class="w-1/3 text-gray-400 font-semibold py-2.5">{{ $attr->name }}</td>
                                    <td class="text-luxury-charcoal py-2.5">{{ $displayVal }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Reviews and Related Products Section -->
    <div class="mt-16 border-t border-luxury-gold/10 pt-12 space-y-12">
        <div class="row g-5">
            <!-- Approved Reviews List -->
            <div class="col-lg-7 space-y-6">
                <h3 class="text-lg font-bold font-serif text-luxury-maroon"><i class="bi bi-chat-left-text-fill mr-2"></i> Patron Reviews ({{ $reviews->count() }})</h3>
                
                @forelse($reviews as $rev)
                    <div class="border border-luxury-gold/5 rounded-lg p-4 bg-luxury-beige/10 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <div>
                                <strong class="text-luxury-maroon block font-serif">{{ $rev->customer->name }}</strong>
                                <span class="text-[10px] text-gray-400 font-semibold">{{ $rev->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center text-luxury-gold font-bold">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $rev->rating ? 'text-luxury-gold' : 'text-gray-200' }} mr-0.5"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-luxury-charcoal/80 mb-0 italic font-serif">"{{ $rev->review_text }}"</p>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-xs">No reviews for this creation yet. Be the first to share your thoughts!</div>
                @endforelse
            </div>

            <!-- Review Form -->
            <div class="col-lg-5">
                <div class="bg-white border border-luxury-gold/15 rounded-lg p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold font-serif uppercase tracking-wider text-luxury-maroon border-b border-gray-100 pb-2"><i class="bi bi-pen mr-1"></i> Write a Review</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success text-xs rounded border border-green-300 py-2.5 px-4 bg-green-50 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth('customer')
                        <form action="{{ route('products.review.submit', $product->id) }}" method="POST" class="space-y-3 text-xs">
                            @csrf
                            <div>
                                <label class="font-semibold block mb-1">Rating *</label>
                                <select name="rating" required class="w-full border rounded px-3 py-2 text-xs outline-none bg-white">
                                    <option value="5">5 Stars (Excellent)</option>
                                    <option value="4">4 Stars (Good)</option>
                                    <option value="3">3 Stars (Average)</option>
                                    <option value="2">2 Stars (Poor)</option>
                                    <option value="1">1 Star (Very Poor)</option>
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold block mb-1">Review Comments</label>
                                <textarea name="review_text" rows="3" placeholder="Share your experience with this weave..." class="w-full border rounded px-3 py-2 outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-luxury-maroon text-white font-bold py-2 rounded uppercase tracking-wider text-[10px] hover:bg-luxury-maroonlight transition">
                                Submit Review
                            </button>
                        </form>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs text-gray-500 mb-3">Please log in to share review feedback for this product.</p>
                            <a href="{{ route('login') }}" class="bg-luxury-gold text-luxury-charcoal font-bold px-6 py-2 rounded-full uppercase tracking-wider text-[10px] no-underline hover:bg-yellow-500 transition inline-block">Log In Now</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Related Products block -->
        <div class="space-y-6 pt-12 border-t border-luxury-gold/10">
            <h3 class="text-lg font-bold font-serif text-luxury-maroon text-center"><i class="bi bi-gift mr-2"></i> You May Also Like</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedProducts as $rel)
                    <div class="bg-white rounded overflow-hidden shadow group flex flex-col h-full border border-luxury-gold/5 transition hover:shadow-xl hover:translate-y-[-4px] duration-300">
                        <!-- Image -->
                        <div class="aspect-[3/4] relative bg-luxury-beige/10 overflow-hidden">
                            <img src="{{ $rel->primaryImage ? asset('images/' . $rel->primaryImage->image_path) : asset('images/saree_red.jpg') }}" 
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                 alt="{{ $rel->name }}">
                        </div>
                        <!-- Details -->
                        <div class="p-4 flex-grow flex flex-col justify-between space-y-2">
                            <div>
                                <span class="text-[10px] text-luxury-gold font-semibold uppercase tracking-wider block">{{ $rel->brand->name ?? 'Handloom' }}</span>
                                <h4 class="text-xs font-serif font-bold text-luxury-maroon mt-1 line-clamp-1">
                                    <a href="{{ route('products.show', $rel->slug) }}" class="no-underline text-inherit hover:text-luxury-gold transition">{{ $rel->name }}</a>
                                </h4>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-luxury-gold/5">
                                <span class="text-xs font-bold text-luxury-charcoal">₹{{ number_format($rel->base_price, 2) }}</span>
                                <a href="{{ route('products.show', $rel->slug) }}" class="text-[10px] font-semibold text-luxury-gold hover:text-luxury-maroon no-underline">View Details &rarr;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-6 text-gray-400 text-xs">No similar creations currently featured.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Pass raw database SKUs details to JS
    const skuList = @json($product->skus->map(function($sku) {
        return [
            'id' => $sku->id,
            'sku_code' => $sku->sku_code,
            'selling_price' => $sku->selling_price,
            'mrp' => $sku->mrp,
            'stock' => $sku->stock,
            'barcode' => $sku->barcode ?? 'N/A',
            'weight' => $sku->weight ? $sku->weight . 'g' : 'N/A',
            'attributes' => $sku->attributeValues->pluck('id')->toArray(),
        ];
    }));

    // Pass images details with their dynamic attribute values mapping
    const imageList = @json($product->images->map(function($img) {
        return [
            'id' => $img->id,
            'image_url' => asset('images/' . $img->image_path),
            'attributes' => $img->attributeValues->pluck('id')->toArray(),
        ];
    }));

    $(document).ready(function() {
        // Thumbnail switcher
        $(document).on('click', '.thumbnail-btn', function() {
            var url = $(this).data('image-url');
            $('#main-product-image').attr('src', url);
            $('.thumbnail-btn').removeClass('border-luxury-gold').addClass('border-luxury-gold/20');
            $(this).removeClass('border-luxury-gold/20').addClass('border-luxury-gold');
        });

        // Function to filter active combinations and matching images
        function updateStorefrontState() {
            let selectedVals = [];
            let selectedGroupVals = {};

            // 1. Collect currently selected option IDs
            $('.attr-option-radio:checked').each(function() {
                let attrGroupId = $(this).closest('.attr-group').data('attr-id');
                let valId = parseInt($(this).val());
                selectedVals.push(valId);
                selectedGroupVals[attrGroupId] = valId;
                
                // Style option spans
                $(this).closest('.attr-group').find('span').removeClass('border-luxury-maroon bg-luxury-maroon text-white').addClass('bg-white text-luxury-charcoal border-gray-200');
                $(this).next('span').addClass('border-luxury-maroon bg-luxury-maroon text-white').removeClass('bg-white text-luxury-charcoal border-gray-200');
            });

            // 2. Disable invalid combinations (prevent impossible variations)
            $('.attr-group').each(function() {
                let currentGroupId = $(this).data('attr-id');
                $(this).find('.attr-val-label').each(function() {
                    let valId = parseInt($(this).data('val-id'));
                    
                    // Construct temporary test set: replace selection of this group with this valId
                    let testVals = [];
                    for (let gId in selectedGroupVals) {
                        if (gId == currentGroupId) {
                            testVals.push(valId);
                        } else {
                            testVals.push(selectedGroupVals[gId]);
                        }
                    }

                    // Check if any active SKU contains all testVals
                    let isCombinationValid = skuList.some(sku => {
                        return testVals.every(id => sku.attributes.includes(id));
                    });

                    if (isCombinationValid) {
                        $(this).removeClass('opacity-30 pointer-events-none');
                        $(this).find('input').prop('disabled', false);
                    } else {
                        $(this).addClass('opacity-30 pointer-events-none');
                        $(this).find('input').prop('disabled', true);
                    }
                });
            });

            // 3. Find matched SKU
            let matchedSku = skuList.find(sku => {
                return selectedVals.every(valId => sku.attributes.includes(valId));
            });

            if (matchedSku) {
                $('#hidden-sku-id').val(matchedSku.id);

                // Update prices
                $('#sku-price').text('₹' + Number(matchedSku.selling_price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                if (matchedSku.mrp && Number(matchedSku.mrp) > Number(matchedSku.selling_price)) {
                    $('#sku-mrp').text('₹' + Number(matchedSku.mrp).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})).removeClass('hidden');
                    let discount = Math.round(((matchedSku.mrp - matchedSku.selling_price) / matchedSku.mrp) * 100);
                    $('#sku-discount').text(discount + '% OFF').removeClass('hidden');
                } else {
                    $('#sku-mrp').addClass('hidden');
                    $('#sku-discount').addClass('hidden');
                }

                // Update stock status
                if (matchedSku.stock > 0) {
                    $('#sku-stock-status').text('In Stock (' + matchedSku.stock + ' units remaining)').removeClass('text-danger').addClass('text-success');
                    $('#add-to-bag-btn').prop('disabled', false).removeClass('bg-gray-400 cursor-not-allowed').addClass('bg-luxury-maroon hover:bg-luxury-maroonlight');
                    $('#quantity-input').attr('max', matchedSku.stock);
                } else {
                    $('#sku-stock-status').text('Out of Stock').removeClass('text-success').addClass('text-danger');
                    $('#add-to-bag-btn').prop('disabled', true).addClass('bg-gray-400 cursor-not-allowed').removeClass('bg-luxury-maroon hover:bg-luxury-maroonlight');
                    $('#quantity-input').attr('max', 0).val(0);
                }

                // Update specs card
                $('#sku-barcode-text').text(matchedSku.barcode);
                $('#sku-weight-text').text(matchedSku.weight);
            } else {
                $('#add-to-bag-btn').prop('disabled', true).addClass('bg-gray-400 cursor-not-allowed');
                $('#sku-stock-status').text('Unavailable Configuration').addClass('text-danger');
            }

            // 4. Multi-dimensional Image Filtering
            // Display only images with no mapped attribute values OR where all mapped attribute values are in selectedVals
            let visibleImages = imageList.filter(img => {
                return img.attributes.length === 0 || img.attributes.every(id => selectedVals.includes(id));
            });

            // Rebuild thumbnails container
            let container = $('#thumbnails-container');
            container.empty();
            if (visibleImages.length > 1) {
                visibleImages.forEach((img, index) => {
                    let activeClass = index === 0 ? 'border-luxury-gold' : 'border-luxury-gold/20';
                    let btn = `
                        <button class="w-24 aspect-[4/3] border ${activeClass} rounded overflow-hidden bg-white hover:border-luxury-gold transition shadow-sm p-0 thumbnail-btn" 
                                data-image-url="${img.image_url}">
                            <img src="${img.image_url}" class="w-full h-full object-cover" alt="Thumbnail">
                        </button>
                    `;
                    container.append(btn);
                });
            }

            // Trigger main image load
            if (visibleImages.length > 0) {
                $('#main-product-image').attr('src', visibleImages[0].image_url);
            }
        }

        // Handle option changes
        $(document).on('change', '.attr-option-radio', function() {
            updateStorefrontState();
        });

        // Initialize state
        updateStorefrontState();

        // Quantity controls
        $('#qty-desc').on('click', function() {
            var val = parseInt($('#quantity-input').val());
            if (val > 1) {
                $('#quantity-input').val(val - 1);
            }
        });

        $('#qty-incr').on('click', function() {
            var val = parseInt($('#quantity-input').val());
            var max = parseInt($('#quantity-input').attr('max')) || 10;
            if (val < max) {
                $('#quantity-input').val(val + 1);
            }
        });
    });
</script>
@endsection
