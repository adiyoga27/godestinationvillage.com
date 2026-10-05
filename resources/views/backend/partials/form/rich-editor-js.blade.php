{{-- Inisialisasi TinyMCE untuk textarea.gd-rich. Param: height (opsional). Taruh di @section('js'). --}}
<script src="{{ asset('assets/admin/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
  tinymce.init({
    selector: "textarea.gd-rich",
    height: {{ (int) ($height ?? 320) }},
    menubar: false,
    branding: false,
    setup: function (editor) {
      editor.on('change', function () { editor.save(); });
    },
    plugins: [
      "advlist autolink link image lists charmap preview hr anchor",
      "searchreplace wordcount visualblocks nonbreaking table paste code"
    ],
    toolbar: "undo redo | formatselect | bold italic underline | bullist numlist outdent indent | link unlink anchor | image table | removeformat code",
    block_formats: "Paragraf=p; Judul=h2; Sub judul=h3",
    image_uploadtab: true,
    images_upload_url: "{{ route('tinymce.upload_image') }}",
    images_upload_headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    automatic_uploads: true,
    relative_urls: false,
    remove_script_host: false,
  });
</script>
