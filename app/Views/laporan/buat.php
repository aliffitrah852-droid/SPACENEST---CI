<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">
    <div class="glass-panel p-8 rounded-3xl shadow-xl">
        <h2 class="text-xl font-bold text-stone-900 mb-1">Buat Laporan Fasilitas</h2>
        <p class="text-xs text-stone-500 mb-6">Laporkan kerusakan peralatan lab, komputer, atau fasilitas FTST lainnya.</p>



        <form method="POST" action="<?= base_url('laporan/simpan') ?>" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Fasilitas / Alat *</label>
                <input type="text" name="facility_name" value="<?= esc(old('facility_name')) ?>" required placeholder="Contoh: PC Lab Elektro 04 / AC Workshop Mesin" 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 focus:ring-2 focus:ring-emerald-500 text-xs">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Kategori (US-08)</label>
                    <select name="category" class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 text-xs">
                        <option value="Hardware/Lab">Hardware / Alat Lab</option>
                        <option value="Jaringan/Internet">Jaringan / Wi-Fi</option>
                        <option value="Mebel/Ruangan">Mebel / Ruangan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1">Urgensi / Prioritas (US-08)</label>
                    <select name="priority" class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 text-xs">
                        <option value="Rendah">Rendah</option>
                        <option value="Sedang" selected>Sedang</option>
                        <option value="Tinggi">Tinggi (Mendesak)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Lokasi Detail *</label>
                <input type="text" name="location" value="<?= esc(old('location')) ?>" required placeholder="Contoh: Gedung B, Lab 1 Elektro, Meja 03" 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Deskripsi Kerusakan *</label>
                <textarea name="description" rows="4" required placeholder="Jelaskan detail masalah atau kronologi kerusakan..." 
                          class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 text-xs"><?= esc(old('description')) ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Unggah Foto Kerusakan (US-09)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-stone-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="<?= base_url('/') ?>" class="px-5 py-2.5 rounded-xl bg-white text-stone-700 text-xs font-semibold hover:bg-stone-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1c1917] hover:bg-black text-white font-semibold text-xs transition shadow-md">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
