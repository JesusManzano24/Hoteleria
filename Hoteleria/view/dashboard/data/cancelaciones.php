<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../ws/conexion.php');
$conn = conectar();

try {
    $sql = "
        SELECT 
            MONTH(r.fecha_reserva) AS mes,
            r.metodo_pago,
            a.tipo,
            COUNT(*) AS total
        FROM reservas r
        JOIN alojamientos a ON r.id_alojamiento = a.id_alojamiento
        JOIN estado_reserva e ON r.id_estado_reserva = e.id_estado_reserva
        WHERE e.nombre_estado = 'Cancelada'
        GROUP BY mes, r.metodo_pago, a.tipo
        ORDER BY mes;
    ";

    $stmt = $conn->query($sql);

    $data = [];
    $tipos = [];
    $metodos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mes = (int)$row['mes'];
        $tipo = $row['tipo'];
        $metodo = $row['metodo_pago'];
        $total = (int)$row['total'];

        $tipos[$tipo] = true;
        $metodos[$metodo] = true;
        $data[$tipo][$metodo][$mes] = $total;
    }

    $response = [
        "meses" => array_map(fn($i) => "Mes $i", range(1, 12)),
        "datasets" => []
    ];

    $colores = ['#2196F3', '#E91E63', '#FF9800', '#4CAF50', '#9C27B0', '#795548'];
    $colorIndex = 0;

    foreach ($data as $tipo => $metodosData) {
        foreach ($metodosData as $metodo => $valores) {
            $dataset = [
                "label" => "$tipo / $metodo",
                "data" => [],
                "backgroundColor" => $colores[$colorIndex++ % count($colores)]
            ];

            for ($m = 1; $m <= 12; $m++) {
                $dataset['data'][] = $valores[$m] ?? 0;
            }

            $response["datasets"][] = $dataset;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
