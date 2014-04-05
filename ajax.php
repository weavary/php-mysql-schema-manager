<?php
require_once 'database_model.php';

header('Content-Type: application/json');

if (!isset($_GET['action'])) {
    echo json_encode(array(
        'status' => 0,
        'message' => 'Action not provided',
    ));
    exit;
}

$db = new DatabaseModel();
switch($_GET['action']) {
    case 'db-list':
        $dbs = $db->getAllDatabases();
        foreach ($dbs as $db_name) {
            @mkdir(DB_SCHEMA_PATH .  $db_name);
        }
        echo json_encode(array(
            'status' => 1,
            'data' => $dbs,
        ));
        break;

    case 'schema':
        if (!isset($_GET['database'])) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Database not selected',
            ));
            exit;
        }

        $xml_files = glob(DB_SCHEMA_PATH . $_GET['database'] . DIRECTORY_SEPARATOR . '*.xml');
        $files = array();
        foreach ($xml_files as $file) {
            $files[] = basename($file);
        }
        echo json_encode(array(
            'status' => 1,
            'data' => $files,
        ));
        break;

    default:
        echo json_encode(array(
            'status' => 0,
            'message' => 'Action not found',
        ));
        break;
}
exit;