@php
    $isAdmin = Auth::user()->role_id == 1;
    $currentImg = ! empty($package->default_img ?? null) ? asset('storage/packages/'.$package->default_img) : null;
@endphp

@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'information-outline', 'title' => 'Informasi Dasar', 'desc' => 'Nama, kategori, dan desa pemilik paket.'])
            @if ($isAdmin)
                <div class="gd-field">
                    <label class="gd-label">Desa Wisata <span class="gd-req">*</span></label>
                    {!! Form::select('village_id', $villages, $package->village_id ?? null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih desa wisata...']) !!}
                    {!! $errors->first('village_id', '<p class="gd-error">:message</p>') !!}
                    {!! $errors->first('user_id', '<p class="gd-error">:message</p>') !!}
                </div>
            @else
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="village_id" value="{{ Auth::user()->village_id }}">
            @endif

            <div class="gd-grid-2">
                <div class="gd-field">
                    <label class="gd-label">Kategori Paket <span class="gd-req">*</span></label>
                    {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih kategori...']) !!}
                    {!! $errors->first('category_id', '<p class="gd-error">:message</p>') !!}
                </div>
                <div class="gd-field">
                    <label class="gd-label">Tag Paket <span class="gd-req">*</span></label>
                    {!! Form::select('tag_id', $tags, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih tag...']) !!}
                    {!! $errors->first('tag_id', '<p class="gd-error">:message</p>') !!}
                </div>
            </div>

            @include('backend.partials.form.bilingual', ['title' => 'Nama Paket', 'en' => 'name', 'id' => 'name_id', 'idValue' => $packageTranslate->name ?? null, 'required' => true, 'type' => 'text', 'placeholder' => 'e.g. Rice Terrace Trekking', 'placeholderId' => 'mis. Trekking Sawah Terasering'])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'format-text', 'title' => 'Konten Paket', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
            @include('backend.partials.form.bilingual', ['title' => 'Deskripsi', 'hint' => 'Gambaran umum paket yang tampil di halaman detail.', 'en' => 'desc', 'id' => 'desc_id', 'idValue' => $packageTranslate->desc ?? null, 'required' => true])
            @include('backend.partials.form.bilingual', ['title' => 'Itinerary', 'hint' => 'Rangkaian kegiatan dari awal sampai akhir.', 'en' => 'itenaries', 'id' => 'itenaries_id', 'idValue' => $packageTranslate->itenaries ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Inclusion', 'hint' => 'Apa saja yang sudah termasuk dalam harga.', 'en' => 'inclusion', 'id' => 'inclusion_id', 'idValue' => $packageTranslate->inclusion ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Term & Condition', 'hint' => 'Syarat, ketentuan, dan kebijakan pembatalan.', 'en' => 'term', 'id' => 'term_id', 'idValue' => $packageTranslate->term ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Durasi', 'hint' => 'Lama kegiatan, mis. 3 jam / 2 hari 1 malam.', 'en' => 'duration', 'id' => 'duration_id', 'idValue' => $packageTranslate->duration ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Persiapan yang Diperlukan', 'hint' => 'Barang atau kondisi yang perlu disiapkan peserta.', 'en' => 'preparation', 'id' => 'preparation_id', 'idValue' => $packageTranslate->preparation ?? null])
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'cash', 'title' => 'Harga', 'desc' => 'Dalam Rupiah penuh.'])
            @include('backend.partials.form.money', ['name' => 'price', 'label' => 'Harga Paket', 'required' => true, 'hint' => 'Contoh 180000 (bukan 180). Isi 0 untuk gratis.'])
            @include('backend.partials.form.money', ['name' => 'disc', 'label' => 'Diskon Paket', 'required' => true, 'hint' => 'Potongan dalam Rupiah. Isi 0 bila tanpa diskon.', 'last' => true])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'image', 'title' => 'Gambar Utama', 'desc' => 'Tampil di kartu & header paket.'])
            @include('backend.partials.form.image-upload', ['name' => 'default_img', 'current' => $currentImg])
        @include('backend.partials.form.card-close')

        @if ($isAdmin)
            @include('backend.partials.form.card-open', ['icon' => 'eye', 'title' => 'Status Publikasi', 'desc' => 'Paket aktif tampil di website.'])
                @include('backend.partials.form.choice', ['name' => 'is_active', 'value' => empty($package) ? 1 : $package->is_active])
            @include('backend.partials.form.card-close')
        @endif
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => url('administrator/package'), 'label' => 'Simpan Paket'])
