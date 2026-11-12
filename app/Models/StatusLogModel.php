<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusLogModel extends Model
{
    protected $table         = 'status_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['report_id', 'status', 'notes'];
    protected $useTimestamps = false;

    public function forReport(int $reportId): array
    {
        return $this->where('report_id', $reportId)
            ->orderBy('created_at', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /** Perubahan status terbaru untuk dashboard admin atau satu pelapor. */
    public function recentForDashboard(?int $userId, int $limit = 5): array
    {
        $builder = $this->select(
            'status_logs.id, status_logs.report_id, status_logs.status, status_logs.notes, status_logs.created_at, reports.facility_name, users.name AS reporter_name'
        )
            ->join('reports', 'reports.id = status_logs.report_id')
            ->join('users', 'users.id = reports.user_id');

        if ($userId !== null) {
            $builder->where('reports.user_id', $userId);
        }

        return $builder
            ->orderBy('status_logs.created_at', 'DESC')
            ->orderBy('status_logs.id', 'DESC')
            ->findAll($limit);
    }
}
