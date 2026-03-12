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
            'modelsCount' => count($ticketModel->getAll())
        ];

        $this->render('admin/dashboard', $data);
    }

    // Otras vistas de admin (cpu, gpu, etc.) se pueden mapear de igual forma iterativamente
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

        // Handle Add, Edit, Search
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Handle Add, Edit, Search
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Models for selects
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

        // Handle Add
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
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
            // Notice: description is now part of obser in the db schema, or left out if not in schema. Taking from pc.php:
            // $description = $_POST['description']; // In legacy pc.php it was passed but the db might not support it. We'll pass it to obser or ignore.
            $description = isset($_POST['description']) ? $_POST['description'] : '';

            // Using the existing create method: create($board_type, $cpu, $ram_capacity, $ram_type, $disc_capacity, $disc_type, $gpu, $gpu_type, $wifi, $bluetooth, $sn, $observaciones)
            // SN is not generated at PC creation time, it's done during label generation or linked separately. Passing null or 0 for SN.
            // Description might be mapped to 'observaciones' based on PC model definition.
            $full_obser = $obser . (!empty($description) ? "\n" . $description : "");

            if ($pcModel->create($board_type, $cpu_name, $ram_capacity, $ram_type, $disc_capacity, $disc_type, $gpu_name, $gpu_type, $wifi, $bluetooth, $obser)) {
                $this->redirect('admin/pc?msg=added');
            } else {
                $errorMessage = "Error al añadir PC.";
            }
        }

        // Handle Messages
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "PC eliminada correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "PC añadida correctamente.";
        }

        // Fetch Data
        try {
            $cpus = $cpuModel->getAll();
            $rams = $ramModel->getAll();
            $discs = $discModel->getAll();
            $gpus = $gpuModel->getAll();
            $pcs = $pcModel->getAllWithDetails();
        } catch (\Exception $e) {
            $errorMessage = "Error al cargar componentes: " . $e->getMessage();
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
            if (isset($_POST['add'])) {
                $prefix = trim($_POST['prefix']);
                if (!empty($prefix)) {
                    // Requires setting num=0 in DB for new prefixes
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
            }
        }

        // Messages from redirect
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "Prefijo SN eliminado correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "Prefijo SN añadido correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "Prefijo SN editado correctamente.";
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
            if (isset($_POST['add'])) {
                $name = trim($_POST['name']);
                if (!empty($name)) {
                    // Create minimal model just with the name as in legacy models.php
                    // Wait, the new `create()` method in `TicketModel.php` requires all the PC attributes: 
                    // `create($name, $board_type, $cpu, $cpu_other, $ram_capacity, $ram_other, $ram_type, $disc_capacity, $disc_other, $disc_type, $gpu, $gpu_other, $gpu_type, $wifi, $bluetooth, $sn, $sn_other, $observaciones)`
                    // Let's create a simpler method just for adding the name or supply nulls. The legacy only asked for Name.
                    // Actually, the legacy code from 'models.php' has: "Añadir Nueva Modelo" -> only form field is "name".
                    // But wait, the `models` table has `model` as an attribute too (based on the index listing).
                    // Let me check my method `create` in TicketModel. It was adapted from ticket generation. 
                    // The pure `Model` entity might need an add method just for name.
                    // I'll supply empty strings to the required fields for now to match the legacy behavior.
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
            }
        }

        // Handle Messages
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'deleted')
                $successMessage = "Modelo eliminado correctamente.";
            if ($_GET['msg'] === 'added')
                $successMessage = "Modelo añadido correctamente.";
            if ($_GET['msg'] === 'edited')
                $successMessage = "Modelo actualizado correctamente.";
        }

        // Fetch Data
        try {
            $models = $model->getAll();
        } catch (\Exception $e) {
            $errorMessage = "Error al cargar modelos: " . $e->getMessage();
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
            if (isset($_POST['add'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $email = trim($_POST['email']);

                if (empty($username) || empty($password) || empty($email)) {
                    $errorMessage = "Por favor, complete todos los campos.";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    // Standard user role is 2 by default
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
            } elseif (isset($_POST['updaterole'])) { // Addition: inline role change logic inside users controller instead of separate file like edit_role.php
                $userId = (int) $_POST['userId'];
                $roleId = (int) $_POST['role_id'];
                if ($userModel->updateRole($userId, $roleId)) {
                    $this->redirect('admin/users?msg=role_updated');
                } else {
                    $errorMessage = "Error al cambiar el rol.";
                }
            }
        }

        // Handle Messages
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
        }

        // Fetch Data
        try {
            $users = $userModel->getAllWithRoles();
            $roles = $userModel->getRoles();
        } catch (\Exception $e) {
            $errorMessage = "Error al cargar usuarios: " . $e->getMessage();
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
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}

