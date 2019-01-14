<?php
/**
* Description
*/
class RequestParser
{

    function __construct($nrequest, $nroute)
    {
        # error_log("new parser with $nrequest - $nroute");
        $this->request  = $this->split($nrequest);
        $this->route    = $nroute;
    }
    public function params()
    {
        $rv = [];
        $keys = $this->parse_keys();
        foreach ($keys as $k) {
            $value_index = array_search('{'.$k.'}', $this->route->split());
            $rv[$k] = $this->request[$value_index];
        }
        return $rv;
    }
    private function split($r)
    {
        return preg_split('/\//', $r);
    }
    private function parse_keys()
    {
        $regex = '/\{(.+?)\}/';
        $rv = [];
        foreach ($this->route->split() as $i => $v) {
            if(preg_match($regex, $v, $matches)) {
                $rv[] = $matches[1];
            }
        }
        # error_log("parse_keys " .print_r($rv, true));
        return $rv;
    }
    private function value_regex()
    {
        
    }
}
?>