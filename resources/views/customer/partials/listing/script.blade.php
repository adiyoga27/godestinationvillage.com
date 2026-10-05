<script>
    // Panel filter daftar ([data-listing-filter]): langsung diterapkan saat pilihan diubah;
    // kolom kosong & nilai default (data-default) tidak ikut ke URL.
    (function () {
        document.querySelectorAll('[data-listing-filter]').forEach(function (form) {
            form.querySelectorAll('[data-autosubmit]').forEach(function (el) {
                el.addEventListener('change', function () { form.requestSubmit ? form.requestSubmit() : form.submit(); });
            });
            form.addEventListener('submit', function () {
                form.querySelectorAll('input, select').forEach(function (el) {
                    var isChoice = el.type === 'radio' || el.type === 'checkbox';
                    if (isChoice ? (el.checked && el.value === '') : (el.value === '' || el.value === el.getAttribute('data-default'))) {
                        el.disabled = true;
                    }
                });
            });
        });
    })();
</script>
