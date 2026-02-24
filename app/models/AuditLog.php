<?php

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    private static $tableReady = false;

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    private function ensureTable()
    {
        if (self::$tableReady) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT DEFAULT NULL,
            username VARCHAR(50) DEFAULT NULL,
            full_name VARCHAR(100) DEFAULT NULL,
            role_name VARCHAR(50) DEFAULT NULL,
            action VARCHAR(50) NOT NULL,
            entity_type VARCHAR(50) DEFAULT NULL,
            entity_id INT DEFAULT NULL,
            reference_code VARCHAR(50) DEFAULT NULL,
            description VARCHAR(255) DEFAULT NULL,
            metadata TEXT,
            ip_address VARCHAR(45) DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_action (action),
            KEY idx_entity (entity_type, entity_id),
            KEY idx_user_id (user_id),
            KEY idx_reference_code (reference_code),
            KEY idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->execute($sql);
        self::$tableReady = true;
    }

    public function log($data)
    {
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $data['metadata'] = json_encode($data['metadata'], JSON_UNESCAPED_UNICODE);
        }

        return $this->create($data);
    }

    public function search($filters = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['start_date'])) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $filters['end_date'];
        }

        if (!empty($filters['action'])) {
            $sql .= " AND action = ?";
            $params[] = $filters['action'];
        }

        if (!empty($filters['entity_type'])) {
            $sql .= " AND entity_type = ?";
            $params[] = $filters['entity_type'];
        }

        if (!empty($filters['keyword'])) {
            $sql .= " AND (reference_code LIKE ? OR description LIKE ? OR username LIKE ? OR full_name LIKE ?)";
            $keyword = '%' . $filters['keyword'] . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY id DESC";

        $limit = !empty($filters['limit']) ? (int)$filters['limit'] : 200;
        $sql .= " LIMIT ?";
        $params[] = $limit;

        return $this->query($sql, $params);
    }

    public function getStats($startDate = null, $endDate = null)
    {
        $sql = "SELECT 
                    COUNT(*) as total_logs,
                    SUM(CASE WHEN action LIKE 'create_%' THEN 1 ELSE 0 END) as create_actions,
                    SUM(CASE WHEN action LIKE 'approve_%' THEN 1 ELSE 0 END) as approve_actions,
                    SUM(CASE WHEN action LIKE 'reject_%' THEN 1 ELSE 0 END) as reject_actions,
                    COUNT(DISTINCT user_id) as active_users
                FROM {$this->table}
                WHERE 1=1";
        $params = [];

        if ($startDate) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $endDate;
        }

        $result = $this->query($sql, $params);
        return $result ? $result[0] : null;
    }
}
