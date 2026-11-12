<?php

namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\StatusLogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Laporan extends BaseController
{
    /** Form buat laporan. */
    public function buat()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin')->with('error', 'Admin tidak dapat membuat laporan.');
        }

        return view('laporan/buat');
    }

    /** Simpan laporan baru (US-08, US-09, US-10). */
    public function simpan()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin')->with('error', 'Admin tidak dapat membuat laporan.');
        }

        $rules = [
            'facility_name' => 'required|max_length[150]',
            'category'      => 'required|in_list[' . implode(',', ReportModel::CATEGORIES) . ']',
            'priority'      => 'required|in_list[' . implode(',', ReportModel::PRIORITIES) . ']',
            'location'      => 'required|max_length[100]',
            'description'   => 'required',
        ];
        $messages = [
            'facility_name' => ['required' => 'Kolom Nama Fasilitas, Lokasi, dan Deskripsi wajib diisi!'],
            'location'      => ['required' => 'Kolom Nama Fasilitas, Lokasi, dan Deskripsi wajib diisi!'],
            'description'   => ['required' => 'Kolom Nama Fasilitas, Lokasi, dan Deskripsi wajib diisi!'],
        ];

        if (! $this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();

            return redirect()->back()->withInput()->with('error', reset($errors));
        }

        // Unggah foto (opsional) — divalidasi dari ISI berkas, bukan dari nama/ekstensi klien.
        $imageName = null;
        $file      = $this->request->getFile('image');

        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

            if (! $file->isValid() || $file->hasMoved()) {
                return redirect()->back()->withInput()->with('error', 'Foto gagal diunggah. Silakan coba lagi.');
            }
            if ($file->getSize() > 4 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran foto maksimal 4 MB.');
            }

            $mime = $file->getMimeType(); // dideteksi dari isi berkas (finfo)
            if (! isset($allowed[$mime]) || @getimagesize($file->getTempName()) === false) {
                return redirect()->back()->withInput()->with('error', 'Berkas harus berupa foto JPG, PNG, atau WEBP.');
            }

            // Nama & ekstensi ditentukan server, bukan dari klien.
            $imageName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
            $file->move(FCPATH . 'uploads', $imageName);
        }

        $reports = new ReportModel();
        $db      = db_connect();
        $db->transStart();

        $reportId = $reports->insert([
            'user_id'       => (int) session()->get('user_id'),
            'facility_name' => trim($this->request->getPost('facility_name')),
            'category'      => $this->request->getPost('category'),
            'priority'      => $this->request->getPost('priority'),
            'location'      => trim($this->request->getPost('location')),
            'description'   => trim($this->request->getPost('description')),
            'image'         => $imageName,
            'status'        => 'Dikirim',
        ], true);

        // Log status awal (US-10)
        (new StatusLogModel())->insert([
            'report_id' => $reportId,
            'status'    => 'Dikirim',
            'notes'     => 'Laporan berhasil dibuat oleh pelapor.',
        ]);

        $db->transComplete();

        return redirect()->to('/')->with('success', 'Laporan terkirim. Admin akan memverifikasinya.');
    }

    /** Detail laporan + riwayat status (US-10). */
    public function detail(int $id)
    {
        $report = (new ReportModel())->findWithReporter($id);

        if (! $report) {
            throw PageNotFoundException::forPageNotFound('Laporan tidak ditemukan.');
        }

        $isAdmin = session()->get('role') === 'admin';
        if (! $isAdmin && (int) $report['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        return view('laporan/detail', [
            'report' => $report,
            'logs'   => (new StatusLogModel())->forReport($id),
        ]);
    }
}
