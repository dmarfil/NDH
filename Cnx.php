<?php
//Archivo:             Cnx.php
//Descripcion:         Clase que establece la conexión a la base de datos
//Autor:               dmarfil, marco
//Fecha de creación:   1/07/2012 
//Ultima modificación: 14/11/2012 
//Driver mysqli agregado por marco
// se agrego funcion setcollate by dperez

class NdhDbCnx
 {
  public $driver; //Allowed Drivers mysql,mssql,mysqli
  public $cnx;
  public $host;
  public $user;
  public $pass;
  public $port;
  public $collate;
  public $Exception;
  public $Language;

  function NdhDbCnx()
   {
    $this->driver="mysqli";
    $this->cnx = null;
    $this->host = "localhost";
    $this->user = "root";
    $this->pass = "";
    $this->collate = "utf8";
    $this->dbname = "";
    $this->Exception = "";
    $this->Language = "es_MX";	
    switch ($this->driver)
     {
      case "mysql":
	    $this->port = 3306;
        break;
      case "mysqli":
	    $this->port = 3306;
       break;
      case "mssql":
	   $this->port = "";
       break;
           
     }
   }

  function open()
   {
    switch ($this->driver)
     {
      case "mysql" :
          try {
              @$this->cnx = mysql_connect($this->host . ":" . $this->port,$this->user,$this->pass);
              if ($this->cnx === false){
                throw new Exception('No se pudo conectar');
              }
            }
            catch (Exception $e){
               /* ... add logging stuff there if you need ... */
                $this->Exception = "Can't Open Conection to " . $this->host . " " . $e->getMessage();
            }
        break;
      case "mysqli":
          try {
              @$this->cnx = mysqli_connect($this->host,$this->user,$this->pass);
              if ($this->cnx === false){
                throw new Exception('No se pudo conectar');
              }
            }
            catch (Exception $e){
               /* ... add logging stuff there if you need ... */
                $this->Exception = "Can't Open Conection to " . $this->host . " " . $e->getMessage();
            }
       break;
      case "mssql" :
          try {
              @$this->cnx = mssql_connect($this->host,$this->user,$this->pass);
              if ($this->cnx === false){
                throw new Exception('No se pudo conectar');
              }
            }
            catch (Exception $e){
               /* ... add logging stuff there if you need ... */
                $this->Exception = "Can't Open Conection to " . $this->host . " " . $e->getMessage();
            }
       break;
     }
   }

   function close()
   {
    switch ($this->driver)
     {
      case "mysql" :
        mysql_close($this->cnx);
        break;
      case "mysqli" :
        mysqli_close($this->cnx);
       break;
      case "mssql" :
        mssql_close($this->cnx);
       break;
     }
   } 
   
function SetCollate() {
    switch ($this->driver)
     {
      case "mysql" :
        if (!$this->Exception) {
            mysql_select_db($this->dbname,$this->cnx) or die ("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01" . mysql_error());
            mysql_query("SET NAMES '" . $this->collate . "'", $this->cnx ) or die(mysql_error());
        }
        break;
      case "mysqli" :
        if (!$this->Exception) {
            mysqli_select_db($this->cnx,$this->dbname) or die ("Can't Select database ". $this->dbname . " trought this  Cnx Notify Error # E01" );
            mysqli_query($this->cnx  ,"SET NAMES '" . $this->collate . "'" ) or die(mysqli_error());
            mysqli_query($this->cnx  ,"SET lc_time_names = '" . $this->Language . "'" ) or die(mysqli_error());
        }   
       break;
      case "mssql" :
        if (!$this->Exception) {  
            mssql_select_db($this->dbname,$this->cnx) or die ("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01");
            mssql_query("SET NAMES '" . $this->collate . "'", $this->cnx ) or die ("Can't Query " . $this->sql . " Notify Error #e02");
        }
       break;
     }
              //or die(mysql_error($this->cnx));   
   }
 }
 
