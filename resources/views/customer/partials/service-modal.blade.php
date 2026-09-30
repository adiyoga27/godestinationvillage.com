{{-- Popup deskripsi service. Isi + tombol 100% dari database (Admin > Homepage / Services).
     Dipakai homepage & halaman services. Cara pakai tombol pemicu:
     <button data-service-modal data-title=".." data-desc="<html>" data-icon=".." data-phone=".."
             data-whatsapp=".." data-file=".." data-file-name=".." data-buttons='[{"label":"..","url":".."}]'
             data-page=".." data-page-label=".."> --}}
<div id="serviceModal" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="serviceModalTitle" aria-hidden="true">
    <div data-close-service-modal class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm"></div>
    <div class="relative max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <button type="button" data-close-service-modal aria-label="Close"
            class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-600 transition hover:bg-ink-100 hover:text-ink-900">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <div class="p-8 sm:p-10">
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-cream-100 p-5">
                <img data-service-img src="" alt="" class="h-full w-full object-contain">
            </div>
            <h3 id="serviceModalTitle" data-service-title class="mt-6 text-center font-display text-2xl font-bold text-ink-950"></h3>
            <div data-service-desc class="mt-5 text-justify leading-relaxed text-ink-600"></div>
            <div data-service-buttons class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <a data-service-call href="#" class="btn btn-secondary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                    {{ __('Call') }}
                </a>
                <a data-service-wa href="#" target="_blank" rel="noopener" class="btn btn-primary">
                    <svg class="h-4 w-4" fill="#25D366" viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.13.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.21-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35zm-5.42 7.4h-.004a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.9-9.88a9.83 9.83 0 019.88 9.89c0 5.45-4.43 9.88-9.88 9.88zM12.05 21.5c2.6 0 5.03-1.01 6.86-2.84a9.66 9.66 0 002.84-6.87 9.66 9.66 0 00-2.84-6.87c-1.83-1.82-4.26-2.83-6.86-2.83a9.68 9.68 0 00-9.69 9.69c0 1.7.45 3.37 1.3 4.83l-1.4 5.12 5.24-1.37a9.67 9.67 0 004.55 1.14z" /></svg>
                    {{ __('Message') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__serviceModalInit) return;
        window.__serviceModalInit = true;
        var modal = document.getElementById('serviceModal');
        if (!modal) return;

        var titleEl = modal.querySelector('[data-service-title]');
        var descEl = modal.querySelector('[data-service-desc]');
        var imgEl = modal.querySelector('[data-service-img]');
        var callEl = modal.querySelector('[data-service-call]');
        var waEl = modal.querySelector('[data-service-wa]');
        var btnWrap = modal.querySelector('[data-service-buttons]');

        function open() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function addBtn(label, href, download) {
            var a = document.createElement('a');
            a.className = 'btn btn-secondary';
            a.textContent = label;
            a.href = href || '#';
            if (download) {
                a.setAttribute('download', '');
            } else if (/^https?:\/\//.test(href || '')) {
                a.setAttribute('target', '_blank');
                a.setAttribute('rel', 'noopener');
            }
            btnWrap.appendChild(a);
        }

        document.querySelectorAll('[data-service-modal]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                titleEl.textContent = btn.dataset.title || '';
                descEl.innerHTML = btn.dataset.desc || '';
                imgEl.src = btn.dataset.icon || '';
                imgEl.alt = btn.dataset.title || '';
                callEl.href = 'tel:' + (btn.dataset.phone || '');
                waEl.href = 'https://wa.me/' + (btn.dataset.whatsapp || '');

                btnWrap.querySelectorAll('[data-custom-btn]').forEach(function (el) { el.remove(); });

                if (btn.dataset.file) {
                    var dl = document.createElement('a');
                    dl.className = 'btn btn-secondary';
                    dl.setAttribute('data-custom-btn', '');
                    dl.href = btn.dataset.file;
                    dl.setAttribute('download', '');
                    dl.textContent = btn.dataset.fileLabel || 'Download';
                    btnWrap.appendChild(dl);
                }

                try {
                    var custom = JSON.parse(btn.dataset.buttons || '[]');
                    custom.forEach(function (b) {
                        if (!b || !b.label) return;
                        var a = document.createElement('a');
                        a.className = 'btn btn-secondary';
                        a.setAttribute('data-custom-btn', '');
                        a.textContent = b.label;
                        a.href = b.url || '#';
                        if (b.download) a.setAttribute('download', '');
                        else if (/^https?:\/\//.test(b.url || '')) { a.setAttribute('target', '_blank'); a.setAttribute('rel', 'noopener'); }
                        btnWrap.appendChild(a);
                    });
                } catch (e) {}

                if (btn.dataset.page) {
                    var p = document.createElement('a');
                    p.className = 'btn btn-primary';
                    p.setAttribute('data-custom-btn', '');
                    p.textContent = btn.dataset.pageLabel || 'Learn more';
                    p.href = btn.dataset.page;
                    btnWrap.appendChild(p);
                }

                open();
            });
        });

        modal.querySelectorAll('[data-close-service-modal]').forEach(function (el) {
            el.addEventListener('click', close);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') close();
        });
    })();
</script>
