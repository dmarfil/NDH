<?php
//Archivo:             RecordSet.php
//Descripcion:         RecordSet.
//Autor:               dmarfil, marco
//Fecha de creación:   1/07/2012 
//Ultima modificación: 1/07/2012 
//Driver mysqli agregado por marco

class NdhDbRecordSet
 {
  public $driver; //Allowed Drivers mysql,mssql
  public $numFil; //Número de filas en resultado
  public $numCol; //Número de columnas
  public $afeFil; //Número de Filas afectadas por Insert, Update, Delete
  public $numId; //insert_id
  public $FillName;
  public $datos;
  public $posicion; //Puntero del recorset
  public $EOF;
  public $BOF;
  public $result;
  public $dbname;
  public $cnx;
  public $sql;
  public $DbSelected;
  public $Exception;
  //public $Exception_Msg;

  function NdhDbRecordSet()
   {
    $this->driver = "mysqli";
    $this->EOF = true;
    $this->BOF = false;
    $this->numFil = 0;
    $this->numCol = 0;
    $this->afeFil = 0;
    $this->numId  = 0;
    $this->FillName = "";
    $this->Exception=false;
    $this->DbSelected=false; // add mayo 2013
    //$this->Exception_Msg="";
   }

  
  function open()
   {
    switch ($this->driver)
     {
      case "mysql" : //Begin Mysql block
            try {
                $this->DbSelected = mysql_select_db($this->dbname,$this->cnx);
                if ($this->DbSelected === false){
                    throw new Exception("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01" . mysql_error());
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }

            try {
                $this->result = mysql_query($this->sql, $this->cnx);
                if ($this->result === false){
                    throw new Exception("Can't Query sqlError "  . mysql_error($this->cnx) ." query: " . $this->sql . " Notify Error #e02" );
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }
            //mysql_select_db($this->dbname,$this->cnx) or die ("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01" . mysql_error());
            //$this->result = mysql_query($this->sql, $this->cnx) or die ("Can't Query sqlError "  . mysql_error($this->cnx) ." query: " . $this->sql . " Notify Error #e02" );
            @$this->numFil = mysql_num_rows($this->result);
            @$this->afeFil = mysql_affected_rows($this->result);
            @$this->numCol = mysql_num_fields($this->result);
           
        break; //End Mysql block
    
      case "mysqli" : //Begin Mysqli block
            try {
                $this->DbSelected = mysqli_select_db($this->cnx,$this->dbname);
                if ($this->DbSelected === false){
                    throw new Exception("Can't Select database ". $this->dbname . " trought this " . $this->cnx . " Cnx Notify Error # E01" . mysqli_connect_error() . mysqli_error());
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }

            try {
                $this->result = mysqli_query($this->cnx, $this->sql);
                if ($this->result === false){
                    throw new Exception("Can't Query sqlError "  . mysqli_error($this->cnx) ." query: " . $this->sql . " Notify Error #e02" );
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }
            //mysqli_select_db($this->cnx,$this->dbname) or die ("Can't Select database ". $this->dbname . " trought this " . $this->cnx . " Cnx Notify Error # E01" . mysqli_connect_error() . mysqli_error());
            //$this->SetSecureData();
            //@$this->result = mysqli_query($this->cnx, $this->sql) or die ("Can't Query sqlError "  . mysqli_error($this->cnx) ." query: " . $this->sql . " Notify Error #e02" );
            @$this->numFil = mysqli_num_rows($this->result);
            @$this->afeFil = mysqli_affected_rows($this->cnx);
            @$this->numCol = mysqli_num_fields($this->result);
            @$this->numId  = mysqli_insert_id($this->cnx);
        break; //End Mysqli block
    
      case "mssql" :
            try {
                $this->DbSelected = mssql_select_db($this->dbname,$this->cnx);
                if ($this->DbSelected === false){
                    throw new Exception("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01");
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }

            try {
                $this->result = mssql_query($this->sql, $this->cnx);
                if ($this->result === false){
                    throw new Exception("Can't Query " . $this->sql . " Notify Error #e02");
                }
            }
            catch (Exception $e) {
                $this->Exception =  $e->getMessage();
            }
        //mssql_select_db($this->dbname,$this->cnx) or die ("Can't Select database ". $this->dbname . "trought this " . $this->cnx . " Cnx Notify Error # E01");
        //$this->result = mssql_query($this->sql, $this->cnx) or die ("Can't Query " . $this->sql . " Notify Error #e02");
        @$this->numFil = mssql_num_rows($this->result);
        @$this->afeFil = mssql_rows_affected($this->result);
        @$this->numCol = mssql_num_fields($this->result);
       break;
     }
    if ($this->numFil<=1)
     {
      $this->EOF = true;
     }
    else
     {
      $this->EOF = false;
     }
    $this->posicion = 0;
   }

  function execute()
   {
    $this->open($this->dbname, $this->sql, $this->cnx);
   }

  function getFieldName($pos)
   {
    $this->FillName = "";
    switch ($this->driver)
     {
      case "mysql" : //Begin Mysql block
        $this->FillName = mysql_field_name($this->result,$pos);
        break; //End Mysql block
      case "mysqli" : //Begin Mysqli block
        $arr =  mysqli_fetch_field_direct($this->result,$pos);
        $this->FillName = $arr->name;
        break; //End Mysqli block
      case "mssql" :
        $this->FillName = mssql_field_name($this->result,$pos);
        break;
     }
     return;
   }

  function getReg()
   {
     $this->datos = null;
    switch ($this->driver)
     {
      case "mysql" : //Begin Mysql block
    	 @mysql_data_seek($this->result,$this->posicion);       
         $this->datos = mysql_fetch_array($this->result,MYSQL_BOTH)or die(mysql_error($this->cnx));//<=
        break; //End Mysql block
    
      case "mysqli" : //Begin Mysqli block
    	 @mysqli_data_seek($this->result,$this->posicion);//<=
          //if(mysqli_num_rows($this->result)>0)
         $this->datos = mysqli_fetch_array($this->result, MYSQLI_BOTH) or die(mysqli_error($this->cnx));//<=
        break; //End Mysqli block
    
      case "mssql" :
    	 @mssql_data_seek($this->result,$this->posicion);
         $this->datos = mssql_fetch_array($this->result);
        break;
     }
   }


  function getObj()
   {
    switch ($this->driver)
     {
      case "mysql" : //Begin Mysql block
        @mysql_data_seek($this->result,$this->posicion);
        return mysql_fetch_object($this->result);
        break; //End Mysql blockç
    
      case "mysqli" : //Begin Mysqli block
        @mysqli_data_seek($this->result,$this->posicion);//<=
        return mysqli_fetch_object($this->result);
        break; //End Mysqli block
    
      case "mssql" :
        @mssql_data_seek($this->result,$this->posicion);
        return mssql_fetch_object($this->result);
        break;
     }
   }



  function moveFirst()
   {
    $this->posicion = 0;
    $this->EOF = false;
    $this->getReg();
   }

   function moveLast()
   {
    $this->posicion = $this->numFil-1;
    $this->EOF = false;
    $this->getReg();
   }


   function moveNext()
   {
    if ($this->posicion < $this->numFil-1)
     {
      $this->posicion++;
      $this->getReg();
     }
    else
     {
      $this->EOF = true;
     }
   }


   function movePrevius()
   {
    if ($this->posicion > 0)
     {
      $this->posicion--;
      $this->getReg();
     }
    else
     {
      $this->BOF = true;
     }
   }



  function find($campo, $valor)
   {
    $this->moveFirst();
    $encontrado = false;
    while ($this->EOF!=true)
     {
      if ($this->datos["$campo"]==$valor)
       {
        $encontrado = true;
        break;
       }
      $this->moveNext();
     }
    return $encontrado;
   }

  function close()
   {
    switch ($this->driver)
     {
      case "mysql" : //Begin Mysql block
        @mysql_free_result($this->result);
        break; //End Mysql block
    
      case "mysqli" : //Begin Mysqli block
        @mysqli_free_result($this->result);
        break; //End Mysqli block
    
      case "mssql" :
        @mssql_free_result($this->result);
        break;
     }

   }
 }// end class
