<?php
final class Quake3ServerQueries extends ServerQueries {
    public function getInfo() {
        if( !$this->connected )
            return false;
        $return = array();
        $this->send("\xFF\xFF\xFF\xFFgetinfo\0");
        if($this->read()) {
            $answer = $this->getString();
            if($answer != "infoResponse\n")
                return false;
            while(strlen($this->raw))
                $return[$this->getString()] = $this->getString();
        } else
            return false;
        if(!empty($return['hostname']))
            $return['hostname'] = preg_replace('!\^.{1}!', '', $return['hostname']);
        return $return;
    }

    function getPlayers() {
        $return = array();
        if(!$this->connected)
            return $return;
        $this->send("\xff\xff\xff\xffgetstatus");
        if($this->read()) {
            $answer = $this->getString();
            if($answer != "statusResponse\n")
                return false;
            $pos = strpos($this->raw, "\n");
            if($pos)
                $this->raw = substr($this->raw, $pos+1);
            else
                return array();
            if(preg_match_all("!^(\d+)\s(\d+)\s\"(.*)\"$!m", $this->raw, $matches)) {
                $count = count($matches[0]);
                for($i=0; $i < $count; $i++) {
                    $matches[3][$i] = preg_replace('!\^.{1}!', '', $matches[3][$i]);
                    $return[] = array('name' => $matches[3][$i], 'score'=> $matches[1][$i], 'ping' => $matches[2][$i]);
                }
            }
        } else
            return false;
        return $return;
    }

    function getRules() {
        $return = array();
        if(!$this->connected)
            return $return;
        $this->send("\xff\xff\xff\xffgetstatus");
        if($this->read()) {
            $answer = $this->getString();
            if($answer != "statusResponse\n")
                return false;
            $pos = strpos($this->raw, "\n");
            if($pos)
                $this->raw = substr($this->raw, 0, $pos);
            else
                return array();
            while(strlen($this->raw))
                $return[$this->getString()] = $this->getString();
        } else
            return false;
        return $return;
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