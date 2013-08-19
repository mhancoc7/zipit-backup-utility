<?php
###############################################################
# Zipit Backup Utility
###############################################################
# Developed by Jereme Hancock for Cloud Sites
# Visit http://zipitbackup.com for updates
###############################################################
       
  class displayLogfile { 
        
      var $rowstoread; 
      var $align; 
      var $filecontent; 
      var $fileRows; 
      var $rows; 
      var $linebreak; 
       

       
      // Set´s 
       
      /** 
       * setRowsToRead() 
       * @desc This Function Sets "how much rows should be displayed."  
       * If you leave this empty, standart is 40 Rows. 
       * @param int $rows 
       */ 
      function setRowsToRead($data) { 
          if($data>0) { 
            $this->rowstoread = $data;     
          } else { 
          $this->rowstoread = 40; 
          } 
      } 
       
       
      /** 
       * setAlign() 
       * @desc This Function sets the Align.  
       * "top" =  Last row on Top  
       * "bottom" = Last row on Bottom 
       * Is this is not set, align is allways bottom!  
       * @param string $align ("top" || "bottom") 
       */ 
      function setAlign($data) { 
        if($data == "top" || $data == "bottom") { 
          $this->align = $data; 
        } else { 
          $this->align = "bottom"; 
        } 
      } 

       
      /** 
       * setLineBreak() 
       * @desc Set here, the count of chars, after you want the break the line. 
       * Dont set this if you dont need that. 
       * @param int $breakLineAfter 
       */ 
      function setLineBreak($data=0) { 
        $data>0 ? $this->linebreak=$data : $this->linebreak=0; 
      } 
       
      /** 
       * setFilepath() 
       * This Function read the Filecontents, or returns an error if 
       * the file doesent exists. 
       * @param string $filename 
       * @return bool 
       */ 
      function setFilepath($data) { 
        if($this->filecontent=@file($data)) { 
          return true; 
        } else { 
          echo '<b>Error in: this->setFilepath()</b> <br />'; 
          echo 'File dosent exist ('.$data.') '; 
          exit(); 
          return false; 
        } 
      }       
       
       
       
       
    // Action´s 
     
    /** 
     * rowSize() 
     * @desc This Function fills $this->fileRows with the row-counting  
     */ 
    function rowSize() { 
     if($this->filecontent) { 
      $this->fileRows = count($this->filecontent); 
     } 
    } 
     
     
    /** 
     * readRows() 
     * @desc This Function creates the Array $this->rows. Key = Linenumber / Value = Row 
     * 
     */ 
    function readRows() { 
      $this->rowSize(); 
      $this->rowstoread>$this->fileRows ? $this->rowstoread=$this->fileRows : $this->rowstoread*=1; 
      $this->rowstoread<1 ? $start=1 : $start=($this->fileRows-$this->rowstoread); 
      $this->outputRows = array();   
      $count=1; 
      for($x=$start; $x<=$this->fileRows-1; $x++) { 
       $this->rows[$x] = $this->filecontent[$x]; 
       $count++; 
      }   
       $this->align=="top" ? $this->rows = array_reverse($this->rows,TRUE) : false;  
    }  
     
    /** 
     * returnFormated() 
     * @desc This Function returns the Formated Rows as String 
     */ 
    function returnFormated() { 
      $this->readRows(); 
      foreach($this->rows AS $rowNumber => $row) { 
          if($this->linebreak>0) { 
           $rowfill = str_pad("",(strlen($rowNumber.":")+2)," ",STR_PAD_LEFT); 
           echo wordwrap($row,$this->linebreak, chr(13).chr(10).$rowfill,1); 
          } else { 
         echo str_pad((strlen($rowNumber.":")+2)," ", STR_PAD_RIGHT).$row; 
        } 
      } 
    } 
} 
         
         
?>
