<?php
include ('conexion.php');

function fecha($fecha)
{
	switch($fecha[5].$fecha[6])
	{
		case '01'; $mes='Enero'; break;
		case '02'; $mes='Febrero'; break;
		case '03'; $mes='Marzo'; break;
		case '04'; $mes='Abril'; break;
		case '05'; $mes='Mayo'; break;
		case '06'; $mes='Junio'; break;
		case '07'; $mes='Julio'; break;
		case '08'; $mes='Agosto'; break;
		case '09'; $mes='Septiembre'; break;
		case '10'; $mes='Octubre'; break;
		case '11'; $mes='Noviembre'; break;
		case '12'; $mes='Diciembre'; break;
	}
	
	$fechanew=$fecha[8].$fecha[9].' - '.$mes.' - '.$fecha[0].$fecha[1].$fecha[2].$fecha[3];
	
	return $fechanew;
}

function fechados($fecha)
{
	switch($fecha[5].$fecha[6])
	{
		case '01'; $mes='Ene'; break;
		case '02'; $mes='Feb'; break;
		case '03'; $mes='Mar'; break;
		case '04'; $mes='Abr'; break;
		case '05'; $mes='May'; break;
		case '06'; $mes='Jun'; break;
		case '07'; $mes='Jul'; break;
		case '08'; $mes='Ago'; break;
		case '09'; $mes='Sep'; break;
		case '10'; $mes='Oct'; break;
		case '11'; $mes='Nov'; break;
		case '12'; $mes='Dic'; break;
	}
	
	$fechanew=$fecha[8].$fecha[9].' - '.$mes.' - '.$fecha[2].$fecha[3];
	
	return $fechanew;
}

function mes($mesn)
{
   switch($mesn)
	{
		case '01'; $mes='Enero'; break;
		case '02'; $mes='Febrero'; break;
		case '03'; $mes='Marzo'; break;
		case '04'; $mes='Abril'; break;
		case '05'; $mes='Mayo'; break;
		case '06'; $mes='Junio'; break;
		case '07'; $mes='Julio'; break;
		case '08'; $mes='Agosto'; break;
		case '09'; $mes='Septiembre'; break;
		case '10'; $mes='Octubre'; break;
		case '11'; $mes='Noviembre'; break;
		case '12'; $mes='Diciembre'; break;
	}
	
	return $mes;
}

function caducidad($fecha)
{
   switch($fecha[5].$fecha[6])
	{
		case '01'; $mes='Ene'; break;
		case '02'; $mes='Feb'; break;
		case '03'; $mes='Mar'; break;
		case '04'; $mes='Abr'; break;
		case '05'; $mes='May'; break;
		case '06'; $mes='Jun'; break;
		case '07'; $mes='Jul'; break;
		case '08'; $mes='Ago'; break;
		case '09'; $mes='Sep'; break;
		case '10'; $mes='Oct'; break;
		case '11'; $mes='Nov'; break;
		case '12'; $mes='Dic'; break;
	}
	
	$fechanew=$mes.' - '.$fecha[2].$fecha[3];
	
	return $fechanew;
}

function fecha_bit($fecha)
{
   switch($fecha[5].$fecha[6])
	{
		case '01'; $mes='Ene'; break;
		case '02'; $mes='Feb'; break;
		case '03'; $mes='Mar'; break;
		case '04'; $mes='Abr'; break;
		case '05'; $mes='May'; break;
		case '06'; $mes='Jun'; break;
		case '07'; $mes='Jul'; break;
		case '08'; $mes='Ago'; break;
		case '09'; $mes='Sep'; break;
		case '10'; $mes='Oct'; break;
		case '11'; $mes='Nov'; break;
		case '12'; $mes='Dic'; break;
	}

   $fechanew=$fecha[8].$fecha[9].'-'.$mes.'-'.$fecha[2].$fecha[3].'<br />'.$fecha[11].$fecha[12].$fecha[13].$fecha[14].$fecha[15].$fecha[16].$fecha[17].$fecha[18];

   return $fechanew;
}

function res_poblacion($estado, $poblacion)
{
    include('../conexion.php');

    $ResPobV=mysqli_query($conn, "SELECT Id FROM poblaciones WHERE Estado='".$estado."' AND Poblacion LIKE '".$poblacion."' LIMIT 1");
    if(mysqli_num_rows($ResPobV)==1)
    {
        $RResPobV=mysqli_fetch_array($ResPobV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO poblaciones (Estado, Poblacion) VALUES ('".$estado."', '".$poblacion."')") or die(mysqli_error($conn));
        $RResPobV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM poblaciones WHERE Poblacion LIKE '".$poblacion."' LIMIT 1"));
    }

    return $RResPobV["Id"];
}
function res_religion($religion)
{
    include('../conexion.php');

    $ResRelV=mysqli_query($conn, "SELECT Id FROM religion WHERE Religion LIKE '".$religion."' LIMIT 1");
    if(mysqli_num_rows($ResRelV)==1)
    {
        $RResRelV=mysqli_fetch_array($ResRelV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO religion (Religion) VALUES ('".$religion."')") or die(mysqli_error($conn));
        $RResRelV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM religion WHERE Religion LIKE '".$religion."' LIMIT 1"));
    }

    return $RResRelV["Id"];
}
function res_edocivil($edocivil)
{
    include('../conexion.php');

    $ResEdCV=mysqli_query($conn, "SELECT Id FROM edocivil WHERE EdoCivil LIKE '".$edocivil."' LIMIT 1");
    if(mysqli_num_rows($ResEdCV)==1)
    {
        $RResEdCV=mysqli_fetch_array($ResEdCV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO edocivil (EdoCivil) VALUES ('".$edocivil."')") or die(mysqli_error($conn));
        $RResEdCV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM edocivil WHERE EdoCivil LIKE '".$edocivil."' LIMIT 1"));
    }

    return $RResEdCV["Id"];
}
function res_lenguas($lengua)
{
    include('../conexion.php');

    $ResLenV=mysqli_query($conn, "SELECT Id FROM lenguas WHERE Lengua LIKE '".$lengua."' LIMIT 1");
    if(mysqli_num_rows($ResLenV)==1)
    {
        $RResLenV=mysqli_fetch_array($ResLenV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO lenguas (Lengua) VALUES ('".$lengua."')") or die(mysqli_error($conn));
        $RResLenV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM lenguas WHERE Lengua LIKE '".$lengua."' LIMIT 1"));
    }

    return $RResLenV["Id"];
}
function res_comunidad($comunidad)
{
    include('../conexion.php');

    $ResComV=mysqli_query($conn, "SELECT Id FROM comunidades WHERE Comunidad LIKE '".$comunidad."' LIMIT 1");
    if(mysqli_num_rows($ResComV)==1)
    {
        $RResComV=mysqli_fetch_array($ResComV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO comunidades (Comunidad) VALUES ('".$comunidad."')") or die(mysqli_error($conn));
        $RResComV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM comunidades WHERE Comunidad LIKE '".$comunidad."' LIMIT 1"));
    }

    return $RResComV["Id"];
}
function res_instituto($instituto)
{
    include('../conexion.php');

    $ResInsV=mysqli_query($conn, "SELECT Id FROM institutos WHERE Instituto LIKE '".$instituto."' LIMIT 1");
    if(mysqli_num_rows($ResInsV)==1)
    {
        $RResInsV=mysqli_fetch_array($ResInsV);
    }
    else
    {
        mysqli_query($conn, "INSERT INTO institutos (Instituto) VALUES ('".$instituto."')");
        $RResInsV=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM institutos WHERE Instituto LIKE '".$instituto."' LIMIT 1"));
    }

    return $RResInsV["Id"];
}

function obtener_edad_segun_fecha($fecha_nacimiento)
{
    $nacimiento = new DateTime($fecha_nacimiento);
    $ahora = new DateTime(date("Y-m-d"));
    $diferencia = $ahora->diff($nacimiento);
    return $diferencia->format("%y");
}

function calcular_edad($fecha){
   $fecha_nac = new DateTime(date('Y/m/d',strtotime($fecha))); // Creo un objeto DateTime de la fecha ingresada
   $fecha_hoy =  new DateTime(date('Y/m/d',time())); // Creo un objeto DateTime de la fecha de hoy
   $edad = date_diff($fecha_hoy,$fecha_nac); // La funcion ayuda a calcular la diferencia, esto seria un objeto
   return $edad;
}

function num2letras($num, $fem = true, $dec = true) 
{ 
    //if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
       $matuni[2]  = "dos"; 
       $matuni[3]  = "tres"; 
       $matuni[4]  = "cuatro"; 
       $matuni[5]  = "cinco"; 
       $matuni[6]  = "seis"; 
       $matuni[7]  = "siete"; 
       $matuni[8]  = "ocho"; 
       $matuni[9]  = "nueve"; 
       $matuni[10] = "diez"; 
       $matuni[11] = "once"; 
       $matuni[12] = "doce"; 
       $matuni[13] = "trece"; 
       $matuni[14] = "catorce"; 
       $matuni[15] = "quince"; 
       $matuni[16] = "dieciseis"; 
       $matuni[17] = "diecisiete"; 
       $matuni[18] = "dieciocho"; 
       $matuni[19] = "diecinueve"; 
       $matuni[20] = "veinte"; 
       $matunisub[2] = "dos"; 
       $matunisub[3] = "tres"; 
       $matunisub[4] = "cuatro"; 
       $matunisub[5] = "quin"; 
       $matunisub[6] = "seis"; 
       $matunisub[7] = "sete"; 
       $matunisub[8] = "ocho"; 
       $matunisub[9] = "nove"; 
    
       $matdec[2] = "veint"; 
       $matdec[3] = "treinta"; 
       $matdec[4] = "cuarenta"; 
       $matdec[5] = "cincuenta"; 
       $matdec[6] = "sesenta"; 
       $matdec[7] = "setenta"; 
       $matdec[8] = "ochenta"; 
       $matdec[9] = "noventa"; 
       $matsub[3]  = 'mill'; 
       $matsub[5]  = 'bill'; 
       $matsub[7]  = 'mill'; 
       $matsub[9]  = 'trill'; 
       $matsub[11] = 'mill'; 
       $matsub[13] = 'bill'; 
       $matsub[15] = 'mill'; 
       $matmil[4]  = 'millones'; 
       $matmil[6]  = 'billones'; 
       $matmil[7]  = 'de billones'; 
       $matmil[8]  = 'millones de billones'; 
       $matmil[10] = 'trillones'; 
       $matmil[11] = 'de trillones'; 
       $matmil[12] = 'millones de trillones'; 
       $matmil[13] = 'de trillones'; 
       $matmil[14] = 'billones de trillones'; 
       $matmil[15] = 'de billones de trillones'; 
       $matmil[16] = 'millones de billones de trillones'; 
    
       $num = trim((string)@$num); 
       if ($num[0] == '-') { 
          $neg = 'menos '; 
          $num = substr($num, 1); 
       }else 
          $neg = ''; 
       while ($num[0] == '0') $num = substr($num, 1); 
       if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
       $zeros = true; 
       $punt = false; 
       $ent = ''; 
       $fra = ''; 
       for ($c = 0; $c < strlen($num); $c++) { 
          $n = $num[$c]; 
          if (! (strpos(".,'''", $n) === false)) { 
             if ($punt) break; 
             else{ 
                $punt = true; 
                continue; 
             } 
    
          }elseif (! (strpos('0123456789', $n) === false)) { 
             if ($punt) { 
                if ($n != '0') $zeros = false; 
                $fra .= $n; 
             }else 
    
                $ent .= $n; 
          }else 
    
             break; 
    
       } 
       $ent = '     ' . $ent; 
       if ($dec and $fra and ! $zeros) { 
          $fin = ' coma'; 
          for ($n = 0; $n < strlen($fra); $n++) { 
             if (($s = $fra[$n]) == '0') 
                $fin .= ' cero'; 
             elseif ($s == '1') 
                $fin .= $fem ? ' uno' : ' un'; 
             else 
                $fin .= ' ' . $matuni[$s]; 
          } 
       }else 
          $fin = ''; 
       if ((int)$ent === 0) return 'Cero ' . $fin; 
       $tex = ''; 
       $sub = 0; 
       $mils = 0; 
       $neutro = false; 
       while ( ($num = substr($ent, -3)) != '   ') { 
          $ent = substr($ent, 0, -3); 
          if (++$sub < 3 and $fem) { 
             $matuni[1] = 'un'; 
             $subcent = 'os'; 
          }else{ 
             $matuni[1] = $neutro ? 'un' : 'un'; 
             $subcent = 'os'; 
          } 
          $t = ''; 
          $n2 = substr($num, 1); 
          if ($n2 == '00') { 
          }elseif ($n2 < 21) 
             $t = ' ' . $matuni[(int)$n2]; 
          elseif ($n2 < 30) { 
             $n3 = $num[2]; 
             if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
             $n2 = $num[1]; 
             $t = ' ' . $matdec[$n2] . $t; 
          }else{ 
             $n3 = $num[2]; 
             if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
             $n2 = $num[1]; 
             $t = ' ' . $matdec[$n2] . $t; 
          } 
          $n = $num[0]; 
          if ($n == 1) { 
             $t = ' ciento' . $t; 
          }elseif ($n == 5){ 
             $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
          }elseif ($n != 0){ 
             $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
          } 
          if ($sub == 1) { 
          }elseif (! isset($matsub[$sub])) { 
             if ($num == 1) { 
                $t = ' mil'; 
             }elseif ($num > 1){ 
                $t .= ' mil'; 
             } 
          }elseif ($num == 1) { 
             $t .= ' ' . $matsub[$sub] . 'on'; 
          }elseif ($num > 1){ 
             $t .= ' ' . $matsub[$sub] . 'ones'; 
          }   
          if ($num == '000') $mils ++; 
          elseif ($mils != 0) { 
             if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
             $mils = 0; 
          } 
          $neutro = true; 
          $tex = $t . $tex; 
       } 
       $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 

function randomColor(){
 $str = "#";
 for($i = 0 ; $i < 6 ; $i++){
 $randNum = rand(0, 15);
 switch ($randNum) {
 case 10: $randNum = "A"; 
 break;
 case 11: $randNum = "B"; 
 break;
 case 12: $randNum = "C"; 
 break;
 case 13: $randNum = "D"; 
 break;
 case 14: $randNum = "E"; 
 break;
 case 15: $randNum = "F"; 
 break; 
 }
 $str .= $randNum;
 }
 return $str;
}

function dias_pasados($fecha_inicial,$fecha_final)
{
$dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
$dias = abs($dias); $dias = floor($dias);
return $dias;
}

function permisos($accion, $cadena)
{
   include ("conexion.php");
   
   if($_SESSION["perfil"]==1)
   {
      $regresa=$cadena;
   }
   else
   {
      $ResPermiso=mysqli_fetch_array(mysqli_query($conn, "SELECT Permisos FROM usuarios_perfiles WHERE Id='".$_SESSION["perfil"]."' LIMIT 1"));

      if(strpos($ResPermiso["Permisos"], '|'.$accion.'|') != false)
      {
         $regresa=$cadena;
      }
      else
      {
         $regresa='';
      }
   }
   

   return $regresa;
}

function monto_reservacion($reservacion)
{
   include ("conexion.php");

   $ResRP=mysqli_fetch_array(mysqli_query($conn, "SELECT Dias FROM reservacion WHERE Id='".$reservacion."' LIMIT 1"));

   $dpaci=mysqli_num_rows(mysqli_query($conn, "SELECT IdPA FROM reservaciones WHERE IdReservacion='".$reservacion."' AND Tipo='P' AND Cama>='0' GROUP BY IdPA")); //cuantos dias esta en el albergue

   $ResPac=mysqli_fetch_array(mysqli_query($conn, "SELECT FechaNacimiento WHERE Id='".$dpaci["IdPA"]."'"));

   if($dpaci>0)
   {
      if($ResPac["FechaNacimiento"]==NULL OR $ResPac["FechaNacimiento"]=='')
      {
         $cp=25;
      }
      else
      {
         $edadp=obtener_edad_segun_fecha($ResPac["FechaNacimiento"]);
         if($edadp<=12){$cp=15;}else{$cp=25;}
      }
   }
   else
   {
      $cp=0;
   }
   
   //calculamos acompañantes
   $acomp=mysqli_num_rows(mysqli_query($conn, "SELECT COUNT(Id) AS acompanantes FROM reservaciones WHERE IdReservacion='".$reservacion."' AND Tipo='A' AND Cama>'0' GROUP BY IdPA"));
   if($acomp==0 OR $acomp==NULL){if($edadp<=12){$cp=15;}else{$cp=35;} $ca=0;}elseif($acomp>0){$ca=25*$acomp;}
   if($dpaci==0 AND $acomp==1){$ca=25*$acomp;} //solo se hospeda acompañante
   
   //calcula total paciente
   $ctp=$cp*$ResRP["Dias"];
   //calcula total acompañante
   $cta=$ca*$ResRP["Dias"];
   //calcula total a pagar
   $CT=$ctp+$cta;

   return $CT;
}

function obtenerPorcentaje($cantidad, $total) {
   $porcentaje = ((float)$cantidad * 100) / $total; // Regla de tres
   $porcentaje = round($porcentaje, 2);  // Quitar los decimales
   return $porcentaje;
}
?>