<?php
//Archivo:             Form.php
//Descripcion:         Form.
//Autor:               dmarfil, rduran
//Fecha de creación:   1/07/2012 
//Ultima modificación: 17/09/2012 

class NdhHtmlForm  //Class to generate HTML form with any king of INPUT, SELECT or TEXTAREA
{// Agregada: 09/03/2005
  public $numFields;  //Number of elements in the html form
  public $Method;     //Method to send the data
  public $Action;     //Script that will process the data
  public $EncType;    //Type of Data in form
  public $Name;       //form Name
  public $Text;       //Array of values for Input field types (SUBMIT|RESET|RADIO|CHECKBOX|FILE|TEXT|HIDDEN|IMAGE|BUTTON|PASSWORD)
  public $TextArea;   //Array of values for Text Area Input type
  public $List;       //Array of values for Select Input type
  public $BrOn;       //Just if you want to add a <br> after each field
  public $FocusField; //Name of the field that you want to be selected before page load
  public $FieldName;  //Array store each field name
  public $FieldType;  //Array store each Type of each field
  public $OnSubmit;   //For JavaScript added 09/05/2005
  public $FormId;       //

  function NdhHtmlForm()
   {
    global $_SERVER;                    //Needed for access the $_SERVER['PHP_SELF'] publicaible
    $this->numFields=0;                  //Setting to 0
    $this->Method="POST";                //Setting by default to POST
    $this->Action=$_SERVER['PHP_SELF'];  //Setting by default to $_SERVER['PHP_SELF']
    $this->EncType="multipart/form-data";                   //Setting by default to empty
    $this->Name="form"  . uniqid(time());//Setting a defualt Unique form name
    $this->FormId= uniqid(time());//Setting a defualt Unique form name
    $this->BrOn=false;                    //Setting by default to true
    $this->Text['MaxSize']=30;           //Setting by default to 10
    $this->Text['Size']=10;              //Setting by default to 10

   }
   
   
  function open()
   { //Just print the form tag
    echo "<form name=\"" . $this->Name ."\" id=\"" . $this->FormId ."\" Method=\"" .$this->Method . "\" Action=\"" . $this->Action . "\" ENCTYPE=\"" . $this->EncType ."\"";
	if (isset($this->OnSubmit)) echo " OnSubmit=\"" . $this->OnSubmit . "\"";
	echo ">";
    echo "\r"; //Just to keep the html code in order see the page generated code
   }

  function close()
   { //Print
    echo "</form>\r";
    if (isset($this->FocusField))
     {
      echo "<script languaje=\"JavaScript\">\r function setFocus() \r\t{\r\t document." . $this->Name . "." .$this->FocusField . ".focus();\r\t";
      if ($this->FindType($this->FocusField)!="SELECT")
       {
        echo " document." . $this->Name . "." .$this->FocusField . ".select();";
       }
      echo "\r} setFocus();\r</script>";
     }
    echo "\r";//Just to keep the html code in order see the page generated code
   }

  function FindType($Field)
   { //Find the type of any form field
    $encontrado ="";
    for ($x=0;$x<$this->numFields;$x++)
     {
      if (strtoupper($Field)==strtoupper($this->FieldName[$x]))
      {
       $encontrado = $this->FieldType[$x];
       break;
      }
     }
    return $encontrado;
   }

  function AddField()
  //General Porpuse form input
  //supported types SUBMIT RESET RADIO CHECKBOX FILE TEXT HIDDEN IMAGE BUTTON PASSWORD
   {
    if (isset($this->Text['Type']))
     {
       if (!((strtoupper($this->Text['Type'])!="SUBMIT")    &&
             (strtoupper($this->Text['Type'])!="RESET")     &&
             (strtoupper($this->Text['Type'])!="RADIO")     &&
             (strtoupper($this->Text['Type'])!="CHECKBOX")  &&
             (strtoupper($this->Text['Type'])!="FILE")      &&
             (strtoupper($this->Text['Type'])!="TEXT")      &&
             (strtoupper($this->Text['Type'])!="HIDDEN")    &&
             (strtoupper($this->Text['Type'])!="IMAGE")     &&
             (strtoupper($this->Text['Type'])!="BUTTON")    &&
             (strtoupper($this->Text['Type'])!="PASSWORD")))
         {  // begin if block
         echo "\t";
         if (isset($this->Text['Label']))
          {
           echo $this->Text['Label'] . " ";
          }
         echo "<input type=\"" . $this->Text['Type'] . "\" ";
         if (isset($this->Text['Style']))
          {
           echo " class=\"" . $this->Text['Style'] . "\" ";
          }
         if (!isset($this->Text['Name']))
         // auto asign name
         // if name is null
          {
           $this->Text['Name'] = "field"  . uniqid(time());
          }
         echo " name=\"" . $this->Text['Name'] . "\" ";
         if (!isset($this->Text['Value']))
          {
           switch ($this->Text['Type'])
           {
            case "SUBMIT": $this->Text['Value']="SUBMIT";
                           break;
            case "RESET" : $this->Text['Value']="CANCEL";
                          break;
            default      : $this->Text['Value']="";
           }
          }
          echo " value=\"" . $this->Text['Value'] . "\" ";
          if (isset($this->Text['Checked']) and $this->Text['Checked']==true)
           {
            echo  " Checked ";
           }
          if (isset($this->Text['Disable']) and $this->Text['Disable']==true)
           {
            echo  " disabled=\"true\" ";
           }
          if (isset($this->Text['JavaEvent']) && isset($this->Text['Java']))
           {
              // Modificacion
              $JavaEvent=explode("|",$this->Text['JavaEvent']);
              $Java=explode("|",$this->Text['Java']);
              for($i=0;$i<sizeof($Java);$i++)
                    echo  $JavaEvent[$i] . "=\"" . $Java[$i] . "\" ";
           }
          if (isset($this->Text['Id']))
           {
            echo "Id=\"" . $this->Text['Id'] . "\" ";
           }
          if (isset($this->Text['Src']))
           {
            echo " src=\"" . $this->Text['Src'] . "\" ";
           }
          if (isset($this->Text['MaxSize']))
           {
             echo " maxlength=\"" . $this->Text['MaxSize'] . "\" ";
           }
          if (isset($this->Text['Size']))  // default asign
           {
            echo " size=\"" . $this->Text['Size'] ."\"";
           }
          echo " >";
          if ($this->BrOn==true) echo "<br>\r";
          else echo "\r";
          $this->FieldType[$this->numFields]=$this->Text['Type'];
          $this->FieldName[$this->numFields]=$this->Text['Name'];
          $this->numFields++;
         }//end if type
     }
     
    if (sizeof($this->Text)>0) {
        foreach ($this->Text as $atributo => $valor) {
            unset ($this->Text[$atributo]);
        }
    }     
    /*   // removido y sustituido por el código del bloque for each
    unset($this->Text['Name']);
    unset($this->Text['Type']);
    unset($this->Text['Value']);
    unset($this->Text['Style']);
    unset($this->Text['Java']);
    unset($this->Text['JavaEvent']);
    unset($this->Text['Label']);
    unset($this->Text['MaxSize']);
    unset($this->Text['Size']);
    unset($this->Text['Checked']);
    unset($this->Text['Src']);
    unset($this->Text['Disable']);
    unset($this->Text['Id']);
     * 
     */
  } //end function

function ListOpen()
  {
   echo "\t";
   if (!isset($this->List['Name'])) // auto asign name
    {
     $this->List['Name'] = "field"  . uniqid(time());
    }
   echo "<select name=\"" . $this->List['Name'] . "\" ";
   if (isset($this->List['Multiple']) && ($this->List['Multiple']==true))
    {
     echo " Multiple ";
    }
   if (isset($this->List['Size']))
    {
     echo " size=\"" . $this->List['Size'] . "\" ";
    }
   if (isset($this->List['JavaEvent']) && isset($this->List['Java']))
    {
     echo  $this->List['JavaEvent'] . "=\"" . $this->List['Java'] . "\" ";
    }
    if (isset($this->List['Style']))
    {
    echo " class=\"" . $this->List['Style'] . "\" ";
    }
    if (isset($this->List['Id']))
     {
     echo "Id=\"" . $this->List['Id'] . "\" ";
     }
     if (isset($this->List['Disable']) and $this->List['Disable']==true)
    {
    echo  " disabled=\"true\" ";
    }
   echo ">\r";
   $this->FieldType[$this->numFields]="SELECT";
   $this->FieldName[$this->numFields]=$this->List['Name'];
   $this->numFields++;

    if (sizeof($this->List)>0) {
        foreach ($this->List as $atributo => $valor) {
            unset ($this->List[$atributo]);
        }
    }     
   /*
   
   unset($this->List['Name']);
   unset($this->List['Multiple']);
   unset($this->List['Style']);
   unset($this->List['Java']);
   unset($this->List['JavaEvent']);
   unset($this->List['Label']);
   unset($this->List['MaxSize']);
   unset($this->List['Size']);
   unset($this->List['Checked']);
   unset($this->List['Src']);
   unset($this->List['Id']);
   unset($this->List['Disable']);
    * 
    */
  } //end function

function ListAddItem($value,$label,$selected=false) //value, label, true|false
  {
   //if ($value!="") // Removida para poder usar la validación de jquery  
    //{
     echo "\t\t<option value=\"$value\" ";
     if (($selected==true) || ((isset($this->List['Default'])) && ($this->List['Default']==$value)))
      {
       echo " selected ";
      }
     echo ">";
     if ($label!="")
      {
       echo $label;
      }
     else
      {
       echo $value;
      }
     echo "</option>\r";
    //}
  } //end function

function ListClose()
  {
   echo "\t</select>\r";
   if ($this->BrOn) echo "<br>\r";
   else echo "\r";
   unset($this->List['Default']);
  } //end function


function AddList()
  {
   echo "\t";
   if (!isset($this->List['Name'])) // auto asign name
    {
     $this->List['Name'] = "field"  . uniqid(time());
    }
   echo "<select name=\"" . $this->List['Name'] . "\" ";
   if (isset($this->List['Multiple']) && ($this->List['Multiple']==true))
    {
     echo " Multiple ";
    }
   if (isset($this->List['Size']))
    {
     echo " size=\"" . $this->List['Size'] . "\" ";
    }
   if (isset($this->List['JavaEvent']) && isset($this->List['Java']))
    {
     echo  $this->List['JavaEvent'] . "=\"" . $this->List['Java'] . "\" ";
    }
   if (isset($this->List['Id']))
    {
    echo "Id=\"" . $this->List['Id'] . "\" ";
    }
   echo ">\r";
   if (isset($this->List['Value']))
    {
     for ($x=0;$x<sizeof($this->List['Value']);$x++)
     {
       echo "\t\t<option value=\"" . $this->List['Value'][$x] . "\" ";
       if (isset($this->List['Default'][$x]) && ($this->List['Default'][$x]==true))
        {
         echo " selected ";
        }
       echo ">";
       if (isset($this->List['Label'][$x]))
        {
         echo $this->List['Label'][$x];
        }
       else
        {
         echo $this->List['Value'][$x];
        }
       echo "</option>\r";
     }
    }//and if value
   echo "\t</select>\r";
   if ($this->BrOn) echo "<br>\r";
   else echo "\r";
   $this->FieldType[$this->numFields]="SELECT";
   $this->FieldName[$this->numFields]=$this->List['Name'];
   $this->numFields++;
   
    if (sizeof($this->List)>0) {
        foreach ($this->List as $atributo => $valor) {
            unset ($this->List[$atributo]);
        }
    }     
   /*
   
   unset($this->List['Name']);
   unset($this->List['Multiple']);
   unset($this->List['Default']);
   unset($this->List['Style']);
   unset($this->List['Java']);
   unset($this->List['JavaEvent']);
   unset($this->List['Label']);
   unset($this->List['MaxSize']);
   unset($this->List['Size']);
   unset($this->List['Checked']);
   unset($this->List['Src']);
   unset($this->List['Id']);
    * 
    */
  } //end function

function AddTextArea()
  {
   echo "\t<textarea ";
     // <textarea name="textarea" cols="50" rows="5" wrap="VIRTUAL"></textarea>
        if (sizeof($this->TextArea)>0) {
         foreach ($this->TextArea as $atributo => $valor) {
             if ($atributo!="Value") echo " " . $atributo . "=\"". $valor . "\" ";
             
         }
     }
    echo ">";        
   if (isset($this->TextArea['Value']))
    {
     echo $this->TextArea['Value'];
    }
   echo "</textarea>\r";
   if ($this->BrOn) echo "<br>\r";
   else echo "\r";
   $this->FieldType[$this->numFields]="TEXTAREA";
   $this->FieldName[$this->numFields]=$this->TextArea['Name'];
   $this->numFields++;

   if (sizeof($this->TextArea)>0) {
        foreach ($this->TextArea as $atributo => $valor) {
            unset ($this->TextArea[$atributo]);
        }
    }  
   
   /*
   
   unset($this->TextArea['Name']);
   unset($this->TextArea['Type']);
   unset($this->TextArea['Value']);
   unset($this->TextArea['Style']);
   unset($this->TextArea['Java']);
   unset($this->TextArea['JavaEvent']);
   unset($this->TextArea['Label']);
   unset($this->TextArea['MaxSize']);
   unset($this->TextArea['Size']);
   unset($this->TextArea['Checked']);
   unset($this->TextArea['Src']);
   unset($this->TextArea['Rows']);
   unset($this->TextArea['Cols']);
   unset($this->TextArea['Wrap']);
   unset($this->TextArea['Id']);
    * 
    */
  } //end function

} // end Class

