@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'card-text-outline', 'title' => 'Isi Kartu', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
            @include('backend.partials.form.bilingual', ['title' => 'Judul', 'en' => 'name', 'id' => 'name_id', 'required' => true, 'type' => 'text', 'placeholder' => 'e.g. Culture', 'placeholderId' => 'mis. Budaya', 'hint' => 'Versi Indonesia boleh dikosongkan — pengunjung ID akan melihat versi English.'])
            @include('backend.partials.form.bilingual', ['title' => 'Deskripsi Singkat', 'en' => 'desc', 'id' => 'desc_id', 'type' => 'textarea', 'hint' => 'Opsional, tampil 2 baris di bawah judul. Maks. 300 karakter.'])
            <div class="gd-field">
                <label class="gd-label">Link Tujuan</label>
                {!! Form::text('url', null, ['class' => 'form-control gd-input', 'maxlength' => 255, 'placeholder' => 'mis. tour-packages atau https://...']) !!}
                {!! $errors->first('url', '<p class="gd-error">:message</p>') !!}
                <p class="gd-hint mb-0">Kosongkan untuk ke halaman daftar desa. Path di website (mis. <code>tour-packages</code>) otomatis mengikuti bahasa pengunjung.</p>
            </div>
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'image', 'title' => 'Gambar Kartu', 'desc' => 'Tampil penuh di kartu, rasio potret/persegi disarankan.'])
            @include('backend.partials.form.image-upload', ['name' => 'image', 'current' => $card->image ? asset('storage/tag/'.$card->image) : null, 'required' => ! $card->exists, 'hint' => 'JPG / PNG / WEBP, maks. 5 MB'])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'sort', 'title' => 'Urutan & Status'])
            <div class="gd-field">
                <label class="gd-label">Urutan Tampil</label>
                {!! Form::number('sort_order', null, ['class' => 'form-control gd-input', 'min' => 0]) !!}
                <p class="gd-hint mb-0">Angka kecil tampil lebih dulu. Beranda menampilkan maks. 6 kartu.</p>
            </div>
            @include('backend.partials.form.choice', ['name' => 'status', 'label' => 'Tampil di Beranda', 'value' => (int) old('status', $card->status ?? 1), 'on' => 'Tampil', 'off' => 'Sembunyi'])
        @include('backend.partials.form.card-close')
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => route('explore-cards.index'), 'label' => 'Simpan Kartu'])
