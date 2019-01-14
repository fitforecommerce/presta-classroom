<?php
/**
* Description
*/
class Route
{
    public $route_path;
    public $values;

    function __construct($nroute_path, $nvalues)
    {
        $this->route_path = $nroute_path;
        $this->values = $nvalues;
    }
    public function __toString()
    {
        return $this->route_path;
    }
    public function __get($key)
    {
        return $this->values[$key];
    }
    public function matches_request($request)
    {
        $rv = preg_match($this->route_regex(), $request);
        return $rv==1;
    }
    public function split()
    {
        return preg_split('/\//', $this->route_path);
    }
    private function route_regex()
    {
        if(isset($this->route_regex)) return $this->route_regex;
        $regex = '/\{(.+?)\}/';
        $tmpreg = '';
        foreach ($this->split() as $i => $pe) {
            if(preg_match($regex, $pe, $m)) {
                $tmpreg .= '\/([^\/]+)(?=\/|$)';
            } elseif($pe!='') {
                $tmpreg .= '\/'.$pe;
                if($i == count($this->split())-1) $tmpreg .= "\$";
            }
        }
        $this->route_regex = "/$tmpreg/";
        # error_log("Route::route_regex rv: $this->route_regex");
        return $this->route_regex;
    }
    private function param_regex()
    {
        return '/\{(.+?)\}/';
    }
}
?>