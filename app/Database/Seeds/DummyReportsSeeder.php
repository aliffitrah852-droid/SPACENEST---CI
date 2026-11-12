<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use RuntimeException;

class DummyReportsSeeder extends Seeder
{
    public function run()
    {
        $marker = '[DUMMY-SPACENEST]';
        $existing = $this->db->table('reports')
            ->like('description', $marker, 'after')
            ->countAllResults();

        if ($existing > 0) {
            CLI::write('Data dummy laporan sudah tersedia; tidak ada duplikasi yang ditambahkan.');

            return;
        }

        $reporters = $this->db->table('users')
            ->select('id')
            ->where('role', 'pelapor')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if ($reporters === []) {
            throw new RuntimeException('Tambahkan setidaknya satu akun pelapor sebelum menjalankan DummyReportsSeeder.');
        }

        $reports = [
            ['Proyektor Lab Informatika', 'Hardware/Lab', 'Tinggi', 'Lab Komputer 1', 'Proyektor sering mati setelah digunakan beberapa menit.', 'Dikirim'],
            ['Access point Gedung A', 'Jaringan/Internet', 'Tinggi', 'Lantai 2 Gedung A', 'Koneksi Wi-Fi terputus-putus di beberapa ruang kelas.', 'Diverifikasi'],
            ['Kursi Laboratorium 2', 'Mebel/Ruangan', 'Sedang', 'Lab Komputer 2', 'Sandaran kursi longgar dan perlu diperbaiki.', 'Diproses'],
            ['Komputer workstation 04', 'Hardware/Lab', 'Tinggi', 'Lab Desain', 'Komputer tidak dapat menyala saat tombol daya ditekan.', 'Selesai'],
            ['Koneksi jaringan ruang dosen', 'Jaringan/Internet', 'Sedang', 'Ruang Dosen FTST', 'Koneksi kabel jaringan tidak terdeteksi pada beberapa meja.', 'Dikirim'],
            ['Lampu ruang kelas 3A', 'Mebel/Ruangan', 'Rendah', 'Gedung B Ruang 3A', 'Satu lampu di bagian belakang ruangan tidak menyala.', 'Diverifikasi'],
            ['Printer administrasi', 'Hardware/Lab', 'Sedang', 'Ruang Administrasi', 'Hasil cetak bergaris dan tinta tidak keluar merata.', 'Diproses'],
            ['Saklar lampu koridor', 'Lainnya', 'Rendah', 'Koridor Gedung A', 'Saklar lampu koridor sulit digunakan.', 'Selesai'],
            ['Switch jaringan Lab Elektro', 'Jaringan/Internet', 'Tinggi', 'Lab Elektro', 'Beberapa port switch tidak memberikan koneksi jaringan.', 'Dikirim'],
            ['Meja praktikum 5', 'Mebel/Ruangan', 'Sedang', 'Lab Mesin', 'Permukaan meja praktikum bergoyang saat digunakan.', 'Diverifikasi'],
            ['Monitor komputer 12', 'Hardware/Lab', 'Sedang', 'Lab Komputer 1', 'Monitor menampilkan layar berkedip secara berkala.', 'Diproses'],
            ['Proyektor ruang kuliah 2B', 'Hardware/Lab', 'Tinggi', 'Gedung B Ruang 2B', 'Tampilan proyektor terlihat buram meskipun sudah difokuskan.', 'Selesai'],
            ['Wi-Fi ruang baca', 'Jaringan/Internet', 'Rendah', 'Ruang Baca FTST', 'Sinyal Wi-Fi lemah di sisi dekat jendela.', 'Dikirim'],
            ['Pintu ruang kelas 1C', 'Mebel/Ruangan', 'Sedang', 'Gedung A Ruang 1C', 'Gagang pintu terasa longgar dan sulit dikunci.', 'Diverifikasi'],
            ['Keyboard komputer 07', 'Hardware/Lab', 'Rendah', 'Lab Komputer 2', 'Beberapa tombol keyboard tidak merespons.', 'Diproses'],
            ['Kabel jaringan ruang rapat', 'Jaringan/Internet', 'Sedang', 'Ruang Rapat FTST', 'Kabel jaringan pada meja utama tidak tersambung.', 'Selesai'],
            ['AC ruang kelas 2A', 'Lainnya', 'Tinggi', 'Gedung A Ruang 2A', 'Pendingin ruangan tidak mengeluarkan udara dingin.', 'Dikirim'],
            ['Kabel daya osiloskop', 'Hardware/Lab', 'Tinggi', 'Lab Elektro', 'Kabel daya alat terlihat terkelupas dan perlu diganti.', 'Diverifikasi'],
            ['Papan tulis ruang 3B', 'Mebel/Ruangan', 'Rendah', 'Gedung B Ruang 3B', 'Permukaan papan tulis sulit dibersihkan.', 'Diproses'],
            ['Koneksi internet ruang seminar', 'Jaringan/Internet', 'Sedang', 'Ruang Seminar FTST', 'Akses internet sering terputus saat ruangan penuh.', 'Selesai'],
        ];

        $statusHistory = [
            'Dikirim'      => ['Dikirim'],
            'Diverifikasi' => ['Dikirim', 'Diverifikasi'],
            'Diproses'     => ['Dikirim', 'Diverifikasi', 'Diproses'],
            'Selesai'      => ['Dikirim', 'Diverifikasi', 'Diproses', 'Selesai'],
        ];
        $statusNotes = [
            'Dikirim'      => 'Laporan dummy dibuat untuk pengujian.',
            'Diverifikasi' => 'Laporan dummy telah diverifikasi.',
            'Diproses'     => 'Penanganan teknis dummy sedang berjalan.',
            'Selesai'      => 'Perbaikan dummy telah selesai.',
        ];
        $today = new \DateTimeImmutable('today');

        $this->db->transStart();

        foreach ($reports as $index => [$facility, $category, $priority, $location, $description, $status]) {
            $createdAt = $today->modify(sprintf('-%d days', 320 - ($index * 16)));
            $this->db->table('reports')->insert([
                'user_id'       => (int) $reporters[$index % count($reporters)]['id'],
                'facility_name' => $facility,
                'category'      => $category,
                'priority'      => $priority,
                'location'      => $location,
                'description'   => $marker . ' ' . $description,
                'image'         => null,
                'status'        => $status,
                'created_at'    => $createdAt->format('Y-m-d H:i:s'),
            ]);

            $reportId = (int) $this->db->insertID();
            foreach ($statusHistory[$status] as $step => $historyStatus) {
                $this->db->table('status_logs')->insert([
                    'report_id'  => $reportId,
                    'status'     => $historyStatus,
                    'notes'      => $statusNotes[$historyStatus],
                    'created_at' => $createdAt->modify(sprintf('+%d days', $step))->format('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            throw new RuntimeException('Gagal menambahkan data dummy laporan.');
        }

        CLI::write(sprintf('Berhasil menambahkan %d laporan dummy beserta riwayat status.', count($reports)));
    }
}
