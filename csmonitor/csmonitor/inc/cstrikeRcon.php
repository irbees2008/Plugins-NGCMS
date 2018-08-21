<?php
class cstrikeRcon extends ServerQueries{
    protected $password;
    protected $challenge_number;

    final public function connect($address, $port, $password) {
        $this->password = $password;
        return parent::connect($address, $port);
    }

    public function command($command) {
        if(!$this->connected)
            return false;
        if(empty($this->challenge_number))
            $this->challenge_number = $this->getChallenge();
        $packet = "\xff\xff\xff\xffrcon " . $this->challenge_number
                . " \"" . $this->password . "\" " . $command . "\n";
        $this->send($packet);
        $this->read(true);
        $this->raw = str_replace("\xFF\xFF\xFF\xFF\x6c", '', $this->raw);
        $this->getByte();
        return $this->raw;
    }

    private function getChallenge() {
        if( !$this->connected )
            return false;
        $this->send("\xff\xff\xff\xffchallenge rcon\n");
        $this->read();
        $return = explode(' ', $this->raw);
        return trim($return[2]);
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
        return true;
    }
}
?>