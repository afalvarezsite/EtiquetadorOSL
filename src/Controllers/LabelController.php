<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cpu;
use App\Models\Ram;
use App\Models\Disc;
use App\Models\Gpu;
use App\Models\Sn;

class LabelController extends Controller
{

    public function index()
    {
        $cpuModel = new Cpu();
        $ramModel = new Ram();
        $discModel = new Disc();
        $gpuModel = new Gpu();
        $snModel = new Sn();

        $data = [
            'cpus' => $cpuModel->getDistinctNames(),
            'rams' => $ramModel->getDistinctCapacities(),
            'discs' => $discModel->getDistinctCapacities(),
            'gpus' => $gpuModel->getDistinctNames(),
            'sns' => $snModel->getDistinctPrefixes()
        ];

        $this->render('label/generator', $data);
    }

    public function generatePdf()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('generator');
            return;
        }
        $this->verifyCsrfToken();

        // Obtener datos del formulario con valores por defecto
        $data = [
            'board_type' => $_POST['board_type'] ?? '',
            'cpu_name' => $_POST['cpu_name'] ?? '',
            'cpu_other_name' => $_POST['cpu_other_name'] ?? '',
            'ram_capacity' => $_POST['ram_capacity'] ?? '',
            'ram_other_capacity' => $_POST['ram_other_capacity'] ?? '',
            'ram_type' => $_POST['ram_type'] ?? '',
            'disc_capacity' => $_POST['disc_capacity'] ?? '',
            'disc_other_capacity' => $_POST['disc_other_capacity'] ?? '',
            'disc_type' => $_POST['disc_type'] ?? '',
            'gpu_name' => $_POST['gpu_name'] ?? '',
            'gpu_other_name' => $_POST['gpu_other_name'] ?? '',
            'gpu_type' => $_POST['gpu_type'] ?? '',
            'wifi' => $_POST['wifi'] ?? 'false',
            'bluetooth' => $_POST['bluetooth'] ?? 'false',
            'sn_prefix' => strtoupper($_POST['sn_prefix'] ?? ''),
            'sn_prefix_other' => strtoupper($_POST['sn_prefix_other'] ?? ''),
            'num_pag' => $_POST['num_pag'] ?? '',
            'checkbox_save' => $_POST['checkbox_save'] ?? '',
            'ticket_name' => $_POST['ticket_name'] ?? '',
            'observaciones' => $_POST['observaciones'] ?? ''
        ];

        $cpuModel = new Cpu();
        $ramModel = new Ram();
        $discModel = new Disc();
        $gpuModel = new Gpu();
        $pcModel = new \App\Models\Pc();
        $snModel = new Sn();
        $ticketModel = new \App\Models\TicketModel();

        // Mapeo de campos manuales a sus correspondientes Modelos
        $manualFields = [
            ['model' => $cpuModel, 'field' => 'cpu_other_name', 'target' => 'cpu_name', 'column' => 'name', 'idKey' => 'cpu_id'],
            ['model' => $ramModel, 'field' => 'ram_other_capacity', 'target' => 'ram_capacity', 'column' => 'capacity', 'idKey' => 'ram_id'],
            ['model' => $discModel, 'field' => 'disc_other_capacity', 'target' => 'disc_capacity', 'column' => 'capacity', 'idKey' => 'disc_id'],
            ['model' => $gpuModel, 'field' => 'gpu_other_name', 'target' => 'gpu_name', 'column' => 'name', 'idKey' => 'gpu_id']
        ];

        $ids = ['cpu_id' => null, 'ram_id' => null, 'disc_id' => null, 'gpu_id' => null];

        // Access Database instance for direct queries where Models don't have specific helpers yet
        $db = \App\Core\Database::getInstance()->getConnection();

        foreach ($manualFields as $info) {
            $table = strtolower(basename(str_replace('\\', '/', get_class($info['model']))));
            if (!empty($data[$info['field']])) {
                $data[$info['target']] = $data[$info['field']];
                $stmt = $db->prepare("INSERT INTO {$table} ({$info['column']}) VALUES (?)");
                $stmt->execute([$data[$info['target']]]);
                $ids[$info['idKey']] = $db->lastInsertId();
            } else {
                $stmt = $db->prepare("SELECT id FROM {$table} WHERE {$info['column']} = ? LIMIT 1");
                $stmt->execute([$data[$info['target']]]);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($row) {
                    $ids[$info['idKey']] = $row['id'];
                }
            }
        }

        // Priorizar prefijo manual
        if (!empty($data['sn_prefix_other'])) {
            $data['sn_prefix'] = $data['sn_prefix_other'];
        }

        $prefix = $data['sn_prefix'];
        $num_pag = (int) $data['num_pag'];
        $clean = "true";
        $total_pages = $num_pag < 2 ? 1 : $num_pag;
        $is_single = $num_pag < 2 ? "true" : "false";

        // Preparar carpeta pdf/raid if needed
        $publicPdfPath = __DIR__ . '/../../public/pdf';
        if (!is_dir($publicPdfPath . '/raid')) {
            mkdir($publicPdfPath . '/raid', 0777, true);
        }

        for ($i = 1; $i <= $num_pag; $i++) {
            $is_last = ($i === $total_pages);
            $end = $is_last ? "true" : "false";

            // NOTE: pdfgenerator.py runs with CWD /var/www/html/public so paths must be absolute or relative to public/
            $name_relative = $is_single == "true" ? "pdf/generado.pdf" : "pdf/raid/generado{$i}.pdf";

            // Get last SN
            $stmt = $db->prepare("SELECT MAX(num) AS last_num FROM sn WHERE prefix = ?");
            $stmt->execute([$prefix]);
            $last_num = $stmt->fetch(\PDO::FETCH_ASSOC)['last_num'] ?? 0;
            $sn_num = $last_num + 1;

            // Insert SN
            $stmt = $db->prepare("INSERT INTO sn (prefix, num) VALUES (?, ?)");
            $stmt->execute([$prefix, $sn_num]);
            $new_sn_id = $db->lastInsertId();

            // Insert PC
            $stmt = $db->prepare("INSERT INTO pc (board_type, cpu_name, ram_capacity, ram_type, disc_capacity, disc_type, gpu_name, gpu_type, wifi, bluetooth, obser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['board_type'],
                $ids['cpu_id'],
                $ids['ram_id'],
                $data['ram_type'],
                $ids['disc_id'],
                $data['disc_type'],
                $ids['gpu_id'],
                $data['gpu_type'],
                $data['wifi'],
                $data['bluetooth'],
                $data['observaciones']
            ]);
            $new_pc_id = $db->lastInsertId();

            // Link SN → PC
            $stmt = $db->prepare("INSERT INTO sn_pc (sn_id, pc_id) VALUES (?, ?)");
            $stmt->execute([$new_sn_id, $new_pc_id]);

            $argsArray = [
                $data['board_type'],
                $data['cpu_name'],
                $data['ram_capacity'],
                $data['ram_type'],
                $data['disc_type'],
                $data['disc_capacity'],
                $data['gpu_name'],
                $data['gpu_type'],
                $data['wifi'],
                $data['bluetooth'],
                $prefix,
                $sn_num,
                $name_relative, // Path relative to public/
                $end,
                $is_single,
                $clean,
                $data['observaciones']
            ];

            $escaped = array_map('escapeshellarg', $argsArray);

            // The python script lives in /var/www/html/src/Scripts/pdfgenerator.py
            $scriptPath = __DIR__ . '/../Scripts/pdfgenerator.py';
            $command = "cd " . __DIR__ . "/../../public && /opt/venv/bin/python3 " . escapeshellarg($scriptPath) . " " . implode(' ', $escaped);
            shell_exec($command);

            $clean = "false";
        }

        if ($data['checkbox_save'] == 'True') {
            $stmt = $db->prepare("SELECT MAX(id) AS last_num FROM pc");
            $stmt->execute();
            $last_num = $stmt->fetch(\PDO::FETCH_ASSOC)['last_num'];

            $stmt = $db->prepare("INSERT INTO models (name, model) VALUES (?, ?)");
            $stmt->execute([$data['ticket_name'], $last_num]);
        }

        $this->redirect('generator');
    }

    public function getSavedModels()
    {
        header('Content-Type: application/json');
        $ticketModel = new \App\Models\TicketModel();
        // Uses raw query, should maybe define getAll in model later
        $stmt = \App\Core\Database::getInstance()->getConnection()->query("SELECT * FROM models");
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getModel()
    {
        header('Content-Type: application/json');
        if (!isset($_GET['modelId']))
            return;
        $modelId = $_GET['modelId'];

        $sql = "SELECT pc.id, pc.board_type, cpu.name AS cpu_name, pc.ram_type, ram.capacity AS ram_capacity, 
                       pc.disc_type, disc.capacity AS disc_capacity, gpu.name AS gpu_name, pc.gpu_type, 
                       pc.wifi, pc.bluetooth, pc.obser 
                FROM pc 
                LEFT JOIN cpu ON pc.cpu_name = cpu.id 
                LEFT JOIN ram ON pc.ram_capacity = ram.id 
                LEFT JOIN disc ON pc.disc_capacity = disc.id 
                LEFT JOIN gpu ON pc.gpu_name = gpu.id 
                WHERE pc.id = ?";

        $stmt = \App\Core\Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([$modelId]);
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function deleteModel()
    {
        if (isset($_GET['modelId'])) {
            $stmt = \App\Core\Database::getInstance()->getConnection()->prepare("DELETE FROM models WHERE id = ?");
            $stmt->execute([$_GET['modelId']]);
        }
    }

    public function editModelName()
    {
        if (isset($_GET['modelId']) && isset($_GET['modelName'])) {
            $ticketModel = new \App\Models\TicketModel();
            $ticketModel->updateName($_GET['modelId'], $_GET['modelName']);
        }
    }

    public function clearPreview()
    {
        $filePath = __DIR__ . '/../../public/pdf/generado.pdf';
        if (file_exists($filePath)) {
            unlink($filePath);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'File not found']);
        }
    }
}
