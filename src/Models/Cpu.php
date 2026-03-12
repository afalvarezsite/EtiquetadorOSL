<?php

namespace App\Models;

use App\Core\Model;

class Cpu extends Model
{
    protected $table = 'cpu';

    public function create($name)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function getDistinctNames()
    {
        $stmt = $this->db->query("SELECT DISTINCT name FROM {$this->table} ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function searchByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE ? ORDER BY name ASC");
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll();
    }

    public function updateName($id, $name)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isUsedInPc($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM pc WHERE cpu_name = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function deleteAll()
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table}");
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }
}
