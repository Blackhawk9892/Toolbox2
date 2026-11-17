<?php
// PHP 7.3+; requires includes/connection.php to provide a mysqli connection.
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');

function reply($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

try {
    require_once __DIR__ . '/includes/connection.php';
    // Accept common variable names used by connection.php.
    if (isset($conn) && $conn instanceof mysqli) $db = $conn;
    elseif (isset($mysqli) && $mysqli instanceof mysqli) $db = $mysqli;
    elseif (isset($connection) && $connection instanceof mysqli) $db = $connection;
    else throw new Exception('Connection file must define $conn, $mysqli, or $connection (mysqli).');
    $db->set_charset('utf8mb4');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $action = $_POST['action'] ?? 'list';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') reply(['success'=>false,'message'=>'POST required'],405);

    // Serialize changes using an InnoDB row lock; every writer locks the same row.
    if ($action !== 'list') {
        $db->begin_transaction();
        $db->query('SELECT id FROM phone_rotation_lock WHERE id = 1 FOR UPDATE');
    }

    if ($action === 'assign') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) throw new Exception('Select a salesperson.');
        $stmt = $db->prepare('SELECT name FROM phone_salespeople WHERE id = ? AND active = 1');
        $stmt->bind_param('i', $id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$row) throw new Exception('Salesperson not found or inactive.');
        $name = $row['name'];
        $result = $db->query('SELECT COALESCE(MAX(position),0)+1 AS next_position FROM phone_salespeople WHERE active = 1');
        $next = (int)$result->fetch_assoc()['next_position'];
        $stmt = $db->prepare('UPDATE phone_salespeople SET position = ? WHERE id = ?');
        $stmt->bind_param('ii', $next, $id); $stmt->execute(); $stmt->close();
        $stmt = $db->prepare('INSERT INTO phone_call_history (salesperson_id,salesperson_name) VALUES (?,?)');
        $stmt->bind_param('is', $id, $name); $stmt->execute(); $stmt->close();
        $message = 'Call assigned to ' . $name . '.';
    } elseif ($action === 'add') {
        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '' || mb_strlen($name, 'UTF-8') > 120) throw new Exception('Enter a name of up to 120 characters.');
        $result = $db->query('SELECT COALESCE(MAX(position),0)+1 AS next_position FROM phone_salespeople');
        $next = (int)$result->fetch_assoc()['next_position'];
        $stmt = $db->prepare('INSERT INTO phone_salespeople (name,position) VALUES (?,?)');
        $stmt->bind_param('si', $name, $next); $stmt->execute(); $stmt->close();
        $message = 'Salesperson added.';
    } elseif ($action === 'set_active') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $active = (string)($_POST['active'] ?? '');
        if (!$id || !in_array($active, ['0','1'], true)) throw new Exception('Invalid request.');
        $flag = (int)$active;
        if ($flag === 1) {
            $result = $db->query('SELECT COALESCE(MAX(position),0)+1 AS next_position FROM phone_salespeople');
            $next = (int)$result->fetch_assoc()['next_position'];
            $stmt = $db->prepare('UPDATE phone_salespeople SET active=1,position=? WHERE id=?');
            $stmt->bind_param('ii', $next, $id);
        } else {
            $stmt = $db->prepare('UPDATE phone_salespeople SET active=0 WHERE id=?');
            $stmt->bind_param('i', $id);
        }
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $check = $db->prepare('SELECT id FROM phone_salespeople WHERE id=?');
            $check->bind_param('i',$id); $check->execute();
            $found = $check->get_result()->fetch_assoc(); $check->close();
            if (!$found) throw new Exception('Salesperson not found.');
        }
        $stmt->close();
        $message = $flag ? 'Salesperson reactivated at bottom.' : 'Salesperson deactivated.';
    } elseif ($action !== 'list') {
        throw new Exception('Unknown action.');
    }

    if ($action !== 'list') $db->commit();
    $people = [];
    $result = $db->query('SELECT id,name,active,position FROM phone_salespeople ORDER BY active DESC,position ASC,id ASC');
    while ($row = $result->fetch_assoc()) $people[] = $row;
    $history = [];
    $result = $db->query('SELECT salesperson_name,assigned_at FROM phone_call_history ORDER BY id DESC LIMIT 20');
    while ($row = $result->fetch_assoc()) $history[] = $row;
    reply(['success'=>true,'message'=>$message ?? '', 'salespeople'=>$people,'history'=>$history]);
} catch (Throwable $e) {
    if (isset($db) && $db instanceof mysqli) { try { $db->rollback(); } catch (Throwable $ignored) {} }
    error_log('Phone rotation: ' . $e->getMessage());
    reply(['success'=>false,'message'=>'Unable to complete request. Check PHP error log and database setup.'],500);
}
