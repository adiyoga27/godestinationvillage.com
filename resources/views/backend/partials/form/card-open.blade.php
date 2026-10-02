{{-- Pembuka kartu form. Param: icon (mdi tanpa prefix), title, desc (opsional). Tutup dengan partials.form.card-close. --}}
<section class="gd-card">
    <header class="gd-card__head">
        <span class="gd-card__icon"><i class="mdi mdi-{{ $icon ?? 'information-outline' }}"></i></span>
        <div>
            <h2 class="gd-card__title">{{ $title }}</h2>
            @if (! empty($desc))<p class="gd-card__desc">{{ $desc }}</p>@endif
        </div>
    </header>
    <div class="gd-card__body">
