<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    public function findByUsernameOrEmail($identifier)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre_rol as rol 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id_rol 
            WHERE u.email = ? OR u.username = ?
        ");
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch();
    }

    public function create($username, $email, $password, $role_id = 2) // Default to User role
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$username, $email, $password, $role_id]);
    }

    public function updateSecret2FA($id, $secret)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET secret_2fa = ? WHERE id = ?");
        return $stmt->execute([$secret, $id]);
    }

    public function getAllWithRoles()
    {
        $stmt = $this->db->query("
            SELECT u.*, r.nombre_rol as rol_name
            FROM {$this->table} u
            LEFT JOIN roles r ON u.role_id = r.id_rol
            ORDER BY u.username ASC
        ");
        return $stmt->fetchAll();
    }

    public function updatePassword($id, $hashedPassword)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function updateEmail($id, $email)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET email = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$email, $id]);
    }

    public function updateRole($id, $role_id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET role_id = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$role_id, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countUsers()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }

    public function getRoles()
    {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY id_rol ASC");
        return $stmt->fetchAll();
    }
}
