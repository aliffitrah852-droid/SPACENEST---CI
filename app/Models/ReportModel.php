<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    public const STATUSES   = ['Dikirim', 'Diverifikasi', 'Diproses', 'Selesai'];
    public const CATEGORIES = ['Hardware/Lab', 'Jaringan/Internet', 'Mebel/Ruangan', 'Lainnya'];
    public const PRIORITIES = ['Rendah', 'Sedang', 'Tinggi'];

    protected $table         = 'reports';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'facility_name', 'category', 'priority',
        'location', 'description', 'image', 'status',
    ];
    protected $useTimestamps = false;

    /** Semua laporan beserta data pelapor (untuk admin). */
    public function allWithReporter(): array
    {
        return $this->select('reports.*, users.name AS reporter_name, users.prodi')
            ->join('users', 'users.id = reports.user_id')
            ->orderBy('reports.created_at', 'DESC')
            ->findAll();
    }

    /** Laporan milik satu pengguna. */
    public function forUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /** Satu laporan + data pelapor. */
    public function findWithReporter(int $id): ?array
    {
        return $this->select('reports.*, users.name AS reporter_name, users.email')
            ->join('users', 'users.id = reports.user_id')
            ->where('reports.id', $id)
            ->first();
    }
}
