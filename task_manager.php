<?php

$tareasArchivo = 'tareas.txt';
if (!file_exists($tareasArchivo)) {
    file_put_contents($tareasArchivo, '');
}

function ID()
{
    return str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
}

function agregarTarea($titulo, $descripcion)
{
    global $tareasArchivo;
    
    $tareas = json_decode(file_get_contents($tareasArchivo), true);

    $nuevaTarea = [
        'id' => ID(),
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'fecha_afegit' => date('Y-m-d H:i:s')
    ];

    $tareas[] = $nuevaTarea;

    file_put_contents($tareasArchivo, json_encode($tareas));
}

function listarTareas()
{
    $tareas = json_decode(file_get_contents($GLOBALS['tareasArchivo']), true);

    if (empty($tareas)) {
        echo "No ni ha tareas pendents.";
        return;
    }

    foreach ($tareas as $index => $tarea) {
        echo ($index + 1) . " - ID: {$tarea['id']} - {$tarea['titulo']} - {$tarea['descripcion']} - Añadido el: {$tarea['fecha_afegit']}";
    }
}

function eliminarTarea($id)
{
    $tareas = json_decode(file_get_contents($GLOBALS['tareasArchivo']), true);

    foreach ($tareas as $index => $tarea) {
        if ($tarea['id'] === $id) {
            unset($tareas[$index]);
            break;
        }
    }

    file_put_contents($GLOBALS['tareasArchivo'], json_encode(array_values($tareas)));
}

$comando = $argv[1] ?? '';

switch ($comando) {
    case 'afegir':
        $titulo = $argv[2] ?? '';
        $descripcion = $argv[3] ?? '';
        if (!empty($titulo) && !empty($descripcion)) {
            agregarTarea($titulo, $descripcion);
            echo "Te ha afegit la tarea";
        } else {
            echo "Fica be el titul y la descripcio";
        }
        break;

    case 'llistar':
        listarTareas();
        break;

    case 'eliminar':
        $id = $argv[2] ?? '';
        if (!empty($id)) {
            eliminarTarea($id);
            echo "La tarea a sigut eliminada correctament.";
        } else {
            echo "Fica el ID per eliminar la tarea";
        }
        break;

    default:
        echo "========================================================================\n";
        echo "Per utilitzar el programa ni ha diferents opcions:\n";
        echo "1. Per afegir una tarea: afegir 'Titol de la tarea' 'Descripcio'\n";
        echo "2. Per llistar les tareas: llistar\n";
        echo "3. Per eliminar una tarea: eliminar ID\n";
        echo "========================================================================\n";
        break;
}
?>

