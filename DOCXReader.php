<?php 

class DOCXReader{
    

// Open the file

    public function readFile($file){
        $lineas = file($file);
        if($lineas === true) {
            $this->parse();
        } else {
            throw new Exception("Failed to open $file with zip error code: $lineas");
        }
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