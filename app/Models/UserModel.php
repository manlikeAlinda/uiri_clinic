<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'user_id';
    protected $returnType    = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'username',
        'password',
        'role',    // VARCHAR in your schema (e.g., 'Admin', 'Doctor', 'User')
        'status',  // e.g., 'Active', 'Inactive', 'Suspended'
    ];

    /**
     * Optional: expose known roles to your UI.
     */
    public function getAvailableRoles(): array
    {
        return ['Admin', 'Doctor', 'User'];
    }

    /**
     * Normalize role value from DB to a canonical form (title case).
     */
    public static function normalizeRole(?string $role): ?string
    {
        if ($role === null) return null;

        $r = strtolower(trim($role));
        return match ($r) {
            'admin'  => 'Admin',
            'doctor' => 'Doctor',
            'user'   => 'User',
            default  => ucfirst($r), // fallback; keeps unexpected values visible
        };
    }

    /**
     * Fetch profile for authentication:
     * - Pull role directly from users.role (NO roles table join).
     * - LEFT JOIN doctors to get doctor_id mapping if it exists.
     * - Returns: user_id, username, password, role, status, doctor_id
     */
    public function getAuthProfileByUsername(string $username): ?array
    {
        $row = $this->db->table('users u')
            ->select('u.user_id, u.username, u.password, u.role, u.status, d.doctor_id')
            ->join('doctors d', 'd.user_id = u.user_id', 'left')
            ->where('u.username', $username)
            ->get()
            ->getRowArray();

        if (!$row) {
            return null;
        }

        // Normalize role for consistency throughout the app
        $row['role'] = self::normalizeRole($row['role'] ?? null);
        return $row;
    }

    /**
     * Optional helpers you may find useful elsewhere.
     */
    public static function isActive(?array $user): bool
    {
        if (!$user) return false;
        // Treat empty status as active if your schema allows NULLs; adjust as needed.
        $status = strtolower((string) ($user['status'] ?? 'Active'));
        return in_array($status, ['active', '1', 'enabled', ''], true);
    }

    public static function isDoctor(?array $user): bool
    {
        return ($user['role'] ?? null) === 'Doctor';
    }

    public static function isAdmin(?array $user): bool
    {
        return ($user['role'] ?? null) === 'Admin';
    }
}
