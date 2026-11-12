<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$statusChip = [
    'Dikirim'      => 'bg-stone-200 text-stone-700',
    'Diverifikasi' => 'bg-sky-100 text-sky-800',
    'Diproses'     => 'bg-indigo-100 text-indigo-800',
    'Selesai'      => 'bg-emerald-100 text-emerald-800',
];
$prioDot  = ['Rendah' => 'bg-emerald-500', 'Sedang' => 'bg-amber-500', 'Tinggi' => 'bg-rose-500'];
$chartColors = ['#1c1917', '#2fbf8f', '#e07a10', '#3b82f6', '#a78bfa', '#ef476f', '#06b6d4'];
?>
<div class="space-y-6 max-w-[1280px]">



    <!-- Judul -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold"><?= $isAdmin ? 'Dashboard Admin' : 'Dashboard Saya' ?></h1>
            <p class="mt-2 text-stone-800/80 text-lg">
                <?= $isAdmin
                    ? 'Pantau laporan dan penanganan fasilitas di seluruh FTST.'
                    : 'Pantau laporan fasilitas yang Anda kirim.' ?>
            </p>
        </div>
        <?php if (! $isAdmin): ?>
            <a href="<?= base_url('laporan/buat') ?>" class="self-start md:self-auto shrink-0 inline-flex items-center gap-3 rounded-full bg-[#1c1917] text-white font-bold px-7 py-4 shadow-xl hover:bg-black transition">
                <span class="w-5 h-5 rounded-full bg-amber-400 text-[#1c1917] grid place-items-center text-[10px]"><i class="fa-solid fa-plus"></i></span>Buat Laporan Baru
            </a>
        <?php endif; ?>
    </div>

    <!-- Kartu statistik -->
    <section id="statistik" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-5 scroll-mt-6 md:scroll-mt-24">
        <?php
        $cards = $isAdmin ? [
            ['Total Laporan FTST',  'fa-folder-open',        $statTotal,   $delta,             'bg-emerald-100 text-emerald-700', 'Bulan ini',            'text-[#1c1917]', false],
            ['Menunggu Verifikasi', 'fa-inbox',              $statDikirim, 'Perlu dicek',      'bg-amber-100 text-amber-700',     'Laporan baru',         'text-orange-600', false],
            ['Sedang Diproses',     'fa-screwdriver-wrench', $statProses,  'Aktif',            'bg-sky-100 text-sky-700',         'Pengerjaan teknis',    'text-sky-700', false],
            ['Laporan Selesai',     'fa-circle-check',       $statSelesai, $rate . '% selesai','bg-emerald-100 text-emerald-700', 'Tingkat penyelesaian', 'text-emerald-700', false],
        ] : [
            ['Total Laporan Saya',  'fa-folder-open',        $statTotal,   'Dikirim: ' . $statDikirim, 'bg-amber-100 text-amber-700', '', 'text-[#1c1917]', false],
            ['Sudah Diverifikasi',  'fa-circle-check',       $statVerif,   'Terverifikasi',     'bg-sky-100 text-sky-700',         'Siap diproses',        'text-sky-700', false],
            ['Sedang Diproses',     'fa-screwdriver-wrench', $statProses,  'Diproses',          'bg-indigo-100 text-indigo-700',   'Sedang dikerjakan',    'text-indigo-700', false],
            ['Laporan Selesai',     'fa-circle-check',       $statSelesai, $rate . '%',        'bg-emerald-100 text-emerald-700', 'Laporan tuntas',       'text-emerald-700', false],
        ];
        foreach ($cards as [$label, $icon, $val, $pill, $pillCls, $cap, $iconCls, $green]): ?>
            <div class="glass-panel <?= $green ? 'stat-green' : '' ?> p-5 min-h-[10rem] flex flex-col">
                <div class="flex items-start justify-between">
                    <span class="text-sm font-bold uppercase tracking-wide <?= $green ? '' : 'text-stone-700' ?>"><?= $label ?></span>
                    <span class="w-12 h-12 rounded-full grid place-items-center <?= $green ? 'bg-white/20' : 'bg-white shadow' ?> <?= $iconCls ?>"><?php if ($icon): ?><i class="fa-solid <?= $icon ?>"></i><?php endif; ?></span>
                </div>
                <div class="mt-auto">
                    <div class="text-4xl font-extrabold tight"><?= $val ?></div>
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm font-semibold">
                        <span class="shrink-0 whitespace-nowrap px-3 py-1 rounded-full <?= $pillCls ?>"><?= $pill ?></span>
                        <?php if ($cap): ?><span class="leading-snug <?= $green ? '' : $iconCls ?>"><?= $cap ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        <!-- Distribusi masalah -->
        <section class="glass-panel p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold"><?= $isAdmin ? 'Laporan per Program Studi' : 'Laporan per Kategori' ?></h2>
                    <p class="text-stone-600"><?= $isAdmin ? 'Perbandingan laporan dari setiap program studi' : 'Kategori laporan yang Anda kirim' ?></p>
                </div>
                <span class="px-4 py-2 rounded-full bg-white text-sm font-bold shadow-sm"><?= $isAdmin ? 'FTST' : 'Saya' ?> <?= date('Y') ?></span>
            </div>
            <?php if (!$dist): ?>
                <p class="mt-10 text-center text-sm text-stone-500">Belum ada data untuk ditampilkan.</p>
            <?php else: ?>
                <div class="relative mt-4 h-44 md:h-48">
                    <canvas id="distributionChart" role="img" aria-label="Grafik donut distribusi laporan"></canvas>
                </div>
            <?php endif; ?>
        </section>

        <section class="glass-panel p-5">
            <div>
                <h2 class="text-2xl font-bold"><?= $isAdmin ? 'Tren Laporan FTST' : 'Tren Laporan Saya' ?></h2>
                <p class="text-stone-600"><?= $isAdmin ? 'Jumlah laporan seluruh pelapor selama 12 bulan terakhir' : 'Jumlah laporan Anda selama 12 bulan terakhir' ?></p>
            </div>
            <div class="relative mt-4 h-44 md:h-48">
                <canvas id="monthlyChart" role="img" aria-label="Grafik tren jumlah laporan per bulan"></canvas>
            </div>
        </section>

        <!-- Aktivitas laporan -->
        <section id="daftar" class="glass-panel p-7 scroll-mt-6 md:scroll-mt-24">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold"><?= $isAdmin ? 'Laporan Terbaru FTST' : 'Laporan Saya' ?></h2>
                    <p class="text-stone-600"><?= $isAdmin ? 'Laporan terbaru dari seluruh pelapor' : 'Riwayat laporan yang Anda kirim' ?></p>
                </div>
                <?php if (count($reports) > 4): ?>
                    <button type="button" id="toggleAll" class="text-orange-700 font-bold text-sm whitespace-nowrap">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></button>
                <?php endif; ?>
            </div>

            <?php if (empty($reports)): ?>
                <div class="mt-8 rounded-2xl bg-white/60 p-8 text-center text-sm text-stone-600">
                    <?= $isAdmin ? 'Belum ada laporan di FTST.' : 'Anda belum mengirim laporan.' ?>
                    <a href="<?= base_url('laporan/buat') ?>" class="font-bold underline">Buat laporan</a>.
                </div>
            <?php else: ?>
                <div id="list" class="mt-6 space-y-3 max-h-[26rem] overflow-y-auto pr-1">
                    <?php foreach ($reports as $n => $r): ?>
                        <a href="<?= base_url('laporan/' . $r['id']) ?>" data-q="<?= esc(strtolower($r['facility_name'] . ' ' . $r['location'] . ' ' . $r['status'] . ' ' . $r['category'])) ?>"
                           class="item <?= $n >= 4 ? 'extra hidden' : '' ?> block rounded-2xl bg-white/75 hover:bg-white p-4 pl-5 transition">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="font-mono text-stone-500">SPT-<?= str_pad($r['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                <span class="px-3 py-1 rounded-full font-bold <?= $statusChip[$r['status']] ?>"><?= $r['status'] ?></span>
                                <span class="ml-auto flex items-center gap-1.5 text-stone-500"><span class="w-2 h-2 rounded-full <?= $prioDot[$r['priority']] ?>"></span>Prioritas <?= $r['priority'] ?></span>
                            </div>
                            <div class="mt-1.5 font-bold text-lg tight"><?= esc($r['facility_name']) ?></div>
                            <div class="flex items-center gap-2 text-stone-600 text-sm">
                                <i class="fa-solid fa-location-dot text-rose-600"></i><?= esc($r['location']) ?>
                                <?php if ($isAdmin): ?><span class="ml-auto text-xs text-stone-500">Oleh <?= esc($r['reporter_name']) ?></span><?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <p id="none" class="hidden text-center text-sm text-stone-500 py-6">Tidak ada laporan yang cocok dengan pencarian.</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="glass-panel p-7">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold"><?= $isAdmin ? 'Update Status Terbaru' : 'Perkembangan Laporan Saya' ?></h2>
                    <p class="text-stone-600"><?= $isAdmin ? 'Perubahan status terbaru di seluruh FTST' : 'Perubahan status pada laporan Anda' ?></p>
                </div>
            </div>

            <?php if (empty($statusUpdates)): ?>
                <div class="mt-6 rounded-2xl bg-white/60 p-6 text-center text-sm text-stone-600">
                    Belum ada perubahan status laporan.
                </div>
            <?php else: ?>
                <div class="mt-6 space-y-3">
                    <?php foreach ($statusUpdates as $update): ?>
                        <a href="<?= base_url('laporan/' . $update['report_id']) ?>" class="block rounded-2xl bg-white/75 hover:bg-white p-4 transition">
                            <div class="flex items-center justify-between gap-2 text-xs">
                                <span class="font-mono text-stone-500">SPT-<?= str_pad($update['report_id'], 3, '0', STR_PAD_LEFT) ?></span>
                                <span class="rounded-full px-2.5 py-1 font-bold <?= $statusChip[$update['status']] ?>"><?= esc($update['status']) ?></span>
                            </div>
                            <div class="mt-2 font-bold tight"><?= esc($update['facility_name']) ?></div>
                            <div class="mt-1 text-xs text-stone-500"><?= date('d M Y, H:i', strtotime($update['created_at'])) ?></div>
                            <?php if ($isAdmin): ?>
                                <div class="mt-1 text-xs text-stone-500">Pelapor: <?= esc($update['reporter_name']) ?></div>
                            <?php endif; ?>
                            <?php if ($update['notes']): ?>
                                <p class="mt-2 text-sm text-stone-600 break-words"><?= esc($update['notes']) ?></p>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
(function () {
    var chartColors = <?= json_encode($chartColors, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var distributionLabels = <?= json_encode(array_keys($dist), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var distributionData = <?= json_encode(array_values($dist), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var monthLabels = <?= json_encode($monthLabels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var monthData = <?= json_encode($monthData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    var distributionCanvas = document.getElementById('distributionChart');
    var monthlyCanvas = document.getElementById('monthlyChart');

    if (window.Chart) {
        if (distributionCanvas) {
            new Chart(distributionCanvas, {
                type: 'doughnut',
                data: {
                    labels: distributionLabels,
                    datasets: [{
                        data: distributionData,
                        backgroundColor: distributionLabels.map(function (_, index) {
                            return chartColors[index % chartColors.length];
                        }),
                        borderColor: '#f5f5f5',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18 } },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ' ' + context.label + ': ' + context.raw + ' laporan';
                                }
                            }
                        }
                    }
                }
            });
        }

        if (monthlyCanvas) {
            new Chart(monthlyCanvas, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Laporan',
                        data: monthData,
                        borderColor: '#2fbf8f',
                        backgroundColor: 'rgba(47, 191, 143, 0.16)',
                        pointBackgroundColor: '#159a70',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: 'rgba(28, 25, 23, 0.08)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    var items = document.querySelectorAll('.item'), none = document.getElementById('none'),
        btn = document.getElementById('toggleAll'), q = document.getElementById('q'), all = false;
    function render() {
        var term = (q && q.value || '').trim().toLowerCase(), shown = 0;
        items.forEach(function (el) {
            var match = !term || el.dataset.q.indexOf(term) > -1;
            var hide = !match || (!term && !all && el.classList.contains('extra'));
            el.classList.toggle('hidden', hide); if (!hide) shown++;
        });
        if (none) none.classList.toggle('hidden', shown > 0 || !items.length);
    }
    if (btn) btn.addEventListener('click', function () {
        all = !all; btn.firstChild.nodeValue = all ? 'Ringkas ' : 'Lihat Semua ';
        render();
    });
    if (q) {
        q.addEventListener('input', render);
        q.form.addEventListener('submit', function (e) { e.preventDefault(); document.getElementById('daftar').scrollIntoView(); });
    }
    render();
})();
</script>
<?= $this->endSection() ?>
