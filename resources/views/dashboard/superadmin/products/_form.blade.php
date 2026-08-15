{{-- Shared form partial for create & edit product --}}
@php $isEdit = isset($product); @endphp

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label">Nama Produk <span>*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}"
               placeholder="Contoh: Axioo Hype 5" required>
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Seri Laptop <span>*</span></label>
        <select name="series" class="form-control" required>
            <option value="hype"  {{ old('series', $product->series ?? '') === 'hype'  ? 'selected':'' }}>Axioo Hype</option>
            <option value="pongo" {{ old('series', $product->series ?? '') === 'pongo' ? 'selected':'' }}>Axioo Pongo</option>
        </select>
        @error('series')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Harga Asli (Rp)</label>
        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}"
               placeholder="Contoh: 6499000" min="0">
        @error('price')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Harga Diskon (Rp - opsional)</label>
        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price ?? '') }}"
               placeholder="Contoh: 5999000" min="0">
        <span class="form-hint" style="font-size: 0.7rem; color: var(--text-muted);">Harga asli akan dicoret jika diskon diisi.</span>
        @error('discount_price')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group" style="grid-column: 1 / -1;">
        <label class="form-label">Link Online Shop (Tokopedia / Shopee / Website)</label>
        <input type="url" name="tokopedia_url" class="form-control" value="{{ old('tokopedia_url', $product->tokopedia_url ?? '') }}"
               placeholder="Contoh: https://www.tokopedia.com/nama-toko/laptop-axioo-hype-5">
        <span class="form-hint" style="font-size: 0.72rem; color: var(--text-muted);">Link ke toko/halaman produk di e-commerce untuk pembelian online.</span>
        @error('tokopedia_url')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

<div style="background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); padding:16px; margin:16px 0;">
    <div style="font-size:0.72rem; color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">Spesifikasi Teknis</div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div class="form-group" style="grid-column:1/-1; margin-bottom:0;">
            <label class="form-label">Processor <span>*</span></label>
            <input type="text" name="processor" class="form-control" value="{{ old('processor', $product->processor ?? '') }}"
                   placeholder="Contoh: Intel Core i5-1235U" required>
            @error('processor')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">RAM <span>*</span></label>
            <input type="text" name="ram" class="form-control" value="{{ old('ram', $product->ram ?? '') }}"
                   placeholder="Contoh: 8GB DDR4" required>
            @error('ram')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Penyimpanan (SSD) <span>*</span></label>
            <input type="text" name="storage" class="form-control" value="{{ old('storage', $product->storage ?? '') }}"
                   placeholder="Contoh: 512GB SSD NVMe" required>
            @error('storage')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Kartu Grafis (GPU - opsional)</label>
            <input type="text" name="gpu" class="form-control" value="{{ old('gpu', $product->gpu ?? '') }}"
                   placeholder="Contoh: NVIDIA RTX 4060">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Layar <span>*</span></label>
            <input type="text" name="display" class="form-control" value="{{ old('display', $product->display ?? '') }}"
                   placeholder="Contoh: 14 FHD IPS" required>
            @error('display')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Baterai</label>
            <input type="text" name="battery" class="form-control" value="{{ old('battery', $product->battery ?? '') }}"
                   placeholder="Contoh: 45Wh">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Berat</label>
            <input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight ?? '') }}"
                   placeholder="Contoh: 1.4 kg">
        </div>
        <div class="form-group" style="margin-bottom:0; grid-column: 1 / -1;">
            <label class="form-label">Konektivitas</label>
            <input type="text" name="connectivity" class="form-control" value="{{ old('connectivity', $product->connectivity ?? '') }}"
                   placeholder="Contoh: Wi-Fi 6, Bluetooth 5.1">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Fitur Unggulan</label>
    <textarea name="features" class="form-control" rows="3"
              placeholder="Tuliskan satu fitur unggulan per baris...&#10;Contoh:&#10;Layar Tipis Modern&#10;Dukungan Fast Charging">{{ old('features', $isEdit && $product->features ? implode("\n", $product->features) : '') }}</textarea>
    <span class="form-hint">Satu fitur per baris. Akan ditampilkan sebagai badge produk.</span>
</div>

<div class="form-group">
    <label class="form-label">Deskripsi Lengkap</label>
    <textarea name="description" class="form-control" rows="3"
              placeholder="Jelaskan deskripsi detail produk...">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label class="form-label">Foto-foto Produk <span style="font-weight: 400; color: var(--text-muted);">(bisa pilih lebih dari 1 foto)</span></label>
    @if($isEdit && count($product->images) > 0)
    <div style="margin-bottom: 12px;">
        <div style="font-size: 0.72rem; font-weight: 700; color: var(--text-muted); margin-bottom: 6px;">Foto Produk Terupload (Centang untuk menghapus):</div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach($product->images as $img)
            <div style="position: relative; border: 1px solid var(--border); border-radius: 6px; padding: 4px; background: var(--bg-alt); text-align: center;">
                <img src="{{ asset('storage/' . $img) }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                <label style="display: flex; align-items: center; justify-content: center; gap: 4px; font-size: 0.65rem; color: #dc2626; font-weight: 700; margin-top: 4px; cursor: pointer;">
                    <input type="checkbox" name="remove_images[]" value="{{ $img }}"> Hapus
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <input type="file" name="images[]" multiple class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
    <span class="form-hint">Tekan Ctrl/Shift saat memilih file untuk meng-upload **beberapa foto** sekaligus. Maksimal 3MB per foto.</span>
    @error('images')<span class="form-error">{{ $message }}</span>@enderror
    @error('images.*')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div style="display:flex; align-items:center; gap:8px; padding:12px; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border);">
    <input type="checkbox" name="is_active" id="is_active" value="1" style="accent-color:var(--primary); width:16px; height:16px;"
           {{ old('is_active', $product->is_active ?? true) ? 'checked':'' }}>
    <label for="is_active" style="cursor:pointer; font-weight:600; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.02em;">
        Tampilkan di Katalog Publik
    </label>
</div>
