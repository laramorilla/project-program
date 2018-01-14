<?php 

class DOCXReader{
    

// Open the file

    function read_file_docx($filename){
        
        $striped_content = '';
        $content = '';
        
        if(!$filename || !file_exists($filename)) return false;
        
        $zip = zip_open($filename);
        
        if (!$zip || is_numeric($zip)) return false;
        
        while ($zip_entry = zip_read($zip)) {
            
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }// end while
        
        zip_close($zip);
        
        //echo $content;
        //echo "<hr>";
        //file_put_contents('1.xml', $content);
        
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);
        
        return $striped_content;
    }
    $filename = "filepath";// or /var/www/html/file.docx
    
    $content = read_file_docx($filename);
    if($content !== false) {
        
        echo nl2br($content);
    }
    else {
        echo 'Couldn\'t the file. Please check that file.';
    }
    
// extract the shared string and the list of sheets

    protected function parse() {
        $sheets = array();
        $relationshipsXML = simplexml_load_string($this->getEntryData("_rels/.rels"));
        foreach($relationshipsXML->Relationship as $rel) {
            if($rel['Type'] == self::SCHEMA_OFFICEDOCUMENT) {
                $workbookDir = dirname($rel['Target']) . '/';
                $workbookXML = simplexml_load_string($this->getEntryData($rel['Target']));
                foreach($workbookXML->sheets->sheet as $sheet) {
                    $r = $sheet->attributes('r', true);
                    $sheets[(string)$r->id] = array(
                        'sheetId' => (int)$sheet['sheetId'],
                        'name' => (string)$sheet['name']
                    );
                    
                }
                $workbookRelationsXML = simplexml_load_string($this->getEntryData($workbookDir . '_rels/' . basename($rel['Target']) . '.rels'));
                foreach($workbookRelationsXML->Relationship as $wrel) {
                    switch($wrel['Type']) {
                        case self::SCHEMA_WORKSHEETRELATION:
                            $sheets[(string)$wrel['Id']]['path'] = $workbookDir . (string)$wrel['Target'];
                            break;
                        case self::SCHEMA_SHAREDSTRINGS:
                            $sharedStringsXML = simplexml_load_string($this->getEntryData($workbookDir . (string)$wrel['Target']));
                            foreach($sharedStringsXML->si as $val) {
                                if(isset($val->t)) {
                                    $this->sharedStrings[] = (string)$val->t;
                                } elseif(isset($val->r)) {
                                    $this->sharedStrings[] = XLSXWorksheet::parseRichText($val);
                                }
                            }
                            break;
                    }
                }
            }
        }
        $this->sheetInfo = array();
        foreach($sheets as $rid=>$info) {
            $this->sheetInfo[$info['name']] = array(
                'sheetId' => $info['sheetId'],
                'rid' => $rid,
                'path' => $info['path']
            );
        }
    }
    
}







?>