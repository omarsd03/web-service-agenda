<?php

    include ('db-connect.php');

    $response = array();

    if (isset($_GET['txtId']) && !empty($_GET['txtId'])) {
        
        $str_id = $_GET['txtId'];

        try {
            
            $cnn = new ConexionBD();
            $con = $cnn->conectaBD();

            $sttmt = $con->prepare("SELECT * FROM contactos WHERE id=:id");
            $sttmt->bindParam(':id', $id);

            $id = $str_id;
            $sttmt->execute();

            $rowCount = $sttmt->rowCount();

            if ($rowCount > 0) {
                
                $resultSet = $sttmt->fetchAll();
                $response["contactos"] = array();

                foreach ($resultSet as $row ) {
                    $contactos = array();
                    $contactos["id"] = $row["id"];        
                    $contactos["nombre"] = $row["nombre"];    
                    $contactos["apellidos"] = $row["apellidos"];    
                    $contactos["telefono"] = $row["telefono"];    
                    $contactos["correo"] = $row["correo"];
                    array_push($response["contactos"], $contactos);
                }

                $response['success'] = 1;
				$response['message'] = 'Contacto encontrado';
				echo json_encode($response);

            } else {
                $response['success'] = 0;
				$response['message'] = 'Contacto NO encontrado';
				echo json_encode($response);
            }

        } catch (Exception $e) {
            
            switch ($e->getCode()){

				case 2002:
					$response['success'] = 0;
					$response['message'] =  'El servidor no responde, verifique el estado del servidor y puerto MySQL';
                break;
                
				case 1049:
					$response['success'] = 0;
					$response['message'] =  'La base de datos no existe, verifique el estado de la base de datos en el servidor MySQL';
                break;
                
				case 1045:
					$response['success'] = 0;
					$response['message'] =  'El usuario o la clave de acceso son incorrectos, verifique el estado del usuario de la base de datos en el servidor MySQL';
                break;
                
				case 23000:
					$response['success'] = 0;
					$response['message'] =  'La clave principal ya existe, intente de nuevo con otros datos';
                break;
                
				default:
					$response['success'] = 0;
					$response['message'] = 'Error desconocido, verifique con el administrador del servidor MySQL o del sistema';
            }
            
			echo json_encode($response);

        }

    } else {
        $response['success'] = 0;
		$response['message'] = 'Algunos campos faltan en el llenado del formulario';
		echo json_encode($response);
    }

?>