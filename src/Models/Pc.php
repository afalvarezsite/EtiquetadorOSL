<?php

namespace App\Models;

use App\Core\Model;

class Pc extends Model
{
    protected $table = 'pc';

    public function create($board_type, $cpu_name, $ram_capacity, $ram_type, $disc_capacity, $disc_type, $gpu_name, $gpu_type, $wifi, $bluetooth, $obser)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (board_type, cpu_name, ram_capacity, ram_type, disc_capacity, disc_type, gpu_name, gpu_type, wifi, bluetooth, obser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$board_type, $cpu_name, $ram_capacity, $ram_type, $disc_capacity, $disc_type, $gpu_name, $gpu_type, $wifi, $bluetooth, $obser]);
    }

    public function countWifi()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE wifi = 'true'");
        return $stmt->fetchColumn();
    }

    public function countBluetooth()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE bluetooth = 'true'");
        return $stmt->fetchColumn();
    }

    public function getTotalCount()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }

    public function getAllWithDetails()
    {
        $sql = "
            SELECT pc.*,
                   cpu.name  AS cpu_name_text,
                   ram.capacity  AS ram_capacity_text,
                   disc.capacity AS disc_capacity_text,
                   gpu.name  AS gpu_name_text,
                   sn.prefix AS sn_prefix,
                   sn.num    AS sn_num
            FROM {$this->table}
            LEFT JOIN cpu  ON pc.cpu_name  = cpu.id
            LEFT JOIN ram  ON pc.ram_capacity  = ram.id
            LEFT JOIN disc ON pc.disc_capacity = disc.id
            LEFT JOIN gpu  ON pc.gpu_name  = gpu.id
            LEFT JOIN sn_pc ON pc.id = sn_pc.pc_id
            LEFT JOIN sn   ON sn_pc.sn_id = sn.id
            ORDER BY pc.id
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isUsedInModel($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM model WHERE pc_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
