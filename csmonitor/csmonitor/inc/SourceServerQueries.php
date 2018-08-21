<?php
/**
 * http://developer.valvesoftware.com/wiki/Server_Queries
 */
final class SourceServerQueries extends ServerQueries {
    public function getInfo() {
        if( !$this->connected )
            return false;
        $return = array();
        $this->send("\xFF\xFF\xFF\xFFTSource Engine Query\x00");
        if($tmp = $this->read(true)) {
        #if($tmp = $this->read()) { // if server don't use dproto
            if($tmp == 0x6d) {
                $this->getString();
                $return['serverName'] = $this->getString();
                $return['mapName'] = $this->getString();
                $return['gameDir'] = $this->getString();
                $return['gameDesc'] = $this->getString();
                $return['playerNumber'] = $this->getByte();
                $return['maxPlayers'] = $this->getByte();
                $return['version'] = $this->getByte();
                $this->getByte();
                $tmp = chr($this->getByte());
                if($tmp == 'l')
                    $return['operatingSystem'] = 'Linux';
                else
                    $return['operatingSystem'] = 'Windows';
                if($this->getByte() == 0x01)
                    $return['passwordProtected'] = true;
                else
                    $return['passwordProtected'] = false;
                if($this->getByte() == 0x01) {
                    $this->getString();
                    $this->getString();
                    $this->raw = substr($this->raw, 11);
                }
                if($this->getByte() == 0x01)
                    $return['secureServer'] = true;
                else
                    $return['secureServer'] = false;
                $return['botNumber'] = $this->getByte();
            } elseif($tmp == 0x49) {
                $return['version'] = $this->getByte();
                $return['serverName'] = $this->getString();
                $return['mapName'] = $this->getString();
                $return['gameDir'] = $this->getString();
                $return['gameDesc'] = $this->getString();
                $this->raw = substr( $this->raw, 2 );
                $return['playerNumber'] = $this->getByte();
                $return['maxPlayers'] = $this->getByte();
                $return['botNumber'] = $this->getByte();
                $this->getByte();
                $tmp = chr($this->getByte());
                if($tmp == 'l')
                    $return['operatingSystem'] = 'Linux';
                else
                    $return['operatingSystem'] = 'Windows';
                if($this->getByte() == 0x01)
                    $return['passwordProtected'] = true;
                else
                    $return['passwordProtected'] = false;
                if($this->getByte() == 0x01)
                    $return['secureServer'] = true;
                else
                    $return['secureServer'] = false;
            }
        } else {
            $this->disconnect();
            return false;
        }
        return $return;
    }

    function getPlayers() {
        $return = array();
        if(!$this->connected)
            return $return;
        $this->send("\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF");
        $tmp = $this->read();
        if($tmp == 0x41) {
            $this->send("\xFF\xFF\xFF\xFF\x55" . $this->raw);
            $tmp = $this->read();
        } elseif(!$tmp) {
            $this->send("\xFF\xFF\xFF\xFF\x55" . $this->getChallenge());
            $tmp = $this->read();
        }
        if($tmp == 0x44) {
            $num = $this->getByte();
            for($i = 0; $i < $num; $i++ ) {
                $tmp = $this->getByte();
                $name = $this->getString();
                $kills = $this->getLong();
                $time = $this->getFloat();
                $return[] = array(
                    'name' => $name,
                    'score' => $kills,
                    'time' => gmdate("H:i:s", $time)
                );
            }
        }
        return $return;
    }

    function getRules() {
        $return = array();
        if(!$this->connected)
            return $return;
        $this->send("\xFF\xFF\xFF\xFF\x56\xFF\xFF\xFF\xFF");
        $tmp = $this->read();
        if($tmp == 0x41) {
            $this->send("\xFF\xFF\xFF\xFF\x56" . $this->raw);
            $tmp = $this->read();
        } elseif(!$tmp) {
            $this->send("\xFF\xFF\xFF\xFF\x56" . $this->getChallenge());
            $tmp = $this->read();
        }
        if($tmp == 0x45) {
            $num = $this->getShort();
            for($i=0; $i<$num; $i++) {
                $name = $this->getString();
                $value = $this->getString();
                if($name)
                    $return[$name] = $value;
            }
        }
        return $return;
    }

    private function getChallenge() {
        if( !$this->connected )
            return false;
        $this->send("\xFF\xFF\xFF\xFF\x57");
        $this->read();
        return substr($this->raw, 5);
    }
    
    protected function read($many_packets = false) {
        if($many_packets) {
            parent::read(true);
            $this->getLong();
        } else {
            parent::read();
            if($this->getLong() == -2) {
                $requestId = $this->getLong();
                $pacets = $this->getByte();
                $tmp = $this->getLong();
                if($requestId < 0) {
                    $this->getLong();
                    $this->getByte();
                    $this->raw = substr(bzdecompress($this->raw), 4);
                } elseif(($tmp < 0) && (substr($this->raw, 0, 3) == "\xFF\xFF\xFF"))
                    $this->raw = substr($this->raw, 3);
                $tmp = $this->raw;
                for ($i = 1; $i < $pacets; $i++) {
                    parent::read();
                    $this->raw = $tmp . substr($this->raw, 9);
                    $tmp = $this->raw;
                }
            }
        }
        $byte = $this->getByte();
        return $byte;
    }
}
?>