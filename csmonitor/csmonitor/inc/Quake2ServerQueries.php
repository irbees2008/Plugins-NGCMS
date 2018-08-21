<?php
final class Quake2ServerQueries extends ServerQueries {
    public function getInfo() {
        if(!$this->connected)
            return $return;
        $this->send("\xff\xff\xff\xffstatus\x00");
        if($this->read()) {
            $return = $this->_getInfo();
            if(is_array($return))
                $return['clients'] = count($this->_getPlayers());
            return $return;
        } else
            return false;
    }
    
    private function _getInfo() {
        $return = array();
        $answer = $this->getString();
        $pos = strpos($this->raw, "\n");
        if($pos) {
            $players = substr($this->raw, $pos+1);
            $this->raw = substr($this->raw, 0, $pos);
        } else $players = "";
        while(strlen($this->raw))
            $return[$this->getString()] = $this->getString();
        $this->raw = $players;
        return $return;
    }

    function getPlayers() {
        if(!$this->connected)
            return $return;
        $this->send("\xff\xff\xff\xffstatus\x00");
        if($this->read()) {
            $answer = $this->getString();
            $pos = strpos($this->raw, "\n");
            if($pos)
                $this->raw = substr($this->raw, $pos+1);
            else
                return array();
            return $this->_getPlayers();
        } else
            return false;
        
    }
    private function _getPlayers() {
        $return = array();
        if(preg_match_all("!^(\d+)\s(\d+)\s\"(.*)\"$!m", $this->raw, $matches)) {
            $count = count($matches[0]);
            for($i=0; $i < $count; $i++) {
                $return[] = array('name' => $matches[3][$i], 'score'=> $matches[1][$i], 'ping' => $matches[2][$i]);
            }
        }
        return $return;
    }

    function getRules() {
        return false;
    }
    
    protected function read($many_packets = false) {
        parent::read();
        $this->getLong();
        return !empty($this->raw);
    }

    protected function getString() {
        $pos = strpos($this->raw, '\\');
        if(!$pos) {
            $str = $this->raw;
            $this->raw = "";
            return $str;
        }
        $str = substr($this->raw, 0, $pos);
        $this->raw = substr($this->raw , $pos + 1);
        return $str;
    }
}
?>