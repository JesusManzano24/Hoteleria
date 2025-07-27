<?php
header('Content-Type: application/json');
include '../../ws/conexion.php';


$sql = "SELECT 
    MONTH(r.fecha_inicio) as mes, 
    a.tipo_alojamiento, 
    r.metodo_pago, 
    COUNT(*) as total 
FROM reservas r 
JOIN alojamientos a ON r.id_alojamiento = a.id_alojamiento 
WHERE r.estado = 'Cancelada' 
GROUP BY mes, tipo_alojamiento, metodo_pago";

$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $key = $row['tipo_alojamiento'] . ' - ' . $row['metodo_pago'];
    $data[$key][(int)$row['mes']] = (int)$row['total'];
}

$meses = range(1, 12);
$datasets = [];

foreach ($data as $label => $mesesData) {
    $datasets[] = [
        'label' => $label,
        'data' => array_map(fn($m) => $mesesData[$m] ?? 0, $meses),
        'backgroundColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
    ];
}

echo json_encode([
    'meses' => $meses,
    'datasets' => $datasets
]);
