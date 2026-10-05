@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'folder-outline', 'title' => 'Kategori FAQ', 'desc' => 'Judul grup akordeon di halaman FAQ, mis. Booking, Payment.'])
            @include('backend.partials.form.bilingual', ['title' => 'Nama Kategori', 'en' => 'title', 'id' => 'title_id', 'required' => true, 'type' => 'text', 'placeholder' => 'e.g. Booking', 'placeholderId' => 'mis. Pemesanan', 'hint' => 'Versi Indonesia boleh dikosongkan — pengunjung ID akan melihat versi English.'])
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'sort', 'title' => 'Urutan & Status'])
            <div class="gd-field">
                <label class="gd-label">Urutan Tampil</label>
                {!! Form::number('sort_order', null, ['class' => 'form-control gd-input', 'min' => 0]) !!}
                <p class="gd-hint mb-0">Angka kecil tampil lebih dulu.</p>
            </div>
            @include('backend.partials.form.choice', ['name' => 'is_active', 'label' => 'Status', 'value' => (int) old('is_active', $category->is_active ?? 1), 'on' => 'Tampil', 'off' => 'Sembunyi'])
        @include('backend.partials.form.card-close')
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => route('faqs.index'), 'label' => 'Simpan Kategori'])
