<?php

/**
 * Funcion que convierte un numero entero a romano.
 *
 * @return string
 */
function integer_to_roman($integer)
{
 // Convert the integer into an integer (just to make sure)
 $integer = intval($integer);
 $result = '';
 
 // Create a lookup array that contains all of the Roman numerals.
 $lookup = array('M' => 1000,
 'CM' => 900,
 'D' => 500,
 'CD' => 400,
 'C' => 100,
 'XC' => 90,
 'L' => 50,
 'XL' => 40,
 'X' => 10,
 'IX' => 9,
 'V' => 5,
 'IV' => 4,
 'I' => 1);
 
 foreach($lookup as $roman => $value){
  // Determine the number of matches
  $matches = intval($integer/$value);
 
  // Add the same number of characters to the string
  $result .= str_repeat($roman,$matches);
 
  // Set the integer to be the remainder of the integer and the value
  $integer = $integer % $value;
 }
 
 // The Roman numeral should be built, return it
 return $result;
}
/**
 * Función para encriptar
 * 
 * @param string $data
 * 
 * @return string
 */
function encrypt($data) {
    $key = "keyEncryptS3W";
    $method = "AES-256-CBC";
    $secret_iv = 'sepd';
    $data = trim($data);

    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    $encrypted = base64_encode($encrypted);

    return $encrypted;
}

/**
 * Función para desencriptar
 * 
 * @param string $data
 * 
 * @return string
 */
function decrypt($data) {
    $key = "keyEncryptS3W";
    $method = "AES-256-CBC";
    $secret_iv = 'sepd';
    $_data = trim($data);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	$_data = openssl_decrypt(base64_decode($_data), $method, $key, 0, $iv);
	
    return $_data;
}

/**
 * Funcion que sube un archivo al servidor.
 *
 * @return bool
 */
function uploadFile($imagen, $ruta, $file){
    $target_dir = $ruta;
    $target_file = $target_dir . basename($_FILES["$file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_file = $target_dir . (md5(time() . sha1(basename($_FILES["$file"]["name"])))) . "." . $imageFileType;//basename($_FILES["$file"]["name"]);
    
    // Check para saber si la imagen es real
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["$file"]["tmp_name"]);
        
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            return false;
        }
    }

    // Check del tamaño del archivo
    if ($_FILES["$file"]["size"] > 5000000) {
        $uploadOk = 0;
        return false;
    }

    // Check del tipo de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "txt" && $imageFileType != "pdf" ) {
        $uploadOk = 0;
        return false;
    }
    // Check si el archivo es seguro para subir
    if ($uploadOk == 0) {
        return false;
    // Si todo esta bien el archivo se coloca en la ruta
    } else {
        if (move_uploaded_file($_FILES["$file"]["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            return false;
        }
    }
}

/**
 * Validar VPC
 * 
 * @param int $num_colegiado
 * 
 * @return
 */
function check_vpc(int $num_colegiado, $checkStatus = false) {
    $strRequest = '<soapenv:Envelope
    xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:ser="http://vpc.cgcom.es/ws/server/">
        <soapenv:Header/>
        <soapenv:Body>
            <ser:GetVPCNumColegiado>
                <NumeroColegiado>' . $num_colegiado . '</NumeroColegiado>
            </ser:GetVPCNumColegiado>
        </soapenv:Body>
    </soapenv:Envelope>';
    
    $headers = [
        "Content-type: text/xml",
        "Accept: text/xml",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        "SOAPAction: http://url/location/of/soap/method",
        "Content-length: " . strlen($strRequest)
    ];
    
    $result = ($checkStatus ? false : "No se ha podido consultar la información");

    $ch = curl_init("https://vpc.cgcom.es/ws/server/sscc/index.php?wsdl");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER["DOCUMENT_ROOT"] . '/vpcr/resources/cert_sepd.key.pem');
    curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER["DOCUMENT_ROOT"] . '/vpcr/resources/cert_sepd.crt.pem');
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'S3pD_33'); // SepD.WS-2019
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $strRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    
    if (!curl_errno($ch))
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200):
            $xml = simplexml_load_string($output);
            if (!!$xml) return $result;

            $xml_result = $xml->xpath('//resultado')[0];

            if ($xml_result == "ERROR, No se han encontrado registros"):
                $result = "No encontrado";
            else:
                $numero_colegiado = $xml->xpath('//NumeroColegiado')[0];
                if ($numero_colegiado != $num_colegiado):
                    $result = "El número de colegiado no coincide con el número recibido";
                else:
                    $tramo = $xml->xpath('//Tramo')[0];
                    $tramo_array = explode('-', $tramo);

                    $tramo_inicio = (int) $tramo_array[0];
                    $tramo_fin = (int) $tramo_array[1];
                    $current_year = (int) date('Y');
                    
                    if ($checkStatus) return [ $tramo_inicio, $tramo_fin ];
                    if ($current_year <= $tramo_fin):
                        $result = "Vigente hasta el {$tramo_fin}";
                    else:
                        $result = "Se encuentra caducado";
                    endif;
                endif;
            endif;
        endif;
    
    curl_close($ch);
    return $result;
}

/**
 * Funcion recibe un numero y verifica con CGCOM
 * si el numero enviado es un numero valido de colegiado. 
 *
 * @return string
 */
function numero_CGCOM($numero)
{
    //Un numero valido de colegiado 454502378.

    $soap_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:cgcom.es:vuds.1.0">
    <soapenv:Header/>
        <soapenv:Body>
            <urn:PeticionConsultaRegistroCGCOM>
                <urn:NumeroColegiado>' . $numero . '</urn:NumeroColegiado>
            </urn:PeticionConsultaRegistroCGCOM>
        </soapenv:Body>
    </soapenv:Envelope>';

    $headers = array(
        'Content-Type: text/xml',
        'SOAPAction: "#POST"'
    );

    $url = 'https://pre-registro.cgcom.es:8002/WSRegistroCGCOM?wsdl';

    if (APP_DEBUG) {
        try {
            $ch = curl_init($url);
            if ($ch === false) {
                throw new Exception('failed to initialize');
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_request);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $output = curl_exec($ch);

            if ($output === false) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }
        } catch (Exception $e) {
            trigger_error(
                sprintf(
                    ': Curl failed with error #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                ),
                E_USER_ERROR
            );
        }
    } else {
        $ch = curl_init($url);
        if ($ch === false) {
            throw new Exception('failed to initialize');
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
    }
    curl_close($ch);

    $output = strval($output) == true ? true : false;

    return $output;
}