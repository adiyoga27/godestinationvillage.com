<div class="form-group">
    <label>Dimensi * <small class="text-muted">(contoh: Pemasaran & Branding — soal dikelompokkan per dimensi)</small></label>
    <input type="text" name="dimension" value="{{ old('dimension', $question->dimension ?? '') }}" class="form-control" required>
</div>
<div class="form-group">
    <label>Pernyataan * <small class="text-muted">(dinilai 1–5 oleh pengisi)</small></label>
    <textarea name="question" rows="3" class="form-control" required>{{ old('question', $question->question ?? '') }}</textarea>
</div>
<div class="form-group">
    <label>Teks Bantuan</label>
    <textarea name="help_text" rows="2" class="form-control">{{ old('help_text', $question->help_text ?? '') }}</textarea>
</div>
<div class="form-row">
    <div class="form-group col-md-4"><label>Bobot</label><input type="number" name="weight" value="{{ old('weight', $question->weight ?? 1) }}" class="form-control" min="1" max="10"></div>
    <div class="form-group col-md-4"><label>Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', $question->sort_order ?? 0) }}" class="form-control" min="0"></div>
    <div class="form-group col-md-4"><label>Aktif</label><select name="is_active" class="form-control"><option value="1" {{ old('is_active', $question->is_active ?? true) ? 'selected' : '' }}>Ya</option><option value="0" {{ ! old('is_active', $question->is_active ?? true) ? 'selected' : '' }}>Tidak</option></select></div>
</div>
