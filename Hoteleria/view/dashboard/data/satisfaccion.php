<?php
header('Content-Type: application/json');
include '../db.php';

$sql = "SELECT 
    DAYNAME(re.fecha_resena) AS dia_semana,
    GROUP_CONCAT(s.nombre_servicio) AS servicios,
    AVG(re.calificacion) AS promedio_calificacion,
    COUNT(*) AS total_resenas
FROM resenas re
JOIN reservas r ON re.id_reserva = r.id_reserva
JOIN alojamientos a ON r.id_alojamiento = a.id_alojamiento
JOIN alojamiento_servicio als ON a.id_alojamiento = als.id_alojamiento
JOIN servicios s ON als.id_servicio = s.id_servicio
GROUP BY dia_semana, a.id_alojamiento
HAVING total_resenas >= 3
ORDER BY promedio_calificacion DESC";

$result = $conn->query($sql);

$diasMap = ['Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6,'Sunday'=>7];

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'dia' => $diasMap[$row['dia_semana']] ?? 0,
        'servicios' => $row['servicios'],
        'calificacion' => round((float)$row['promedio_calificacion'], 2),
        'reseñas' => (int)$row['total_resenas']
    ];
}

echo json_encode($data);
