<?php
abstract class ServerQueries {
    protected $resource;
    protected $raw;
    protected $connected;
    protected $ip;
    protected $port;

    function __construct() {
        $this->resource = false;
        $this->connected = false;
        $this->raw = "";
    }
    
    function __destrcut() {
        self::disconnect();
    }

    public function connect($address, $port) {
        self::disconnect();
        $this->port = (int)$port;
        $this->ip = @gethostbyname($address);
        if($this->resource = @fsockopen('udp://' . $this->ip, $this->port, $errno, $errstr, 1 )  ) {
            $this->connected = true;
            stream_set_timeout($this->resource, 1);
        }
        return $this->connected;
    }
    
    public function disconnect() {
        if($this->connected) {
            if(is_resource($this->resource))
                fclose($this->resource);
            $this->connected = false;
        }
    }

    protected function send($data) {
        fwrite($this->resource, $data);
    }

    protected function read($many_packets = false) {
        if($many_packets)
            $this->raw = stream_get_contents($this->resource);
        else
            $this->raw = fread($this->resource, 2048);
        return true;
    }

    protected function getByte() {
        $return = @ord($this->raw[0]);
        $this->raw = substr($this->raw , 1);
        return $return;
    }

    protected function getShort() {
        $return = @unpack('sint', $this->raw);
        $this->raw = substr($this->raw , 2);
        return $return['int'];
    }

    protected function getLong() {
        $return = @unpack('iint', $this->raw);
        $this->raw = substr($this->raw , 4);
        return $return['int'];
    }

    protected function getFloat() {
        $return = @unpack('fint', $this->raw);
        $this->raw = substr($this->raw , 4);
        return $return['int'];
    }

    protected function getString() {
        $str = "";
        $i = 0;
        while(isset($this->raw[$i]) && ($this->raw[$i] != "\0")) {
            $str .= $this->raw[$i];
            $i++;
        }
        $this->raw = substr($this->raw , strlen($str) + 1);
        return $str;
    }
}

?>
