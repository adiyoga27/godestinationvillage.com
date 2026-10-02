{{-- Bar aksi bawah. Param: cancel (URL), label (opsional). --}}
<div class="gd-actionbar">
    <a href="{{ $cancel }}" class="gd-btn gd-btn--ghost">Batal</a>
    <button type="submit" class="gd-btn gd-btn--primary"><i class="mdi mdi-content-save"></i> {{ $label ?? 'Simpan' }}</button>
</div>
