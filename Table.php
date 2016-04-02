<?php
//Archivo:             Table.php
//Descripcion:         Class de tabla
//Autor:               David Perez
//Fecha de creación:   21/01/2013 
//Ultima modificación: 21/01/2013


class NdhHtmlTable extends NdhDbRecordSet {
    public $Table;
    public $TableHtml;
    public $Th;  //array de atributos  
    public $Tr;   //array de atributos  
    public $Td;    //array de atributos  
    public $NumTh;
    public $NumTd;
    public $NumTr;
    public $Echo;
    
    public function NdhHtmlTable () {
        $this->Table['Name']="tabla"  . uniqid(time()); // asigna un nombre unico
        $this->Table['Id']=$this->Table['Name'];   
        $this->NumTh=0;
        $this->NumTd=0;
        $this->NumTr=0;
        $this->TableHtml="";
        $this->Echo=true;
        $this->Tr['valign']="middle";
        //$this->Th['na']
        $this->NdhDbRecordSet();
    }
    
    public function OpenTable(){
        $atributo = "";
        $valor = "";
        $this->TableHtml="<table ";
        if ($this->Echo)  echo "<table ";
        if (sizeof($this->Table)>0) {
            foreach ($this->Table as $atributo => $valor) {
                $this->TableHtml .= " " . $atributo . "=\"". $valor . "\" ";
                if ($this->Echo)  echo " " . $atributo . "=\"". $valor . "\" ";
            }
        }
        $this->TableHtml .= " >\n";
        if ($this->Echo)  echo " >\n";
    }
    
    public function CloseTable(){ //Print
      $this->TableHtml .= "</table>\n";  
      if ($this->Echo) echo "</table>\n";
      $this->close();
   }
    
   public function OpenTr() {
        $atributo = "";
        $valor = "";
        $this->TableHtml .= "<tr ";
        if ($this->Echo) echo "<tr ";
        if (sizeof($this->Tr)>0) {
            foreach ($this->Tr as $atributo => $valor) {
                $this->TableHtml .= " " . $atributo . "=\"". $valor . "\" ";
                if ($this->Echo)  echo " " . $atributo . "=\"". $valor . "\" ";
            }
        }
        $this->TableHtml .= " >\n";
        if ($this->Echo) echo " >\n";
        if (sizeof($this->Tr)>0) {
            foreach ($this->Tr as $atributo => $valor) {
                unset ($this->Tr[$atributo]);
            }
        }   
        $this->NumTr++;
   }

   public function CloseTr() {
       $this->TableHtml .="</tr>\n";
       if ($this->Echo) echo "</tr>\n";
   }
   
   public function OpenTd() {
        $atributo = "";
        $valor = "";
        $this->TableHtml .= "<td ";
        if ($this->Echo) echo "<td ";
        if (sizeof($this->Td)>0) { 
            foreach ($this->Td as $atributo => $valor) {
                $this->TableHtml .= " " . $atributo . "=\"". $valor . "\" ";
                if ($this->Echo)  echo " " . $atributo . "=\"". $valor . "\" ";
            }
        }
        $this->TableHtml .= " >\n";
        if ($this->Echo) echo " >\n";
        if (sizeof($this->Td)>0) { 
            foreach ($this->Td as $atributo => $valor) {
                unset ($this->Td[$atributo]);
            }
        }
        $this->NumTd++;
       
   }
   
   public function CloseTd() {
       $this->TableHtml .="</td>\n";
       if ($this->Echo) echo "</td>\n";
   }
   
   
   public function AddTd() {
        $atributo = "";
        $valor = "";
        $this->TableHtml .="<td ";
        if ($this->Echo) echo "<td ";
        if (sizeof($this->Td)>0) { 
            foreach ($this->Td as $atributo => $valor) {
                if ($atributo!="TdData") {
                    $this->TableHtml .= " " . $atributo . "=\"". $valor . "\" ";
                    if ($this->Echo) echo " " . $atributo . "=\"". $valor . "\" ";
                }
            }
        }
        $this->TableHtml .= " >\n";
        if ($this->Echo) echo " >\n";
        $this->TableHtml .= $this->Td['TdData'];
        if ($this->Echo) echo $this->Td['TdData'];
        $this->TableHtml .=" </td>\n";
        if ($this->Echo) echo " </td>\n";
        if (sizeof($this->Td)>0) { 
            foreach ($this->Td as $atributo => $valor) {
                unset ($this->Td[$atributo]);
            }
        }
        $this->NumTd++;
   }

   public function AddTh() {
        $atributo = "";
        $valor = "";
        $this->TableHtml .= "<th ";
        if ($this->Echo) echo "<th ";
        if (sizeof($this->Th)>0) {
            foreach ($this->Th as $atributo => $valor) {
                if ($atributo!="ThData") {
                    $this->TableHtml .= " " . $atributo . "=\"". $valor . "\" ";
                    if ($this->Echo) echo " " . $atributo . "=\"". $valor . "\" ";
                }
            }
        }
        $this->TableHtml .= " >\n";
        if ($this->Echo) echo " >\n";
        $this->TableHtml .= $this->Th['ThData'];
        if ($this->Echo) echo $this->Th['ThData'];
        $this->TableHtml .= " </th>\n";
        if ($this->Echo) echo " </th>\n";
        if (sizeof($this->Th)>0) {
            foreach ($this->Th as $atributo => $valor) {
                unset ($this->Th[$atributo]);
            }
        }
        $this->NumTh++;
   }
   
   
} // end class
