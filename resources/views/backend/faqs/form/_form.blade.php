@include('backend.partials.form.errors')

<div class="gd-form">
    <div class="gd-form__main">
        @include('backend.partials.form.card-open', ['icon' => 'help-circle-outline', 'title' => 'Pertanyaan & Jawaban', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
            @include('backend.partials.form.bilingual', ['title' => 'Pertanyaan', 'en' => 'question', 'id' => 'question_id', 'required' => true, 'type' => 'text', 'placeholder' => 'e.g. How do I make a booking?', 'placeholderId' => 'mis. Bagaimana cara memesan?', 'hint' => 'Versi Indonesia boleh dikosongkan — pengunjung ID akan melihat versi English.'])
            @include('backend.partials.form.bilingual', ['title' => 'Jawaban', 'en' => 'answer', 'id' => 'answer_id', 'required' => true])
        @include('backend.partials.form.card-close')
    </div>

    <aside class="gd-form__side">
        @include('backend.partials.form.card-open', ['icon' => 'folder-outline', 'title' => 'Kategori', 'desc' => 'Grup akordeon di halaman FAQ.'])
            <div class="gd-field">
                <label class="gd-label">Kategori <span class="gd-req">*</span></label>
                {!! Form::select('faq_category_id', $categories, null, ['class' => 'form-control gd-input', 'required' => 'required', 'placeholder' => 'Pilih kategori...']) !!}
                {!! $errors->first('faq_category_id', '<p class="gd-error">:message</p>') !!}
                <p class="gd-hint mb-0"><a href="{{ route('faq-categories.create') }}">+ Tambah kategori baru</a></p>
            </div>
            <div class="gd-field">
                <label class="gd-label">Urutan Tampil</label>
                {!! Form::number('sort_order', null, ['class' => 'form-control gd-input', 'min' => 0]) !!}
                <p class="gd-hint mb-0">Angka kecil tampil lebih dulu.</p>
            </div>
        @include('backend.partials.form.card-close')

        @include('backend.partials.form.card-open', ['icon' => 'eye', 'title' => 'Status', 'desc' => 'Pertanyaan aktif tampil di website.'])
            @include('backend.partials.form.choice', ['name' => 'is_active', 'value' => (int) old('is_active', $faq->is_active ?? 1), 'on' => 'Tampil', 'off' => 'Sembunyi'])
        @include('backend.partials.form.card-close')
    </aside>
</div>

@include('backend.partials.form.actionbar', ['cancel' => route('faqs.index'), 'label' => 'Simpan Pertanyaan'])
