@php
    $isAdmin = Auth::user()->role_id == 1;
    $currentImg = ! empty($package->default_img ?? null) ? asset('storage/events/'.$package->default_img) : null;
@endphp

@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'information-outline', 'title' => 'Informasi Event', 'desc' => 'Nama, kategori, dan desa penyelenggara.'])
            <div class="gd-grid-2">
                @if ($isAdmin)
                    <div class="gd-field">
                        <label class="gd-label">Desa Wisata <span class="gd-req">*</span></label>
                        {!! Form::select('village_id', $villages, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih desa wisata...']) !!}
                        {!! $errors->first('village_id', '<p class="gd-error">:message</p>') !!}
                    </div>
                @endif
                <div class="gd-field">
                    <label class="gd-label">Kategori Event <span class="gd-req">*</span></label>
                    {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih kategori...']) !!}
                    {!! $errors->first('category_id', '<p class="gd-error">:message</p>') !!}
                </div>
            </div>

            @include('backend.partials.form.bilingual', ['title' => 'Nama Event', 'en' => 'name', 'id' => 'name_id', 'idValue' => $packageTranslate->name ?? null, 'required' => true, 'type' => 'text', 'placeholder' => 'e.g. Ogoh-ogoh Festival', 'placeholderId' => 'mis. Festival Ogoh-ogoh'])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'calendar', 'title' => 'Jadwal & Lokasi', 'desc' => 'Kapan dan di mana event berlangsung.'])
            <div class="gd-grid-2">
                <div class="gd-field">
                    <label class="gd-label">Tanggal & Jam <span class="gd-req">*</span></label>
                    {!! Form::input('datetime-local', 'date_event', null, ['class' => 'form-control gd-input', 'required' => 'required']) !!}
                    <p class="gd-hint">Klik ikon kalender untuk memilih tanggal.</p>
                    {!! $errors->first('date_event', '<p class="gd-error">:message</p>') !!}
                </div>
                <div class="gd-field">
                    <label class="gd-label">Durasi <span class="gd-req">*</span></label>
                    {!! Form::text('duration', null, ['class' => 'form-control gd-input', 'placeholder' => 'mis. 5 Hours']) !!}
                    {!! $errors->first('duration', '<p class="gd-error">:message</p>') !!}
                </div>
            </div>
            <div class="gd-field mb-0">
                <label class="gd-label">Lokasi <span class="gd-req">*</span></label>
                {!! Form::text('location', null, ['class' => 'form-control gd-input', 'placeholder' => 'mis. Badung, Mengwi, Denpasar']) !!}
                {!! $errors->first('location', '<p class="gd-error">:message</p>') !!}
            </div>
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'format-text', 'title' => 'Konten Event', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
            @include('backend.partials.form.bilingual', ['title' => 'Deskripsi', 'hint' => 'Gambaran umum event.', 'en' => 'description', 'id' => 'description_id', 'idValue' => $packageTranslate->description ?? null, 'required' => true])
            @include('backend.partials.form.bilingual', ['title' => 'Itinerary', 'hint' => 'Susunan acara.', 'en' => 'interary', 'id' => 'interary_id', 'idValue' => $packageTranslate->itenaries ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Inclusion', 'hint' => 'Apa saja yang sudah termasuk.', 'en' => 'inclusion', 'id' => 'inclusion_id', 'idValue' => $packageTranslate->inclusion ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Informasi Tambahan', 'hint' => 'Catatan lain untuk peserta.', 'en' => 'additional', 'id' => 'additional_id', 'idValue' => $packageTranslate->term ?? null])
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'cash', 'title' => 'Harga', 'desc' => 'Dalam Rupiah penuh.'])
            @include('backend.partials.form.money', ['name' => 'price', 'label' => 'Harga Paket', 'required' => true])
            @include('backend.partials.form.money', ['name' => 'disc', 'label' => 'Harga Diskon', 'required' => true, 'hint' => 'Isi 0 bila tanpa diskon.', 'last' => true])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'image', 'title' => 'Gambar Utama', 'desc' => 'Tampil di kartu & header event.'])
            @include('backend.partials.form.image-upload', ['name' => 'default_img', 'current' => $currentImg])
        @include('backend.partials.form.card-close')

        @if ($isAdmin)
            @include('backend.partials.form.card-open', ['icon' => 'tune', 'title' => 'Pengaturan', 'desc' => 'Model harga & publikasi.'])
                @include('backend.partials.form.choice', ['name' => 'is_paywish', 'label' => 'Pay as You Wish', 'value' => empty($package) ? 1 : $package->is_paywish, 'on' => 'Ya', 'off' => 'Tidak'])
                @include('backend.partials.form.choice', ['name' => 'is_free', 'label' => 'Gratis', 'value' => empty($package) ? 1 : $package->is_free, 'on' => 'Ya', 'off' => 'Tidak'])
                @include('backend.partials.form.choice', ['name' => 'is_active', 'label' => 'Status Publikasi', 'value' => empty($package) ? 1 : $package->is_active])
            @include('backend.partials.form.card-close')
        @endif
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => url('administrator/events'), 'label' => 'Simpan Event'])
