<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\StatusLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model   = new ReportModel();
        $isAdmin = session()->get('role') === 'admin';

        $reports = $isAdmin
            ? $model->allWithReporter()
            : $model->forUser((int) session()->get('user_id'));

        $statusUpdates = (new StatusLogModel())->recentForDashboard(
            $isAdmin ? null : (int) session()->get('user_id')
        );

        $by = static fn (string $s): int => count(array_filter($reports, static fn ($r) => $r['status'] === $s));

        $total   = count($reports);
        $selesai = $by('Selesai');

        $thisKey = date('Y-m');
        $lastKey = date('Y-m', strtotime('first day of last month'));
        $thisM   = count(array_filter($reports, static fn ($r) => date('Y-m', strtotime($r['created_at'])) === $thisKey));
        $lastM   = count(array_filter($reports, static fn ($r) => date('Y-m', strtotime($r['created_at'])) === $lastKey));
        $delta   = $lastM > 0 ? sprintf('%+d%%', round((($thisM - $lastM) / $lastM) * 100)) : '+' . $thisM;

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $firstMonth = new \DateTimeImmutable('first day of this month');
        $monthly     = [];
        $monthLabels = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $firstMonth->modify(sprintf('-%d months', 11 - $i));
            $key   = $month->format('Y-m');
            $monthly[$key] = 0;
            $monthLabels[] = $monthNames[(int) $month->format('n') - 1] . ' ' . $month->format('Y');
        }

        foreach ($reports as $report) {
            $reportMonth = date('Y-m', strtotime($report['created_at']));
            if (isset($monthly[$reportMonth])) {
                $monthly[$reportMonth]++;
            }
        }

        // Distribusi: admin = per program studi, pelapor = per kategori
        $dist = [];
        foreach ($reports as $r) {
            $k        = $isAdmin ? ($r['prodi'] ?: 'Dosen / Staf') : $r['category'];
            $dist[$k] = ($dist[$k] ?? 0) + 1;
        }
        arsort($dist);

        return view('dashboard/index', [
            'reports'     => $reports,
            'statusUpdates' => $statusUpdates,
            'isAdmin'     => $isAdmin,
            'statTotal'   => $total,
            'statDikirim' => $by('Dikirim'),
            'statVerif'   => $by('Diverifikasi'),
            'statProses'  => $by('Diproses'),
            'statSelesai' => $selesai,
            'rate'        => $total ? round(($selesai / $total) * 100) : 0,
            'delta'       => $delta,
            'dist'        => $dist,
            'monthLabels' => $monthLabels,
            'monthData'   => array_values($monthly),
        ]);
    }
}
