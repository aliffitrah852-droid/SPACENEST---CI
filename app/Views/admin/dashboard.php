<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="glass-panel p-6 rounded-3xl flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-stone-900">Panel Manajemen Admin FTST</h1>
            <p class="text-xs text-stone-500">Verifikasi dan perbarui status laporan fasilitas kampus.</p>
        </div>
    </div>

    <div class="glass-panel p-6 rounded-3xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-300/60 text-stone-500 font-semibold uppercase">
                        <th class="pb-3 px-2">ID & Pelapor</th>
                        <th class="pb-3 px-2">Fasilitas & Lokasi</th>
                        <th class="pb-3 px-2">Status Saat Ini</th>
                        <th class="pb-3 px-2">Perbarui Status (US-07)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200/50">
                    <?php foreach ($reports as $r): ?>
                        <tr class="hover:bg-white/40 transition">
                            <td class="py-3.5 px-2">
                                <a href="<?= base_url('laporan/' . $r['id']) ?>" class="font-bold text-stone-900 hover:underline block">#<?= $r['id'] ?> - <?= esc($r['reporter_name']) ?></a>
                                <div class="text-[11px] text-stone-400"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
                            </td>
                            <td class="py-3.5 px-2">
                                <div class="font-semibold text-stone-800"><?= esc($r['facility_name']) ?></div>
                                <div class="text-[11px] text-stone-500"><?= esc($r['location']) ?></div>
                            </td>
                            <td class="py-3.5 px-2">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800"><?= esc($r['status']) ?></span>
                            </td>
                            <td class="py-3.5 px-2">
                                <form method="POST" action="<?= base_url('admin/status') ?>" class="flex items-center space-x-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="report_id" value="<?= $r['id'] ?>">
                                    <select name="status" class="px-2 py-1 rounded border border-stone-300 text-xs bg-white">
                                        <?php foreach ($statuses as $st): ?>
                                            <option value="<?= esc($st) ?>" <?= $r['status'] === $st ? 'selected' : '' ?>><?= esc($st) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="notes" placeholder="Catatan..." class="px-2 py-1 rounded border border-stone-300 text-xs bg-white/70 w-32">
                                    <button type="submit" name="update_status" class="px-3 py-1 rounded bg-stone-900 text-white font-semibold text-[11px] hover:bg-stone-800 transition">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
