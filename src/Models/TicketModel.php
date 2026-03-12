<?php

namespace App\Models;

use App\Core\Model;

class TicketModel extends Model
{
    protected $table = 'models';

    public function create($name, $board_type, $cpu, $cpu_other, $ram_capacity, $ram_other, $ram_type, $disc_capacity, $disc_other, $disc_type, $gpu, $gpu_other, $gpu_type, $wifi, $bluetooth, $sn, $sn_other, $observaciones)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, board_type, cpu, cpu_other, ram_capacity, ram_other, ram_type, disc_capacity, disc_other, disc_type, gpu, gpu_other, gpu_type, wifi, bluetooth, sn, sn_other, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $board_type, $cpu, $cpu_other, $ram_capacity, $ram_other, $ram_type, $disc_capacity, $disc_other, $disc_type, $gpu, $gpu_other, $gpu_type, $wifi, $bluetooth, $sn, $sn_other, $observaciones]);
    }

    public function updateName($id, $newName)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ? WHERE id = ?");
        return $stmt->execute([$newName, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
