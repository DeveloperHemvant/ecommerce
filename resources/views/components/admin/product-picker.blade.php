@props(['selected' => collect(), 'name' => 'product_ids'])

<div id="product-picker" data-name="{{ $name }}">
    <div class="relative">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">search</span>
        <input type="text" id="product-picker-search" autocomplete="off"
            placeholder="Search products by name or SKU to tag..."
            class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-10 pr-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors" />

        <div id="product-picker-results"
            class="hidden absolute left-0 right-0 top-full mt-2 bg-surface-container-lowest border border-border-subtle rounded-2xl shadow-lg z-20 max-h-80 overflow-y-auto divide-y divide-border-subtle"></div>
    </div>

    <p class="text-[11px] text-on-surface-variant mt-2" id="product-picker-count">
        {{ $selected->count() }} {{ Str::plural('product', $selected->count()) }} tagged
    </p>

    <div id="product-picker-chips" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 mt-3">
        @foreach($selected as $product)
            <div class="product-picker-chip p-3 bg-warm-ivory/50 border border-border-subtle rounded-xl flex items-center gap-3" data-id="{{ $product->id }}">
                <input type="hidden" name="{{ $name }}[]" value="{{ $product->id }}" />
                <div class="w-10 h-12 rounded overflow-hidden bg-surface shrink-0 border border-border-subtle">
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-title-lg text-xs font-semibold text-charcoal-text truncate">{{ $product->name }}</p>
                    <p class="font-data-tabular text-[11px] text-heritage-burgundy font-bold">{{ $product->formatted_price }}</p>
                    <span class="font-data-tabular text-[10px] text-on-surface-variant">{{ $product->sku }}</span>
                </div>
                <button type="button" class="product-picker-remove text-on-surface-variant hover:text-error transition-colors shrink-0" title="Remove">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        @endforeach
    </div>
</div>

<script>
    (function () {
        var root = document.getElementById('product-picker');
        if (!root) return;

        var fieldName = root.dataset.name;
        var input = document.getElementById('product-picker-search');
        var results = document.getElementById('product-picker-results');
        var chips = document.getElementById('product-picker-chips');
        var count = document.getElementById('product-picker-count');
        var debounceTimer = null;

        function selectedIds() {
            return Array.from(chips.querySelectorAll('.product-picker-chip')).map(function (el) { return el.dataset.id; });
        }

        function updateCount() {
            var n = chips.querySelectorAll('.product-picker-chip').length;
            count.textContent = n + ' product' + (n === 1 ? '' : 's') + ' tagged';
        }

        function addChip(product) {
            if (selectedIds().indexOf(String(product.id)) !== -1) return;

            var chip = document.createElement('div');
            chip.className = 'product-picker-chip p-3 bg-warm-ivory/50 border border-border-subtle rounded-xl flex items-center gap-3';
            chip.dataset.id = product.id;

            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = fieldName + '[]';
            hidden.value = product.id;

            var imgWrap = document.createElement('div');
            imgWrap.className = 'w-10 h-12 rounded overflow-hidden bg-surface shrink-0 border border-border-subtle';
            var img = document.createElement('img');
            img.src = product.image;
            img.className = 'w-full h-full object-cover';
            imgWrap.appendChild(img);

            var textWrap = document.createElement('div');
            textWrap.className = 'min-w-0 flex-1';

            var nameEl = document.createElement('p');
            nameEl.className = 'font-title-lg text-xs font-semibold text-charcoal-text truncate';
            nameEl.textContent = product.name;

            var priceEl = document.createElement('p');
            priceEl.className = 'font-data-tabular text-[11px] text-heritage-burgundy font-bold';
            priceEl.textContent = product.price;

            var skuEl = document.createElement('span');
            skuEl.className = 'font-data-tabular text-[10px] text-on-surface-variant';
            skuEl.textContent = product.sku;

            textWrap.appendChild(nameEl);
            textWrap.appendChild(priceEl);
            textWrap.appendChild(skuEl);

            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'product-picker-remove text-on-surface-variant hover:text-error transition-colors shrink-0';
            removeBtn.title = 'Remove';
            removeBtn.innerHTML = '<span class="material-symbols-outlined text-lg">close</span>';

            chip.appendChild(hidden);
            chip.appendChild(imgWrap);
            chip.appendChild(textWrap);
            chip.appendChild(removeBtn);
            chips.appendChild(chip);
            updateCount();
        }

        chips.addEventListener('click', function (event) {
            var btn = event.target.closest('.product-picker-remove');
            if (!btn) return;
            btn.closest('.product-picker-chip').remove();
            updateCount();
        });

        input.addEventListener('input', function () {
            var term = input.value.trim();
            clearTimeout(debounceTimer);

            if (term.length < 2) {
                results.classList.add('hidden');
                results.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(function () {
                fetch("{{ route('admin.products.search') }}?q=" + encodeURIComponent(term))
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        results.textContent = '';
                        var items = data.results || [];

                        if (items.length === 0) {
                            var empty = document.createElement('p');
                            empty.className = 'text-[11px] text-on-surface-variant italic py-3 px-4';
                            empty.textContent = 'No products found.';
                            results.appendChild(empty);
                        }

                        items.forEach(function (product) {
                            var row = document.createElement('button');
                            row.type = 'button';
                            row.className = 'w-full flex items-center gap-3 py-2.5 px-3 hover:bg-warm-ivory/60 transition-colors text-left cursor-pointer';

                            var img = document.createElement('img');
                            img.src = product.image;
                            img.className = 'w-9 h-11 object-cover rounded-lg shrink-0 border border-border-subtle';

                            var textWrap = document.createElement('span');
                            textWrap.className = 'flex-1 min-w-0';

                            var nameEl = document.createElement('span');
                            nameEl.className = 'block text-xs font-semibold text-charcoal-text truncate';
                            nameEl.textContent = product.name;

                            var metaEl = document.createElement('span');
                            metaEl.className = 'block text-[11px] text-on-surface-variant';
                            metaEl.textContent = product.sku + ' — ' + product.price;

                            textWrap.appendChild(nameEl);
                            textWrap.appendChild(metaEl);
                            row.appendChild(img);
                            row.appendChild(textWrap);

                            row.addEventListener('click', function () {
                                addChip(product);
                                results.classList.add('hidden');
                                input.value = '';
                            });

                            results.appendChild(row);
                        });

                        results.classList.remove('hidden');
                    });
            }, 250);
        });

        document.addEventListener('click', function (event) {
            if (!results.contains(event.target) && event.target !== input) {
                results.classList.add('hidden');
            }
        });
    })();
</script>
