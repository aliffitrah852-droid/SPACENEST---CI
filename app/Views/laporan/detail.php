<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="glass-panel p-8 rounded-3xl shadow-xl">
        <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-stone-600 hover:text-stone-900 mb-4"><i class="fa-solid fa-arrow-left"></i> Kembali ke dashboard</a>
        <div class="flex justify-between items-start border-b border-stone-200 pb-4 mb-6">
            <div>
                <span class="text-xs font-semibold text-orange-700 tracking-wide uppercase">Detail Laporan #<?= $report['id'] ?></span>
                <h1 class="text-2xl font-bold text-stone-900"><?= esc($report['facility_name']) ?></h1>
                <p class="text-xs text-stone-500 mt-0.5">Pelapor: <?= esc($report['reporter_name']) ?> (<?= esc($report['email']) ?>)</p>
            </div>
            <span class="px-3 py-1 rounded-full font-bold text-xs bg-amber-100 text-amber-800"><?= esc($report['status']) ?></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs mb-6">
            <div>
                <span class="text-stone-400 block mb-1">Lokasi Facility</span>
                <p class="font-semibold text-stone-800"><?= esc($report['location']) ?></p>
            </div>
            <div>
                <span class="text-stone-400 block mb-1">Kategori & Urgensi</span>
                <p class="font-semibold text-stone-800"><?= esc($report['category']) ?> — Prioritas <?= esc($report['priority']) ?></p>
            </div>
            <div class="col-span-full">
                <span class="text-stone-400 block mb-1">Deskripsi Kerusakan</span>
                <p class="text-stone-700 bg-white/60 p-4 rounded-2xl border border-stone-200/50 leading-relaxed"><?= nl2br(esc($report['description'])) ?></p>
            </div>
            <?php if ($report['image']): ?>
                <div class="col-span-full">
                    <span class="text-stone-400 block mb-2">Dokumentasi Foto (US-09)</span>
                    <img src="<?= base_url('uploads/' . $report['image']) ?>" alt="Foto kerusakan" class="max-h-64 rounded-2xl border border-stone-300 shadow-sm object-cover">
                </div>
            <?php endif; ?>
        </div>

        <!-- Timeline Riwayat Status (US-10) -->
        <div class="border-t border-stone-200 pt-6">
            <h3 class="text-sm font-bold text-stone-900 mb-4">Riwayat Perubahan Status (Status Logs)</h3>
            <div class="relative border-l-2 border-[#2fbf8f]/60 ml-4 space-y-6">
                <?php foreach ($logs as $log): ?>
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-[#2fbf8f] border-2 border-white"></div>
                        <div class="font-bold text-xs text-stone-900"><?= esc($log['status']) ?></div>
                        <div class="text-[11px] text-stone-400 mb-1"><?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?></div>
                        <?php if ($log['notes']): ?>
                            <div class="text-xs text-stone-600 italic bg-stone-100/60 p-2 rounded-lg border border-stone-200/40">"<?= esc($log['notes']) ?>"</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
