<?php
//Inicio la sesion 
session_start();

include('../conexion.php');
include('../funciones.php');

$mensaje='';

//acciones
if(isset($_POST["hacer"]))
{
    //agregar paciente
	if($_POST["hacer"]=='addpaciente')
	{
        if($_POST["indigena"]==''){$_POST["indigena"]=0;}
        if($_POST["discapacitado"]==''){$_POST["discapacitado"]=0;}

        if($_POST["numpaciente"]=='')
        {
            mysqli_query($conn, "INSERT INTO pacientes (NivelSocioeconomico, Nombre, Apellidos, Apellidos2, Curp, Sexo, FechaNacimiento, LugarNacimiento, Talla, Peso, Domicilio, CP, Colonia, Estado, Municipio, TelefonoFijo, TelefonoCelular, 
                                                    TelefonoContacto, Religion, EdoCivil, Ocupacion, Escolaridad, NivelEscolaridad, Lengua, HablaEspanol, CorreoE, ClaveINE, Indigena, Discapacitado, Instituto1, Carnet1, 
                                                    Diagnostico1, Instituto2, Carnet2, Diagnostico2, Instituto3, Carnet3, Diagnostico3, ViveCon, RecibeAyuda, Recibio, Observaciones, FechaRegistro)
									        VALUES ('".$_POST["n_socio"]."', '".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["apellidos"])."', '".strtoupper($_POST["apellidos2"])."', '".strtoupper($_POST["curp"])."', '".$_POST["sexo"]."', '".$_POST["fnacimiento"]."', 
                                                    '".$_POST["l_nacimiento"]."', '".$_POST["talla"]."', '".$_POST["peso"]."', '".strtoupper($_POST["domicilio"])."', '".$_POST["cp"]."', '".strtoupper($_POST["colonia"])."', '".$_POST["estado"]."', '".$_POST["municipio"]."',
                                                    '".$_POST["telefono_fijo"]."', '".$_POST["telefono_celular"]."', '".$_POST["telefono_contacto"]."', '".$_POST["religion"]."', '".$_POST["edocivil"]."', '".$_POST["ocupacion"]."', 
                                                    '".$_POST["escolaridad"]."', '".$_POST["nivel_escolaridad"]."', '".$_POST["lengua"]."', '".$_POST["habla_espanol"]."', '".$_POST["correoe"]."', '".strtoupper($_POST["claveine"])."', 
                                                    '".$_POST["indigena"]."', '".$_POST["discapacitado"]."', '".$_POST["instituto1"]."', '".$_POST["carnet1"]."', '".strtoupper($_POST["diagnostico1"])."', '".$_POST["instituto2"]."', 
                                                    '".$_POST["carnet2"]."', '".strtoupper($_POST["diagnostico2"])."', '".$_POST["instituto3"]."', '".$_POST["carnet3"]."', '".strtoupper($_POST["diagnostico3"])."','".$_POST["vivecon"]."', '".$_POST["recibe_ayuda"]."', 
                                                    '".$_POST["recibio"]."', '".strtoupper($_POST["observaciones"])."', '".date("Y-m-d")."')") or die(mysqli_error($conn));

            $ResNumPac=mysqli_fetch_array(mysqli_query($conn, "SELECT Id FROM pacientes WHERE Curp='".$_POST["curp"]."' ORDER BY Id DESC LIMIT 1"));
            $idp=$ResNumPac["Id"];
        }
        else
        {
            mysqli_query($conn, "INSERT INTO pacientes (Id, NivelSocioeconomico, Nombre, Apellidos, Apellidos2, Curp, Sexo, FechaNacimiento, LugarNacimiento, Talla, Peso, Domicilio, CP, Colonia, Estado, Municipio, TelefonoFijo, TelefonoCelular, 
                                                    TelefonoContacto, Religion, EdoCivil, Ocupacion, Escolaridad, NivelEscolaridad, Lengua, HablaEspanol, Instituto1, Carnet1, Diagnostico1, 
                                                    Instituto2, Carnet2, Diagnostico2, Instituto3, Carnet3, Diagnostico3, ViveCon, RecibeAyuda, Recibio, Observaciones, FechaRegistro)
									        VALUES ('".$_POST["numpaciente"]."', '".$_POST["n_socio"]."', '".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["apellidos"])."', '".strtoupper($_POST["apellidos2"])."', '".strtoupper($_POST["curp"])."', '".$_POST["sexo"]."', '".$_POST["fnacimiento"]."', 
                                                    '".$_POST["l_nacimiento"]."', '".$_POST["talla"]."',
                                                    '".$_POST["peso"]."', '".strtoupper($_POST["domicilio"])."', '".$_POST["cp"]."', '".strtoupper($_POST["colonia"])."', '".$_POST["estado"]."', '".$_POST["municipio"]."',
                                                    '".$_POST["telefono_fijo"]."', '".$_POST["telefono_celular"]."', '".$_POST["telefono_contacto"]."', '".$_POST["religion"]."', 
                                                    '".$_POST["edocivil"]."', '".$_POST["ocupacion"]."', '".$_POST["escolaridad"]."', '".$_POST["nivel_escolaridad"]."', '".$_POST["lengua"]."', 
                                                    '".$_POST["habla_espanol"]."', '".$_POST["instituto1"]."', '".$_POST["carnet1"]."', '".strtoupper($_POST["diagnostico1"])."', '".$_POST["instituto2"]."', '".$_POST["carnet2"]."', '".strtoupper($_POST["diagnostico2"])."', 
                                                    '".$_POST["instituto3"]."', '".$_POST["carnet3"]."', '".strtoupper($_POST["diagnostico3"])."','".$_POST["vivecon"]."', '".$_POST["recibe_ayuda"]."', '".$_POST["recibio"]."', 
                                                    '".strtoupper($_POST["observaciones"])."', '".date("Y-m-d")."')") or die(mysqli_error($conn));
                                                
            $idp=$_POST["numpaciente"];
        }
        
        //registra documentos
        if($_POST["doc_ine"]!=1){$_POST["doc_ine"]=0;}
        if($_POST["doc_carnet"]!=1){$_POST["doc_carnet"]=0;}
        if($_POST["doc_resumen"]!=1){$_POST["doc_resumen"]=0;}
        if($_POST["doc_ine_aco"]!=1){$_POST["doc_ine_aco"]=0;}
        if($_POST["doc_carta_resp"]!=1){$_POST["doc_carta_resp"]=0;}
        if($_POST["doc_reglamento"]!=1){$_POST["doc_reglamento"]=0;}
        
        mysqli_query($conn, "INSERT INTO documentos (IdPaciente, IneP, CarnetHospital, ResumenMedico, IneA, CartaResponsiva, Reglamento)
                                            VALUES ('".$idp."', '".$_POST["doc_ine"]."', '".$_POST["doc_carnet"]."', '".$_POST["doc_resumen"]."', 
                                                    '".$_POST["doc_ine_aco"]."', '".$_POST["doc_carta_resp"]."', '".$_POST["doc_reglamento"]."')");


		$mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se agrego el paciente '.$_POST["nombre"].' '.$_POST["apellidos"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '23', '".json_encode($_POST)."')");
	}
    //editar paciente
    if($_POST["hacer"]=='editpaciente')
    {
        if($_POST["fallecido"]!=1){$f=0; $fm=date("Y-m-d");}else{$f=1;$fm=$_POST["fmuerte"];}

        if($_POST["habla_espanol"]!=1){$he=0;}else{$he=1;}

        if($_POST["indigena"]==''){$_POST["indigena"]=0;}
        if($_POST["discapacitado"]==''){$_POST["discapacitado"]=0;}

        mysqli_query($conn, "UPDATE pacientes SET NivelSocioeconomico='".$_POST["n_socio"]."', 
                                                    Nombre='".strtoupper($_POST["nombre"])."', 
                                                    Apellidos='".strtoupper($_POST["apellidos"])."', 
                                                    Apellidos2='".strtoupper($_POST["apellidos2"])."', 
                                                    Curp='".strtoupper($_POST["curp"])."',
                                                    Sexo='".$_POST["sexo"]."', 
                                                    FechaNacimiento='".$_POST["fnacimiento"]."', 
                                                    LugarNacimiento='".$_POST["l_nacimiento"]."',
                                                    Talla='".$_POST["talla"]."', 
                                                    Peso='".$_POST["peso"]."', 
                                                    Domicilio='".strtoupper($_POST["domicilio"])."', 
                                                    CP='".$_POST["cp"]."', 
                                                    Colonia='".strtoupper($_POST["colonia"])."', 
                                                    Estado='".$_POST["estado"]."', 
                                                    Municipio='".$_POST["municipio"]."', 
                                                    TelefonoFijo='".$_POST["telefono_fijo"]."', 
                                                    TelefonoCelular='".$_POST["telefono_celular"]."', 
                                                    TelefonoContacto='".$_POST["telefono_contacto"]."', 
                                                    Religion='".$_POST["religion"]."', 
                                                    EdoCivil='".$_POST["edocivil"]."', 
                                                    Ocupacion='".$_POST["ocupacion"]."', 
                                                    Escolaridad='".$_POST["escolaridad"]."', 
                                                    NivelEscolaridad='".$_POST["nivel_escolaridad"]."',
                                                    Lengua='".$_POST["lengua"]."', 
                                                    HablaEspanol='".$he."', 
                                                    CorreoE='".$_POST["correoe"]."',
                                                    ClaveINE='".strtoupper($_POST["claveine"])."', 
                                                    Indigena='".$_POST["indigena"]."', 
                                                    Discapacitado='".$_POST["discapacitado"]."',
                                                    Instituto1='".$_POST["instituto1"]."', 
                                                    Carnet1='".$_POST["carnet1"]."',
                                                    Diagnostico1='".strtoupper($_POST["diagnostico1"])."', 
                                                    Instituto2='".$_POST["instituto2"]."', 
                                                    Carnet2='".$_POST["carnet2"]."', 
                                                    Diagnostico2='".strtoupper($_POST["diagnostico2"])."', 
                                                    Instituto3='".$_POST["instituto3"]."', 
                                                    Carnet3='".$_POST["carnet3"]."',
                                                    Diagnostico3='".strtoupper($_POST["diagnostico3"])."', 
                                                    ViveCon='".$_POST["vivecon"]."', 
                                                    RecibeAyuda='".$_POST["recibe_ayuda"]."', 
                                                    Recibio='".$_POST["recibio"]."', 
                                                    Observaciones='".strtoupper($_POST["observaciones"])."',
                                                    Fallecido='".$f."',
                                                    FechaMuerte='".$fm."'
                                                WHERE Id='".$_POST["idpaciente"]."'") or die(mysqli_error($conn));

        //registra documentos
        $ResD=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM documentos WHERE IdPaciente='".$_POST["idpaciente"]."'"));

        if($_POST["doc_ine"]!=1){$_POST["doc_ine"]=0;}
        if($_POST["doc_carnet"]!=1){$_POST["doc_carnet"]=0;}
        if($_POST["doc_resumen"]!=1){$_POST["doc_resumen"]=0;}
        if($_POST["doc_ine_aco"]!=1){$_POST["doc_ine_aco"]=0;}
        if($_POST["doc_carta_resp"]!=1){$_POST["doc_carta_resp"]=0;}
        if($_POST["doc_reglamento"]!=1){$_POST["doc_reglamento"]=0;}

        if($ResD==0)
        {
            mysqli_query($conn, "INSERT INTO documentos (IdPaciente, IneP, CarnetHospital, ResumenMedico, IneA, CartaResponsiva, Reglamento)
                                            VALUES ('".$_POST["idpaciente"]."', '".$_POST["doc_ine"]."', '".$_POST["doc_carnet"]."', '".$_POST["doc_resumen"]."', 
                                                    '".$_POST["doc_ine_aco"]."', '".$_POST["doc_carta_resp"]."', '".$_POST["doc_reglamento"]."')") or die(mysqli_error($conn));
        }
        elseif($ResD>0)
        {
            mysqli_query($conn, "UPDATE documentos SET IneP='".$_POST["doc_ine"]."',
                                                    CarnetHospital='".$_POST["doc_carnet"]."',
                                                    ResumenMedico='".$_POST["doc_resumen"]."',
                                                    IneA='".$_POST["doc_ine_aco"]."',
                                                    CartaResponsiva='".$_POST["doc_carta_resp"]."',
                                                    Reglamento='".$_POST["doc_reglamento"]."'
                                            WHERE IdPaciente='".$_POST["idpaciente"]."'") or die(mysqli_error($conn));
        }
        


        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo el paciente '.$_POST["nombre"].' '.$_POST["apellidos"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '24', '".json_encode($_POST)."')");
    }
    //foto_paciente
    if($_POST["hacer"]=='adfoto')
    {
        //carga el archivo
        if($_FILES['fpaci']!='')
        {
            $nombre_archivo_r ='P_'.$_POST["idpaciente"].'_'.$_FILES['fpaci']['name']; 

            if (is_uploaded_file($_FILES['fpaci']['tmp_name']))
            { 
                if(copy($_FILES['fpaci']['tmp_name'], './fotos/'.$nombre_archivo_r))
                {
                    $copyfile=1;
                }
                else
                {
                    $copyfile=2;
                }
            }
            else
            {
                $copyfile=3;
            }
        }
        if($copyfile==1)
        {
            //revisa si hay logo anterior, y si existe lo borra
            $ResFileAnt=mysqli_fetch_array(mysqli_query($conn, "SELECT Foto FROM pacientes WHERE Id='".$_POST["idpaciente"]."' LIMIT 1"));
            if($ResFileAnt["Foto"]!=NULL)
            {
                unlink('fotos/'.$ResFileAnt["Foto"]);
            }
            //actualiza la base de datos con el nuevo logo
            mysqli_query($conn, "UPDATE pacientes SET Foto='".$nombre_archivo_r."' WHERE Id='".$_POST["idpaciente"]."'");

            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se actualizo la foto del paciente</div>';

            //bitacora
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '25', '".json_encode($_POST)."')");
            
        }
        else{
            $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-exclamation-triangle"></i> Ocurrio un error, no se pudo cargar el archivo, intente nuevamente</div>'; 
        }

    }
    //datos de factura del paciente
    if($_POST["hacer"]=='addfactpaciente')
    {
        if($_POST["facturara"]==1)
        {
            mysqli_query($conn, "UPDATE pacientes SET facturara='1' WHERE Id='".$_POST["idpaciente"]."'");

            mysqli_query($conn, "INSERT INTO facturaraper (Nombre, Rfc, Direccion, Colonia, Municipio, CP, Estado, Telefono, CorreoE, IdPaciente)
                                                    VALUES ('".strtoupper($_POST["nombre"])."', '".strtoupper($_POST["rfc"])."', '".strtoupper($_POST["direccion"])."', '".strtoupper($_POST["colonia"])."',
                                                            '".$_POST["municipio"]."', '".$_POST["cp"]."', '".$_POST["Estado"]."', '".$_POST["Telefono"]."',
                                                            '".$_POST["email"]."', '".$_POST["idpaciente"]."')");
        }
        elseif($_POST["facturara"]>1)
        {
            mysqli_query($conn, "UPDATE pacientes SET facturara='".$_POST["facturara"]."' WHERE Id='".$_POST["idpaciente"]."'");
        }
        
        $ResPacF=mysqli_fetch_array(mysqli_query($conn, "SELECT Nombre, Apellidos FROM pacientes WHERE Id='".$_POST["idpaciente"]."'"));

        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se registro la facturación del paciente '.$ResPacF["Nombre"].' '.$ResPacF["Apellidos"].'</div>';

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '26', '".json_encode($_POST)."')");
    }

    //guardar estudio socioeconomico
    if($_POST["hacer"]=='guardar_es')
    {
        $es=0;

        //datos paciente
        mysqli_query($conn, "UPDATE pacientes SET NivelSocioeconomico='".$_POST["n_socio"]."',
                                                Carnet1='".$_POST["carnet1"]."', 
                                                Nombre='".strtoupper($_POST["nombre"])."', 
                                                Apellidos='".strtoupper($_POST["apellidos"])."', 
                                                FechaNacimiento='".$_POST["fnacimiento"]."', 
                                                EdoCivil='".$_POST["edocivil"]."', 
                                                Escolaridad='".$_POST["escolaridad"]."', 
                                                NivelEscolaridad='".$_POST["nivel_escolaridad"]."', 
                                                Domicilio='".$_POST["domicilio"]."', 
                                                CP='".$_POST["cp"]."', 
                                                Colonia='".$_POST["colonia"]."', 
                                                Estado='".$_POST["estado"]."', 
                                                Municipio='".$_POST["municipio"]."', 
                                                TelefonoFijo='".$_POST["telefono_fijo"]."', 
                                                TelefonoCelular='".$_POST["telefono_celular"]."', 
                                                TelefonoContacto='".$_POST["telefono_contacto"]."',
                                                Instituto1='".$_POST["instituto1"]."', 
                                                Diagnostico1='".strtoupper($_POST["diagnostico1"])."'
                                        WHERE Id='".$_POST["paciente"]."'") or die(mysqli_error($conn));

        //bitacora
        mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '29', '".json_encode($_POST)."')");

        //2. Salud
        $ResSalud=mysqli_query($conn, "SELECT Id FROM es_salud WHERE IdPaciente='".$_POST["paciente"]."'");

        if(!isset($_POST["doc_ine"])){$_POST["doc_ine"]=0;}
        if(!isset($_POST["doc_carnet"])){$_POST["doc_carnet"]=0;}
        if(!isset($_POST["doc_resumen"])){$_POST["doc_resumen"]=0;}
        if(!isset($_POST["doc_ine_aco"])){$_POST["doc_ine_aco"]=0;}

        if(mysqli_num_rows($ResSalud)==0)
        {
            
            mysqli_query($conn, "INSERT INTO es_salud (IdPaciente, AntecedentesEnfermedad, Tratamiento, FrecuenciaHospital, ServicioMedico, IneP, CarnetHospital, ResumenMedico, IneA, Fecha, Usuario)
                                                VALUES ('".$_POST["paciente"]."', '".strtoupper($_POST["antecedentes_enfermedad"])."', '".strtoupper($_POST["tratamiento_enfermedad"])."', '".strtoupper($_POST["frecuencia_hospital"])."', 
                                                        '".$_POST["sm_cuenta"]."', '".$_POST["doc_ine"]."', '".$_POST["doc_carnet"]."', '".$_POST["doc_resumen"]."', '".$_POST["doc_ine_aco"]."', 
                                                        '".date("Y-m-d")."', '".$_SESSION["Id"]."')") or die(mysqli_error($conn));

            $es=1;
        }
        else
        {
            mysqli_query($conn, "UPDATE es_salud SET AntecedentesEnfermedad='".strtoupper($_POST["antecedentes_enfermedad"])."',
                                                    Tratamiento='".strtoupper($_POST["tratamiento_enfermedad"])."',
                                                    FrecuenciaHospital='".strtoupper($_POST["frecuencia_hospital"])."',
                                                    ServicioMedico='".$_POST["sm_cuenta"]."',
                                                    IneP='".$_POST["doc_ine"]."',
                                                    CarnetHospital='".$_POST["doc_carnet"]."',
                                                    ResumenMedico='".$_POST["doc_resumen"]."',
                                                    IneA='".$_POST["doc_ine_aco"]."',
                                                    Fecha='".date("Y-m-d")."', 
                                                    Usuario='".$_SESSION["Id"]."'
                                            WHERE IdPaciente='".$_POST["paciente"]."'") or die(mysqli_error($conn));

            $es=2;
        }

        //4.situacion economica
        $ResSEIngresos=mysqli_query($conn, "SELECT Id FROM es_situacioneconomica_ingreso WHERE IdPaciente='".$_POST["paciente"]."'");

        if(mysqli_num_rows($ResSEIngresos)==0)
        {
            mysqli_query($conn, "INSERT INTO es_situacioneconomica_ingreso (IdPaciente, Esposo, OcupacionE, PadreMadre, OcupacionPM, Hijos, OcupacionH, Otros, OcupacionO, 
                                                                            Usuario, Fecha)
                                                                    VALUES ('".$_POST["paciente"]."', '".$_POST["ingreso_esposo"]."', '".strtoupper($_POST["ocupacion_esposo"])."', '".$_POST["ingreso_padremadre"]."', 
                                                                            '".strtoupper($_POST["ocupacion_padremadre"])."', '".$_POST["ingreso_hijos"]."', '".strtoupper($_POST["ocupacion_hijos"])."', '".$_POST["ingreso_otros"]."', 
                                                                            '".strtoupper($_POST["ocupacion_otros"])."', '".$_SESSION["Id"]."', '".date("Y-m-d")."')") or die(mysqli_error($conn));
        
        }
        else
        {
            mysqli_query($conn, "UPDATE es_situacioneconomica_ingreso SET Esposo='".$_POST["ingreso_esposo"]."',
                                                                            OcupacionE='".strtoupper($_POST["ocupacion_esposo"])."',
                                                                            PadreMadre='".$_POST["ingreso_padremadre"]."', 
                                                                            OcupacionPM='".strtoupper($_POST["ocupacion_padremadre"])."',
                                                                            Hijos='".$_POST["ingreso_hijos"]."',
                                                                            OcupacionH='".strtoupper($_POST["ocupacion_hijos"])."', 
                                                                            Otros='".$_POST["ingreso_otros"]."',
                                                                            OcupacionO='".strtoupper($_POST["ocupacion_otros"])."', 
                                                                            Usuario='".$_SESSION["Id"]."', 
                                                                            Fecha='".date("Y-m-d")."' 
                                                                    WHERE IdPaciente='".$_POST["paciente"]."'") or die(mysqli_error($conn));
        }

        $ResSEEgresos=mysqli_query($conn, "SELECT Id FROM es_situacioneconomica_egreso WHERE IdPaciente='".$_POST["paciente"]."'");

        if(mysqli_num_rows($ResSEEgresos)==0)
        {
            mysqli_query($conn, "INSERT INTO es_situacioneconomica_egreso (IdPaciente, Alimentacion, Transporte, Renta, Gas, Telefono, Agua, ServicioMedico, Luz, Medicamentos, Otros, 
                                                                            Usuario, Fecha)
                                                                    VALUES ('".$_POST["paciente"]."', '".$_POST["egreso_alimentacion"]."', '".$_POST["egreso_transporte"]."', '".$_POST["egreso_renta"]."', 
                                                                            '".$_POST["egreso_gas"]."', '".$_POST["egreso_telefono"]."', '".$_POST["egreso_agua"]."', '".$_POST["egreso_medico"]."', '".$_POST["egreso_luz"]."', 
                                                                            '".$_POST["egreso_medicamentos"]."', '".$_POST["egreso_otros"]."', '".$_SESSION["Id"]."', '".date("Y-m-d")."')") or die(mysqli_error($conn));
        }
        else
        {
            mysqli_query($conn, "UPDATE es_situacioneconomica_egreso SET Alimentacion='".$_POST["egreso_alimentacion"]."', 
                                                                        Transporte='".$_POST["egreso_transporte"]."',
                                                                        Renta='".$_POST["egreso_renta"]."', 
                                                                        Gas='".$_POST["egreso_gas"]."', 
                                                                        Telefono='".$_POST["egreso_telefono"]."', 
                                                                        Agua='".$_POST["egreso_agua"]."', 
                                                                        ServicioMedico='".$_POST["egreso_medico"]."',
                                                                        Luz='".$_POST["egreso_luz"]."',
                                                                        Medicamentos='".$_POST["egreso_medicamentos"]."', 
                                                                        Otros='".$_POST["egreso_otros"]."', 
                                                                        Usuario='".$_SESSION["Id"]."', 
                                                                        Fecha='".date("Y-m-d")."' 
                                                                WHERE IdPaciente='".$_POST["paciente"]."'") or die(mysqli_error($conn));
        }

        //5. Descripcion de la vivienda

        $ResViv=mysqli_query($conn, "SELECT Id FROM es_vivienda WHERE IdPaciente='".$_POST["paciente"]."'");

        if(mysqli_num_rows($ResViv)==0)
        {
            mysqli_query($conn, "INSERT INTO es_vivienda (IdPaciente, TipoVivienda, OtroTV, Vivienda, OtroV, Material, OtroM, Paredes, OtroP, Techo, OtroT, 
                                                            Comedor, Cocina, Bannos, Recamaras, Usuario, Fecha)
                                                    VALUES ('".$_POST["paciente"]."', '".$_POST["tipo_vivienda"]."', '".strtoupper($_POST["tipo_vivienda_otro"])."', 
                                                            '".$_POST["vivienda"]."', '".strtoupper($_POST["vivienda_otro"])."', '".$_POST["material"]."', '".strtoupper($_POST["material_otro"])."', 
                                                            '".$_POST["paredes"]."', '".strtoupper($_POST["paredes_otro"])."', '".$_POST["techo"]."', '".strtoupper($_POST["techo_otro"])."', 
                                                            '".$_POST["comedor"]."', '".$_POST["cocina"]."', '".$_POST["bano"]."', '".$_POST["recamaras"]."', 
                                                            '".$_SESSION["Id"]."', '".date("Y-m-d")."')") or die(mysqli_error($conn));
        }
        else
        {
            mysqli_query($conn, "UPDATE es_vivienda SET TipoVivienda='".$_POST["tipo_vivienda"]."',
                                                        OtroTV='".strtoupper($_POST["tipo_vivienda_otro"])."', 
                                                        Vivienda='".$_POST["vivienda"]."', 
                                                        OtroV='".strtoupper($_POST["vivienda_otro"])."', 
                                                        Material='".$_POST["material"]."', 
                                                        OtroM='".strtoupper($_POST["material_otro"])."', 
                                                        Paredes='".$_POST["paredes"]."', 
                                                        OtroP='".strtoupper($_POST["paredes_otro"])."', 
                                                        Techo='".$_POST["techo"]."', 
                                                        OtroT='".strtoupper($_POST["techo_otro"])."', 
                                                        Comedor='".$_POST["comedor"]."', 
                                                        Cocina='".$_POST["cocina"]."', 
                                                        Bannos='".$_POST["bano"]."', 
                                                        Recamaras='".$_POST["recamaras"]."', 
                                                        Usuario='".$_SESSION["Id"]."', 
                                                        Fecha= '".date("Y-m-d")."' 
                                                WHERE IdPaciente='".$_POST["paciente"]."'") or die(mysqli_error($conn));
        }

        //6. diagnostico social
        $ResDS=mysqli_query($conn, "SELECT Id FROM es_diagnosticosocial WHERE IdPaciente='". $_POST["paciente"]."'");

        if(mysqli_num_rows($ResDS)==0)
        {
            mysqli_query($conn, "INSERT INTO es_diagnosticosocial (IdPaciente, Diagnostico, IdAcompannante,  Usuario, Fecha)
                                                            VALUES ('".$_POST["paciente"]."', '".$_POST["observaciones"]."', '".$_POST["acompannante"]."',
                                                                    '".$_SESSION["Id"]."', '".date("Y-m-d")."')")or die(mysqli_error($conn));

        }
        else
        {
            mysqli_query($conn, "UPDATE es_diagnosticosocial SET Diagnostico='".$_POST["observaciones"]."',
                                                                IdAcompannante='".$_POST["acompannante"]."',
                                                                Usuario='".$_SESSION["Id"]."', 
                                                                Fecha='".date("Y-m-d")."' 
                                                        WHERE IdPaciente='".$_POST["paciente"]."'") or die(mysqli_error($conn));
        }


        $mensaje='<div class="mesaje" id="mesaje"><i class="fas fa-thumbs-up"></i> Se guardo el estudio socioeconomico</div>';

        //bitacora
        if($es==1)
        {
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '27', '".json_encode($_POST)."')");
        }
        else
        {
            mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '28', '".json_encode($_POST)."')");
        }
        
    }
}

//$mensaje="SELECT Id, Nombre, Apellidos, Sexo, Foto FROM pacientes WHERE Id LIKE '".$_POST["buscaidpaciente"]."' OR concat_ws(' ', Nombre, Apellidos) LIKE '%".$_POST["busnombrepaciente"]."%' ORDER BY Id DESC";

$cadena=$mensaje.'<form name="fbuspaciente" id="fbuspaciente" style="width:100%"><table>
            <thead>
                <tr>
                    <td><input type="number" name="buscaidpaciente" id="buscaidpaciente" placeholder="Num. Paciente"></td>
                    <td></td>
                    <td><input type="text" name="busnombrepaciente" id="busnombrepaciente" placeholder="Nombre del Paciente"></td>
                    <td colspan="3"><input type="hidden" name="buscar" id="buscar" value="1"><input type="submit" name="botbuspaciente" id="botbuspaciente" value="Buscar"></td>
                    <td colspan="6" style="text-align: right">| '.permisos(23, '<a href="#" onclick="add_paciente()" class="liga">Nuevo paciente</a>').' |</td>
                </tr>
                <tr>
                    <th colspan="12" align="center" class="textotitable">Pacientes</td>
                </tr>
                <tr>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">Nombre</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                    <th align="center" class="textotitable">&nbsp;</th>
                </tr>
            </thead>
            <tbody>';
if(!isset($_POST["buscar"]))
{
    $ResPacientes=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2, Sexo, Foto FROM pacientes ORDER BY Id DESC LIMIT 100");
}
else
{
    if($_POST["buscaidpaciente"]!='')
    {
        $ResPacientes=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2, Sexo, Foto FROM pacientes WHERE Id = '".$_POST["buscaidpaciente"]."' ORDER BY Id DESC LIMIT 100");

        //busca acompañante id
        $ResAcompanantes=mysqli_query($conn, "SELECT Id, IdPaciente FROM acompannantes WHERE Id='".$_POST["buscaidpaciente"]."' ORDER BY IdPaciente DESC LIMIT 100");
    }
    elseif($_POST["busnombrepaciente"]!='')
    {
        $ResPacientes=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Apellidos2, Sexo, Foto FROM pacientes WHERE concat_ws(' ', Nombre, Apellidos) LIKE '%".$_POST["busnombrepaciente"]."%' ORDER BY Id DESC");

        //busca acompañante nombre
        $ResAcompanantes=mysqli_query($conn, "SELECT Id, IdPaciente FROM acompannantes WHERE Nombre LIKE '%".$_POST["busnombrepaciente"]."%' ORDER BY IdPaciente DESC");
    }
    
}

$J=1; $bgcolor="#ffffff";
while($RResPac=mysqli_fetch_array($ResPacientes))
{
    $ResAmo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM amonestaciones WHERE Tipo='P' AND IdPA='".$RResPac["Id"]."'"));
    $ResRec=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reconocimientos WHERE Tipo='P' AND IdPA='".$RResPac["Id"]."'"));

    $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResPac["Id"].'</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
                    if($RResPac["Foto"]==NULL)
                    {
                        if($RResPac["Sexo"]=='F'){$cadena.='<img src="pacientes/fotos/pacientem.jpg" border="0" class="imgpac">';}
                        if($RResPac["Sexo"]=='M'){$cadena.='<img src="pacientes/fotos/pacienteh.jpg" border="0" class="imgpac">';}
                    }
                    else
                    {
                        $cadena.='<img src="pacientes/fotos/'.$RResPac["Foto"].'" border="0" class="imgpac">';
                    }
    $cadena.='      </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle"><a href="pacientes/cardex-paciente.php?idpaciente='.$RResPac["Id"].'" target="_blank">'.$RResPac["Nombre"].' '.$RResPac["Apellidos"].' '.$RResPac["Apellidos2"].'</a></td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(25, '<a href="javascript:void(0)" onclick="foto_pa(\''.$RResPac["Id"].'\')"><i class="fas fa-portrait"></i></a>').'
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(30, '<a href="pacientes/cred_paciente.php?idpaciente='.$RResPac["Id"].'" target="_blank"><i class="fas fa-id-card"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(38, '<a href="javascript:void(0)" onclick="acompannantes(\''.$RResPac["Id"].'\')"><i class="fas fa-user-friends"></i></a>').' 
					</td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(24, '<a href="javascript:void(0)" onclick="edit_paciente(\''.$RResPac["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').'
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="javascript:void(0)" '.permisos(31, 'onclick="reconocimiento_paciente(\''.$RResPac["Id"].'\')"').'><i class="fas fa-crown"';if($ResRec>0){$cadena.=' style="color:#7ac70c;"';}$cadena.='></i></a>
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						<a href="javascript:void(0)" '.permisos(33, 'onclick="amonesta_paciente(\''.$RResPac["Id"].'\')"').'><i class="fas fa-user-alt-slash"';if($ResAmo>0){$cadena.=' style="color:#c20005;"';}$cadena.='></i></a>
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
						'.permisos(26, '<a href="javascript:void(0)" onclick="factura_paciente(\''.$RResPac["Id"].'\')"><i class="fas fa-file-invoice-dollar"></i></a>').'
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(27, '<a href="javascript:void(0)" onclick="e_socioeconomico_paciente(\''.$RResPac["Id"].'\')"><i class="fas fa-file-archive"></i></a>').'
                    </td>
					<td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(35, '<a href="javascript:void(0)" onclick="limpiar(); abrirmodal(); gastos_medicos(\''.$RResPac["Id"].'\');"><i class="fas fa-file-medical"></i></a>').'
                    </td>

				</tr>';
		$J++;
		if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
		else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
}
//solo cuando busque en acompañantes
if(isset($_POST["buscar"]))
{
    while($RResA=mysqli_fetch_array($ResAcompanantes))
    {
        $ResPacientes2=mysqli_query($conn, "SELECT Id, Nombre, Apellidos, Sexo, Foto FROM pacientes WHERE Id = '".$RResA["IdPaciente"]."' ORDER BY Id DESC LIMIT 100");
    }

    while($RResPac=mysqli_fetch_array($ResPacientes2))
    {
        $ResAmo=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM amonestaciones WHERE Tipo='P' AND IdPA='".$RResPac["Id"]."'"));
        $ResRec=mysqli_num_rows(mysqli_query($conn, "SELECT Id FROM reconocimientos WHERE Tipo='P' AND IdPA='".$RResPac["Id"]."'"));

        $cadena.='	<tr style="background: '.$bgcolor.'" id="row_'.$J.'">
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">'.$RResPac["Id"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">';
                    if($RResPac["Foto"]==NULL)
                    {
                        if($RResPac["Sexo"]=='F'){$cadena.='<img src="pacientes/fotos/pacientem.jpg" border="0" class="imgpac">';}
                        if($RResPac["Sexo"]=='M'){$cadena.='<img src="pacientes/fotos/pacienteh.jpg" border="0" class="imgpac">';}
                    }
                    else
                    {
                        $cadena.='<img src="pacientes/fotos/'.$RResPac["Foto"].'" border="0" class="imgpac">';
                    }
        $cadena.='     </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="left" class="texto" valign="middle">'.$RResPac["Nombre"].' '.$RResPac["Apellidos"].'</td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(25, '<a href="javascript:void(0)" onclick="foto_pa(\''.$RResPac["Id"].'\')"><i class="fas fa-portrait"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(30, '<a href="pacientes/cred_paciente.php?idpaciente='.$RResPac["Id"].'" target="_blank"><i class="fas fa-id-card"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(38, '<a href="javascript:void(0)" onclick="acompannantes(\''.$RResPac["Id"].'\')"><i class="fas fa-user-friends"></i></a>').' 
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(24, '<a href="javascript:void(0)" onclick="edit_paciente(\''.$RResPac["Id"].'\')"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="javascript:void(0)" '.permisos(31, 'onclick="reconocimiento_paciente(\''.$RResPac["Id"].'\')"').'><i class="fas fa-crown"';if($ResRec>0){$cadena.=' style="color:#7ac70c;"';}$cadena.='></i></a>
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        <a href="javascript:void(0)" '.permisos(33, 'onclick="amonesta_paciente(\''.$RResPac["Id"].'\')"').'><i class="fas fa-user-alt-slash"';if($ResAmo>0){$cadena.=' style="color:#c20005;"';}$cadena.='></i></a>
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(26, '<a href="javascript:void(0)" onclick="factura_paciente(\''.$RResPac["Id"].'\')"><i class="fas fa-file-invoice-dollar"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(27, '<a href="javascript:void(0)" onclick="e_socioeconomico_paciente(\''.$RResPac["Id"].'\')"><i class="fas fa-file-archive"></i></a>').'
                    </td>
                    <td onmouseover="row_'.$J.'.style.background=\'#badad8\'" onmouseout="row_'.$J.'.style.background=\''.$bgcolor.'\'" align="center" class="texto" valign="middle">
                        '.permisos(35, '<a href="javascript:void(0)" onclick="limpiar(); abrirmodal(); gastos_medicos(\''.$RResPac["Id"].'\');"><i class="fas fa-file-medical"></i></a>').'
                    </td>
                </tr>';

        $J++;
        if($bgcolor=="#ffffff"){$bgcolor='#cccccc';}
        else if($bgcolor=="#cccccc"){$bgcolor="#ffffff";}
    }
}
$cadena.='  </tbody>
        </table>
        </form>';

echo $cadena;

//bitacora
mysqli_query($conn, "INSERT INTO bitacora (FechaHora, IdUser, Hizo, Datos) VALUES ('".time()."', '".$_SESSION["Id"]."', '22', '".json_encode($_POST)."')");

?>
<script>
function add_paciente(){
	$.ajax({
				type: 'POST',
				url : 'pacientes/add_paciente.php'
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function edit_paciente(paciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/edit_paciente.php',
                data: 'paciente=' + paciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function acompannantes(paciente){
	$.ajax({
				type: 'POST',
				url : 'pacientes/acompannantes.php',
                data: 'paciente=' + paciente
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function foto_pa(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/fotopa.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function amonesta_paciente(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/amo_pacientes.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function reconocimiento_paciente(id)
{
    $.ajax({
				type: 'POST',
				url : 'pacientes/reco_paciente.php',
                data: 'id=' + id 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function factura_paciente(paciente)
{
    $.ajax({
				type: 'POST',
				url : 'caja/facturacion_paciente.php',
                data: 'paciente=' + paciente 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

$("#fbuspaciente").on("submit", function(e){
	e.preventDefault();
	var formData = new FormData(document.getElementById("fbuspaciente"));

	$.ajax({
		url: "pacientes/pacientes.php",
		type: "POST",
		dataType: "HTML",
		data: formData,
		cache: false,
		contentType: false,
		processData: false
	}).done(function(echo){
		$("#contenido").html(echo);
	});
});

function e_socioeconomico_paciente(paciente)
{
    $.ajax({
				type: 'POST',
				url : 'trabajo_social/estudio_socioeconomico.php',
                data: 'paciente=' + paciente 
	}).done (function ( info ){
		$('#contenido').html(info);
	});
}

function gastos_medicos(paciente)
{
    $.ajax({
				type: 'POST',
				url : 'trabajo_social/recibo_medico.php',
                data: 'paciente=' + paciente 
	}).done (function ( info ){
		$('#modal-body').html(info);
	});
}


//mostrar mensaje despues de los cambios
setTimeout(function() { 
    $('#mesaje').fadeOut('fast'); 
}, 1000)
</script>