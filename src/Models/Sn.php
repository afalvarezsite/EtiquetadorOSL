<?php

namespace App\Models;

use App\Core\Model;

class Sn extends Model
{
    protected $table = 'sn';

    public function create($prefix)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (prefix) VALUES (?)");
        return $stmt->execute([$prefix]);
    }

    public function createWithNum($prefix, $num)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (prefix, num) VALUES (?, ?)");
        return $stmt->execute([$prefix, $num]);
    }

    public function updatePrefix($id, $prefix)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET prefix = ? WHERE id = ?");
        return $stmt->execute([$prefix, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getDistinctPrefixes()
    {
        $stmt = $this->db->query("SELECT MIN(id) AS id, prefix, MAX(num) AS num FROM {$this->table} GROUP BY prefix ORDER BY prefix ASC");
        return $stmt->fetchAll();
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
