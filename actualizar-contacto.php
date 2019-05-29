<?php

    include ('db-connect.php');

    $response = array();

    if ( isset($_POST['txtId']) && isset($_POST['txtNombre']) && isset($_POST['txtApellidos']) && isset($_POST['txtTelefono']) && isset($_POST['txtCorreo']) && !empty($_POST['txtId']) && !empty($_POST['txtNombre']) && !empty($_POST['txtApellidos']) && !empty($_POST['txtTelefono']) && !empty($_POST['txtCorreo']) ) {
        
        $str_id = $_POST['txtId'];
        $str_nombre = $_POST['txtNombre'];
        $str_apellidos = $_POST['txtApellidos'];
        $str_telefono = $_POST['txtTelefono'];
        $str_correo = $_POST['txtCorreo'];

        try {

            $cnn = new ConexionBD();
            $con = $cnn->conectaBD();

            $sttmt = $con->prepare("SELECT * FROM contactos WHERE id = :id");
            $sttmt->bindParam(':id', $id);

            $id = $str_id;
            $sttmt->execute();

            $rowCount = $sttmt->rowCount();

            if ($rowCount > 0) {
                
                $sttmt = $con->prepare("UPDATE contactos SET nombre=:nombre, apellidos=:apellidos, telefono=:telefono, correo=:correo WHERE id=:id");
                $sttmt->bindParam(':id', $id);
				$sttmt->bindParam(':nombre', $nombre);
				$sttmt->bindParam(':apellidos', $apellidos);
				$sttmt->bindParam(':telefono', $telefono);
                $sttmt->bindParam(':correo', $correo);
                
                $id = $str_id;
                $nombre = $str_nombre;
                $apellidos = $str_apellidos;
                $telefono = $str_telefono;
                $correo = $str_correo;
                $sttmt->execute();

                $rowCount = $sttmt->rowCount();

                if ($rowCount > 0) {
                    $response['success'] = 1;
                    $response['message'] = "Contacto actualizado correctamente";
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Contacto encontrado pero NO actualizado";
                }

                echo json_encode($response);

            } else {

                $response['success'] = 0;
                $response['message'] = "Contacto NO encontrado";

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