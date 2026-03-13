<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cpu;
use App\Models\Gpu;
use App\Models\Pc;
use App\Models\TicketModel;

class AdminController extends Controller
{

    public function index()
    {
        $cpuModel = new Cpu();
        $gpuModel = new Gpu();
        $pcModel = new Pc();
        $ticketModel = new TicketModel();

        $data = [
            'cpuCount' => count($cpuModel->getAll()),
            'gpuCount' => count($gpuModel->getAll()),
            'pcCount' => $pcModel->getTotalCount(),
            'wifiCount' => $pcModel->countWifi(),
            'bluetoothCount' => $pcModel->countBluetooth(),
            'biosCount' => $pcModel->countBios(),
            'uefiCount' => $pcModel->countUefi(),
            'modelsCount' => count($ticketModel->getAll())
        ];

        $this->render('admin/dashboard', $data);
    }

    public function cpu()
    {
        $cpuModel = new \App\Models\Cpu();
        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $cpuId = (int) $_GET['delete'];
            if ($cpuModel->isUsedInPc($cpuId)) {
                $errorMessage = "No se puede eliminar: Hay PCs usando esta CPU.";
            } else {
                if ($cpuModel->delete($cpuId)) {
                    $this->redirect('admin/cpu?msg=deleted');
                } else {
                    $errorMessage = "Error al eliminar CPU.";
                }
            }
        }

        // Handle Add, Edit, Search, Import
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $name = trim($_POST['name']);
                if (!empty($name)) {
                    if ($cpuModel->create($name)) {
                        $this->redirect('admin/cpu?msg=added');
                    } else {
                        $errorMessage = "Error al añadir CPU.";
                    }
                } else {
                    $errorMessage = "El nombre de la CPU no puede estar vacío.";
                }
            } elseif (isset($_POST['edit'])) {
                $id = (int) $_POST['id'];
                $name = trim($_POST['name']);
                if (!empty($name) && $id > 0) {
                    if ($cpuModel->updateName($id, $name)) {
                        $this->redirect('admin/cpu?msg=edited');
                    } else {
                        $errorMessage = "Error al editar CPU.";
                    }
                } else {
                    $errorMessage = "El nombre de la CPU no puede estar vacío.";
                }
            } elseif (isset($_POST['search'])) {
                $txt = urlencode($_POST['pattern']);
                $this->redirect("admin/cpu?search=$txt");
            } elseif (isset($_POST['import'])) {
                if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['import_file']['tmp_name'];

                    // <-- VALIDACIÓN MIME SEGURA (Path Traversal mitigado)
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file);
                    finfo_close($finfo);

                    $addedCount = 0;
                    $skippedCount = 0;
                    $csvMimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];

                    if (in_array($mime, $csvMimes)) {
                        if (($handle = fopen($file, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $name = trim($data[0]);
                                if (!empty($name)) {
                                    if (!$cpuModel->findByName($name)) {
                                        $cpuModel->create($name);
                                        $addedCount++;
                                    } else {
                                        $skippedCount++;
                                    }
                                }
                            }
                            fclose($handle);
                        }
                    } elseif ($mime === 'text/plain') {
                        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lines as $line) {
                            $name = trim($line);
                            if (!empty($name)) {
                                if (!$cpuModel->findByName($name)) {
                                    $cpuModel->create($name);
                                    $addedCount++;
                                } else {
                                    $skippedCount++;
                                }
                            }
                        }
                    } else {
                        $errorMessage = "Tipo de archivo no permitido. Solo se admiten archivos CSV o TXT.";
                    }

                    if (empty($errorMessage)) {
                        $this->redirect("admin/cpu?msg=imported&added=$addedCount&skipped=$skippedCount");
                    }
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($cpuModel->deleteAll()) {
                    $this->redirect('admin/cpu?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar todas las entradas. Puede que haya dependencias (PCs asociadas).";
                }
            }
        }

        // Handle Messages
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "CPU eliminada correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "CPU añadida correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "CPU actualizada correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todas las CPUs han sido eliminadas.";
            if ($_GET['msg'] === 'imported') {
                $added = $_GET['added'] ?? 0;
                $skipped = $_GET['skipped'] ?? 0;
                $successMessage = "Importación finalizada: $added añadidas, $skipped omitidas (duplicadas).";
            }
        }

        // Fetch Data
        if (isset($_GET['search'])) {
            $txt = $_GET['search'];
            $cpus = $cpuModel->searchByName($txt);
        } else {
            $cpus = $cpuModel->getAll();
        }

        $this->render('admin/cpu', [
            'cpus' => $cpus,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function gpu()
    {
        $gpuModel = new \App\Models\Gpu();
        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $gpuId = (int) $_GET['delete'];
            if ($gpuModel->isUsedInPc($gpuId)) {
                $errorMessage = "No se puede eliminar: Hay PCs usando esta GPU.";
            } else {
                if ($gpuModel->delete($gpuId)) {
                    $this->redirect('admin/gpu?msg=deleted');
                } else {
                    $errorMessage = "Error al eliminar GPU.";
                }
            }
        }

        // Handle Add, Edit, Search, Import
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $name = trim($_POST['name']);
                if (!empty($name)) {
                    if ($gpuModel->create($name)) {
                        $this->redirect('admin/gpu?msg=added');
                    } else {
                        $errorMessage = "Error al añadir GPU.";
                    }
                } else {
                    $errorMessage = "El nombre de la GPU no puede estar vacío.";
                }
            } elseif (isset($_POST['edit'])) {
                $id = (int) $_POST['id'];
                $name = trim($_POST['name']);
                if (!empty($name) && $id > 0) {
                    if ($gpuModel->updateName($id, $name)) {
                        $this->redirect('admin/gpu?msg=edited');
                    } else {
                        $errorMessage = "Error al editar GPU.";
                    }
                } else {
                    $errorMessage = "El nombre de la GPU no puede estar vacío.";
                }
            } elseif (isset($_POST['search'])) {
                $txt = urlencode($_POST['pattern']);
                $this->redirect("admin/gpu?search=$txt");
            } elseif (isset($_POST['import'])) {
                if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['import_file']['tmp_name'];

                    // <-- VALIDACIÓN MIME SEGURA
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file);
                    finfo_close($finfo);

                    $addedCount = 0;
                    $skippedCount = 0;
                    $csvMimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];

                    if (in_array($mime, $csvMimes)) {
                        if (($handle = fopen($file, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                $name = trim($data[0]);
                                if (!empty($name)) {
                                    if (!$gpuModel->findByName($name)) {
                                        $gpuModel->create($name);
                                        $addedCount++;
                                    } else {
                                        $skippedCount++;
                                    }
                                }
                            }
                            fclose($handle);
                        }
                    } elseif ($mime === 'text/plain') {
                        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lines as $line) {
                            $name = trim($line);
                            if (!empty($name)) {
                                if (!$gpuModel->findByName($name)) {
                                    $gpuModel->create($name);
                                    $addedCount++;
                                } else {
                                    $skippedCount++;
                                }
                            }
                        }
                    } else {
                        $errorMessage = "Tipo de archivo no permitido. Solo se admiten archivos CSV o TXT.";
                    }

                    if (empty($errorMessage)) {
                        $this->redirect("admin/gpu?msg=imported&added=$addedCount&skipped=$skippedCount");
                    }
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($gpuModel->deleteAll()) {
                    $this->redirect('admin/gpu?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar todas las entradas. Puede que haya dependencias (PCs asociadas).";
                }
            }
        }

        // Handle Messages
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "GPU eliminada correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "GPU añadida correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "GPU actualizada correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todas las GPUs han sido eliminadas.";
            if ($_GET['msg'] === 'imported') {
                $added = $_GET['added'] ?? 0;
                $skipped = $_GET['skipped'] ?? 0;
                $successMessage = "Importación finalizada: $added añadidas, $skipped omitidas (duplicadas).";
            }
        }

        // Fetch Data
        if (isset($_GET['search'])) {
            $txt = $_GET['search'];
            $gpus = $gpuModel->searchByName($txt);
        } else {
            $gpus = $gpuModel->getAll();
        }

        $this->render('admin/gpu', [
            'gpus' => $gpus,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function pc()
    {
        $pcModel = new \App\Models\Pc();
        $cpuModel = new \App\Models\Cpu();
        $ramModel = new \App\Models\Ram();
        $discModel = new \App\Models\Disc();
        $gpuModel = new \App\Models\Gpu();

        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $pcId = (int) $_GET['delete'];
            if ($pcModel->isUsedInModel($pcId)) {
                $errorMessage = "No se puede eliminar: Hay tickets usando esta PC.";
            } else {
                if ($pcModel->delete($pcId)) {
                    $this->redirect('admin/pc?msg=deleted');
                } else {
                    $errorMessage = "Error al eliminar PC.";
                }
            }
        }

        // Handle Add and DeleteAll
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $board_type = $_POST['board_type'];
                $cpu_name = $_POST['cpu_name'];
                $ram_capacity = $_POST['ram_capacity'];
                $ram_type = $_POST['ram_type'];
                $disc_capacity = $_POST['disc_capacity'];
                $disc_type = $_POST['disc_type'];
                $gpu_name = !empty($_POST['gpu_name']) ? $_POST['gpu_name'] : null;
                $gpu_type = $_POST['gpu_type'];
                $wifi = $_POST['wifi'];
                $bluetooth = $_POST['bluetooth'];
                $obser = $_POST['obser'];
                $description = isset($_POST['description']) ? $_POST['description'] : '';

                $full_obser = $obser . (!empty($description) ? "\n" . $description : "");

                if ($pcModel->create($board_type, $cpu_name, $ram_capacity, $ram_type, $disc_capacity, $disc_type, $gpu_name, $gpu_type, $wifi, $bluetooth, $obser)) {
                    $this->redirect('admin/pc?msg=added');
                } else {
                    $errorMessage = "Error al añadir PC.";
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($pcModel->deleteAll()) {
                    $this->redirect('admin/pc?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar todas las PCs.";
                }
            }
        }

        // Handle Messages
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "PC eliminada correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "PC añadida correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todas las PCs han sido eliminadas.";
        }

        // Fetch Data
        try {
            $cpus = $cpuModel->getAll();
            $rams = $ramModel->getAll();
            $discs = $discModel->getAll();
            $gpus = $gpuModel->getAll();
            $pcs = $pcModel->getAllWithDetails();
        } catch (\Exception $e) {
            // <-- MITIGACIÓN DE FUGA DE INFORMACIÓN (Logs seguros)
            error_log("Error BD al cargar componentes PC: " . $e->getMessage());
            $errorMessage = "Ocurrió un error interno al cargar los componentes. Por favor, intente de nuevo.";
            $pcs = [];
        }

        $this->render('admin/pc', [
            'pcs' => $pcs,
            'cpus' => $cpus,
            'rams' => $rams,
            'discs' => $discs,
            'gpus' => $gpus,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function sn()
    {
        $snModel = new \App\Models\Sn();
        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $snId = (int) $_GET['delete'];
            if ($snModel->delete($snId)) {
                $this->redirect('admin/sn?msg=deleted');
            } else {
                $errorMessage = "Error al eliminar prefijo SN.";
            }
        }

        // Handle Add & Edit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $prefix = trim($_POST['prefix']);
                if (!empty($prefix)) {
                    if ($snModel->createWithNum($prefix, 0)) {
                        $this->redirect('admin/sn?msg=added');
                    } else {
                        $errorMessage = "Error al añadir prefijo SN.";
                    }
                }
            } elseif (isset($_POST['edit'])) {
                $id = (int) $_POST['id'];
                $prefix = trim($_POST['prefix']);
                if (!empty($prefix) && $id > 0) {
                    if ($snModel->updatePrefix($id, $prefix)) {
                        $this->redirect('admin/sn?msg=edited');
                    } else {
                        $errorMessage = "Error al editar prefijo SN.";
                    }
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($snModel->deleteAll()) {
                    $this->redirect('admin/sn?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar todos los perfiles SN.";
                }
            }
        }

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "Prefijo SN eliminado correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "Prefijo SN añadido correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "Prefijo SN editado correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todos los prefijos SN han sido eliminados.";
        }

        $sns = $snModel->getAll();

        $this->render('admin/sn', [
            'sns' => $sns,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function models()
    {
        $model = new \App\Models\TicketModel();
        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $id = (int) $_GET['delete'];
            if ($model->delete($id)) {
                $this->redirect('admin/models?msg=deleted');
            } else {
                $errorMessage = "Error al eliminar modelo.";
            }
        }

        // Handle Add, Edit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $name = trim($_POST['name']);
                if (!empty($name)) {
                    if ($model->create($name, '', '', '', '', '', '', '', '', '', '', '', '', 'false', 'false', '', '', '')) {
                        $this->redirect('admin/models?msg=added');
                    } else {
                        $errorMessage = "Error al añadir modelo.";
                    }
                } else {
                    $errorMessage = "El nombre del modelo no puede estar vacío.";
                }
            } elseif (isset($_POST['edit'])) {
                $id = (int) $_POST['id'];
                $name = trim($_POST['name']);
                if (!empty($name) && $id > 0) {
                    if ($model->updateName($id, $name)) {
                        $this->redirect('admin/models?msg=edited');
                    } else {
                        $errorMessage = "Error al editar modelo.";
                    }
                } else {
                    $errorMessage = "El nombre del modelo no puede estar vacío.";
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($model->deleteAll()) {
                    $this->redirect('admin/models?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar todos los modelos.";
                }
            }
        }

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "Modelo eliminado correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "Modelo añadido correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "Modelo actualizado correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todos los modelos han sido eliminados.";
        }

        try {
            $models = $model->getAll();
        } catch (\Exception $e) {
            // <-- MITIGACIÓN DE FUGA DE INFORMACIÓN
            error_log("Error BD al cargar modelos: " . $e->getMessage());
            $errorMessage = "Error interno al cargar la lista de modelos.";
            $models = [];
        }

        $this->render('admin/models', [
            'models' => $models,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function users()
    {
        $userModel = new \App\Models\User();
        $successMessage = '';
        $errorMessage = '';

        // Handle Delete
        if (isset($_GET['delete'])) {
            $userId = (int) $_GET['delete'];
            if ($userModel->countUsers() <= 1) {
                $errorMessage = "No se puede eliminar el último usuario.";
            } else {
                if ($userModel->delete($userId)) {
                    $this->redirect('admin/users?msg=deleted');
                } else {
                    $errorMessage = "Error al eliminar usuario.";
                }
            }
        }

        // Handle POST actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken(); // <-- PROTECCIÓN CSRF APLICADA

            if (isset($_POST['add'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $email = trim($_POST['email']);

                if (empty($username) || empty($password) || empty($email)) {
                    $errorMessage = "Por favor, complete todos los campos.";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    if ($userModel->create($username, $email, $passwordHash, 2)) {
                        $this->redirect('admin/users?msg=added');
                    } else {
                        $errorMessage = "Error al añadir usuario.";
                    }
                }
            } elseif (isset($_POST['newpass'])) {
                $userId = (int) $_POST['userId'];
                $password = $_POST['password'];
                if (empty($password)) {
                    $errorMessage = "La contraseña no puede estar vacía.";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    if ($userModel->updatePassword($userId, $passwordHash)) {
                        $this->redirect('admin/users?msg=pass_updated');
                    } else {
                        $errorMessage = "Error al cambiar la contraseña.";
                    }
                }
            } elseif (isset($_POST['newemail'])) {
                $userId = (int) $_POST['userId'];
                $email = trim($_POST['email']);
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorMessage = "Por favor, ingrese un correo válido.";
                } else {
                    if ($userModel->updateEmail($userId, $email)) {
                        $this->redirect('admin/users?msg=email_updated');
                    } else {
                        $errorMessage = "Error al cambiar el correo electrónico.";
                    }
                }
            } elseif (isset($_POST['updaterole'])) {
                $userId = (int) $_POST['userId'];
                $roleId = (int) $_POST['role_id'];
                if ($userModel->updateRole($userId, $roleId)) {
                    $this->redirect('admin/users?msg=role_updated');
                } else {
                    $errorMessage = "Error al cambiar el rol.";
                }
            } elseif (isset($_POST['deleteAll'])) {
                if ($userModel->deleteAll($_SESSION['user_id'] ?? 0)) {
                    $this->redirect('admin/users?msg=all_deleted');
                } else {
                    $errorMessage = "Error al eliminar usuarios.";
                }
            }
        }

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "Usuario eliminado correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "Usuario añadido correctamente.";
            if ($_GET['msg'] === 'pass_updated')
                $successMessage = "Contraseña actualizada correctamente.";
            if ($_GET['msg'] === 'email_updated')
                $successMessage = "Correo electrónico actualizado correctamente.";
            if ($_GET['msg'] === 'role_updated')
                $successMessage = "Rol actualizado correctamente.";
            if ($_GET['msg'] === 'all_deleted')
                $successMessage = "Todos los usuarios (excepto tú) han sido eliminados.";
        }

        try {
            $users = $userModel->getAllWithRoles();
            $roles = $userModel->getRoles();
        } catch (\Exception $e) {
            // <-- MITIGACIÓN DE FUGA DE INFORMACIÓN
            error_log("Error BD al cargar usuarios: " . $e->getMessage());
            $errorMessage = "Error interno al cargar la lista de usuarios.";
            $users = [];
            $roles = [];
        }

        $this->render('admin/users', [
            'users' => $users,
            'roles' => $roles,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);
    }

    public function stats()
    {
        $this->render('admin/stats');
    }

    public function statsJson()
    {
        header('Content-Type: application/json');

        $db = \App\Core\Database::getInstance()->getConnection();
        $stat = $_GET['stat'] ?? 'cpu';

        try {
            switch ($stat) {
                case 'cpu':
                    $query = "
                        SELECT cpu.name AS label, COUNT(pc.id) AS value
                        FROM pc
                        JOIN cpu ON pc.cpu_name = cpu.id
                        GROUP BY cpu.name
                        ORDER BY value DESC
                    ";
                    break;
                case 'ram':
                    $query = "
                        SELECT CONCAT(ram.capacity, 'GB ', pc.ram_type) AS label, COUNT(pc.id) AS value
                        FROM pc
                        JOIN ram ON pc.ram_capacity = ram.id
                        GROUP BY ram.capacity, pc.ram_type
                        ORDER BY ram.capacity
                    ";
                    break;
                case 'disc':
                    $query = "
                        SELECT CONCAT(disc.capacity, 'GB ', pc.disc_type) AS label, COUNT(pc.id) AS value
                        FROM pc
                        JOIN disc ON pc.disc_capacity = disc.id
                        GROUP BY disc.capacity, pc.disc_type
                        ORDER BY disc.capacity
                    ";
                    break;
                case 'gpu':
                    $query = "
                        SELECT
                            CASE
                                WHEN pc.gpu_name IS NULL THEN 'Integrada'
                                ELSE CONCAT(gpu.name, ' (', pc.gpu_type, ')')
                            END AS label,
                            COUNT(pc.id) AS value
                        FROM pc
                        LEFT JOIN gpu ON pc.gpu_name = gpu.id
                        GROUP BY pc.gpu_name, pc.gpu_type, gpu.name
                        ORDER BY value DESC
                    ";
                    break;
                case 'wifi':
                    $query = "
                        SELECT
                            CASE
                                WHEN wifi = 'true' THEN 'Con WiFi'
                                ELSE 'Sin WiFi'
                            END AS label,
                            COUNT(id) AS value
                        FROM pc
                        GROUP BY wifi
                        ORDER BY value DESC
                    ";
                    break;
                case 'bluetooth':
                    $query = "
                        SELECT
                            CASE
                                WHEN bluetooth = 'true' THEN 'Con Bluetooth'
                                ELSE 'Sin Bluetooth'
                            END AS label,
                            COUNT(id) AS value
                        FROM pc
                        GROUP BY bluetooth
                        ORDER BY value DESC
                    ";
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Tipo de estadística no válido']);
                    exit;
            }

            $stmt = $db->query($query);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'labels' => array_column($results, 'label'),
                'values' => array_column($results, 'value')
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            // <-- MITIGACIÓN DE FUGA DE INFORMACIÓN EN API
            error_log("Error BD en statsJson: " . $e->getMessage());
            echo json_encode(['error' => 'Error interno procesando las estadísticas.']);
        }
        exit;
    }
}