@php
    $isAdmin = Auth::user()->role_id == 1;
    $currentImg = ! empty($package->default_img ?? null) ? asset('storage/homestay/'.$package->default_img) : null;
@endphp

@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'information-outline', 'title' => 'Informasi Homestay', 'desc' => 'Nama, kategori, lokasi, dan pemilik.'])
            <div class="gd-grid-2">
                @if ($isAdmin)
                    <div class="gd-field">
                        <label class="gd-label">Desa Wisata <span class="gd-req">*</span></label>
                        {!! Form::select('village_id', $villages, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih desa wisata...']) !!}
                        {!! $errors->first('village_id', '<p class="gd-error">:message</p>') !!}
                    </div>
                @endif
                <div class="gd-field">
                    <label class="gd-label">Kategori Homestay <span class="gd-req">*</span></label>
                    {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-width' => '100%', 'title' => 'Pilih kategori...']) !!}
                    {!! $errors->first('category_id', '<p class="gd-error">:message</p>') !!}
                </div>
            </div>

            @include('backend.partials.form.bilingual', ['title' => 'Nama Homestay', 'en' => 'name', 'id' => 'name_id', 'idValue' => $packageTranslate->name ?? null, 'required' => true, 'type' => 'text'])
            @include('backend.partials.form.bilingual', ['title' => 'Lokasi', 'en' => 'location', 'id' => 'location_id', 'idValue' => $packageTranslate->location ?? null, 'type' => 'text', 'placeholder' => 'e.g. Penglipuran Village, Bangli', 'placeholderId' => 'mis. Desa Penglipuran, Bangli'])

            <div class="gd-field mb-0">
                <label class="gd-label">Nama Pemilik <span class="gd-req">*</span></label>
                {!! Form::text('owner_name', null, ['class' => 'form-control gd-input']) !!}
                {!! $errors->first('owner_name', '<p class="gd-error">:message</p>') !!}
            </div>
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'format-text', 'title' => 'Konten Homestay', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
            @include('backend.partials.form.bilingual', ['title' => 'Deskripsi', 'hint' => 'Gambaran umum homestay.', 'en' => 'description', 'id' => 'description_id', 'idValue' => $packageTranslate->description ?? null, 'required' => true])
            @include('backend.partials.form.bilingual', ['title' => 'Fasilitas', 'hint' => 'Kamar, kamar mandi, wifi, dll.', 'en' => 'facilities', 'id' => 'facilities_id', 'idValue' => $packageTranslate->facilities ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Aktivitas Tambahan', 'hint' => 'Kegiatan yang bisa diikuti tamu.', 'en' => 'additional_activities', 'id' => 'additional_activities_id', 'idValue' => $packageTranslate->additional_activities ?? null])
            @include('backend.partials.form.bilingual', ['title' => 'Catatan Tambahan', 'hint' => 'Aturan rumah atau info penting lain.', 'en' => 'additional_notes', 'id' => 'additional_notes_id', 'idValue' => $packageTranslate->additional_notes ?? null])
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'cash', 'title' => 'Harga', 'desc' => 'Dalam Rupiah penuh, per malam.'])
            @include('backend.partials.form.money', ['name' => 'price', 'label' => 'Harga Paket', 'required' => true])
            @include('backend.partials.form.money', ['name' => 'disc', 'label' => 'Harga Diskon', 'required' => true, 'hint' => 'Isi 0 bila tanpa diskon.', 'last' => true])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'clock', 'title' => 'Check-in & Check-out'])
            <div class="gd-grid-2">
                <div class="gd-field mb-0">
                    <label class="gd-label">Check-in <span class="gd-req">*</span></label>
                    {!! Form::text('check_in_time', null, ['class' => 'form-control gd-input', 'placeholder' => '14:00']) !!}
                    {!! $errors->first('check_in_time', '<p class="gd-error">:message</p>') !!}
                </div>
                <div class="gd-field mb-0">
                    <label class="gd-label">Check-out <span class="gd-req">*</span></label>
                    {!! Form::text('check_out_time', null, ['class' => 'form-control gd-input', 'placeholder' => '12:00']) !!}
                    {!! $errors->first('check_out_time', '<p class="gd-error">:message</p>') !!}
                </div>
            </div>
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'image', 'title' => 'Gambar Utama', 'desc' => 'Tampil di kartu & header homestay.'])
            @include('backend.partials.form.image-upload', ['name' => 'default_img', 'current' => $currentImg, 'accept' => '.jpg,.jpeg,.png,.webp', 'hint' => 'JPG / JPEG / PNG / WEBP, maks. 5 MB'])
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'tune', 'title' => 'Pengaturan'])
            @include('backend.partials.form.choice', ['name' => 'is_breakfast', 'label' => 'Sarapan', 'value' => empty($package) ? 1 : $package->is_breakfast, 'on' => 'Termasuk', 'off' => 'Tidak'])
            @if ($isAdmin)
                @include('backend.partials.form.choice', ['name' => 'is_active', 'label' => 'Status Publikasi', 'value' => empty($package) ? 1 : $package->is_active])
            @endif
        @include('backend.partials.form.card-close')
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => url('administrator/homestay'), 'label' => 'Simpan Homestay'])
