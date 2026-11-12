<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\StatusLogModel;

class Admin extends BaseController
{
    /** Panel manajemen laporan. */
    public function index()
    {
        return view('admin/dashboard', [
            'reports'  => (new ReportModel())->allWithReporter(),
            'statuses' => ReportModel::STATUSES,
        ]);
    }

    /** Perbarui status laporan + catat log (US-06, US-07, US-10). */
    public function updateStatus()
    {
        $rules = [
            'report_id' => 'required|is_natural_no_zero',
            'status'    => 'required|in_list[' . implode(',', ReportModel::STATUSES) . ']',
            'notes'     => 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin')->with('error', 'Data pembaruan status tidak valid.');
        }

        $reportId = (int) $this->request->getPost('report_id');
        $reports  = new ReportModel();

        if (! $reports->find($reportId)) {
            return redirect()->to('/admin')->with('error', 'Laporan tidak ditemukan.');
        }

        $status = $this->request->getPost('status');
        $db     = db_connect();
        $db->transStart();
        $reports->update($reportId, ['status' => $status]);
        (new StatusLogModel())->insert([
            'report_id' => $reportId,
            'status'    => $status,
            'notes'     => trim((string) $this->request->getPost('notes')),
        ]);
        $db->transComplete();

        return redirect()->to('/admin')->with('success', "Status laporan #{$reportId} diperbarui.");
    }
}
