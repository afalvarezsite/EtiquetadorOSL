<?php

namespace App\Models;

use App\Core\Model;

class Ram extends Model
{
    protected $table = 'ram';

    public function create($capacity)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (capacity) VALUES (?)");
        return $stmt->execute([$capacity]);
    }

    public function getDistinctCapacities()
    {
        $stmt = $this->db->query("SELECT DISTINCT capacity FROM {$this->table} ORDER BY capacity");
        return $stmt->fetchAll();
    }
}
