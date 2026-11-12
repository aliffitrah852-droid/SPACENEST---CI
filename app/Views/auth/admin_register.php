<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-md mx-auto mt-6 mb-10">
    <div class="glass-panel p-8 rounded-3xl shadow-xl border border-white/60">
        <div class="flex items-center gap-2 mb-2">
            <span class="px-3 py-1 bg-amber-600 text-white text-[10px] font-bold rounded-full uppercase tracking-wider">Registrasi Khusus</span>
            <span class="text-xs text-stone-500 font-semibold">Pihak Fakultas / Admin</span>
        </div>
        <h2 class="text-2xl font-bold text-stone-900 mb-1">Daftar Akun Admin FTST</h2>
        <p class="text-xs text-stone-500 mb-6">Buat akun pengelola untuk verifikasi dan pemantauan fasilitas.</p>


        <form method="POST" action="<?= base_url('admin/register') ?>" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" value="<?= esc(old('name')) ?>" required placeholder="Dr. Ir. Admin Fakultas, M.T." 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 focus:ring-2 focus:ring-amber-500 text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Email Resmi Fakultas *</label>
                <input type="email" name="email" value="<?= esc(old('email')) ?>" required placeholder="admin.ftst@unisma.ac.id" 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 focus:ring-2 focus:ring-amber-500 text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Kata Sandi *</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 focus:ring-2 focus:ring-amber-500 text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 mb-1">Kode Registrasi Admin *</label>
                <input type="password" name="kode_admin" required placeholder="Kode dari pengelola sistem" autocomplete="off"
                       class="w-full px-4 py-2.5 rounded-xl bg-white/70 border border-stone-300 focus:ring-2 focus:ring-amber-500 text-xs">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs transition shadow-md">
                Daftar Sebagai Admin FTST
            </button>
        </form>

        <div class="mt-6 flex justify-between items-center text-xs text-stone-500">
            <a href="<?= base_url('register') ?>" class="hover:underline">← Daftar sebagai Pelapor</a>
            <a href="<?= base_url('login') ?>" class="text-amber-700 font-semibold hover:underline">Sudah punya akun? Masuk</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
