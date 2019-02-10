<?php
class Router
{
    use ConfigTrait;

    public $action;
    public $controller_class;

    public static function from_request()
    {
        global $_SERVER;
        return new Router($_SERVER['REQUEST_URI']);
    }
    function __construct($nrequest)
    {
      # ignore the url basepath
      $regex = '~^'.preg_quote($this->appconfig('webserver')['urlpath']).'~';
      $nrequest = preg_replace($regex, '', $nrequest);
      $this->wwwpath = $nrequest;
    }
	public function run_controller()
	{
		if($controller = $this->controller()) {
            # error_log("MainController->run_controller ".get_class($controller));
			$controller->run();
		} else {
			# $this->msg('Invalid controller chosen!', MessageView::ERROR);
            throw new Exception('Invalid controller '.$this->controller_class().' chosen!');
            return false;
		}
	}
	public function controller()
	{
        if(isset($this->controller)) return $this->controller;
		if(!class_exists($this->controller_class())) { return false; }
        $contr_class = $this->controller_class();
        # error_log("Router::cast controller to $contr_class");
		$this->controller = new $contr_class();
		return $this->controller;
	}
    public function controller_class()
    {
        if(isset($this->controller_class)) return $this->controller_class;
        $this->controller_class = $this->route()->controllerClass;
        if(!isset($this->controller_class) || $this->controller_class=='') {
            $this->controller_class = $this->default_controller_class();
        }
        return $this->controller_class;
    }
    public function action()
    {
        if(isset($this->action)) return $this->action;
        $this->action = $this->route()->action;
        if(!isset($this->action) || $this->action=='') {
            $this->action = $this->controller()->default_action();
        }
        # error_log("Router::action ".$this->action);
        return $this->action;
    }
    public function params()
    {
        # error_log("Router::params ".print_r($this->request_parser()->params(), true));
        return $this->request_parser()->params();
    }
    public function default_controller_class()
    {
        return "DashboardController";
    }
    public function request_parser()
    {
        if(!isset($this->request_parser)) {
          $this->request_parser = new RequestParser($this->wwwpath, $this->route());
        }
        return $this->request_parser;
    }
    public function base_path()
    {
        if(!isset($this->base_path)) {
            $this->base_path = $this->appconfig('webserver')['urlpath'].'/public';
        }
        return $this->base_path;
    }
    private function route()
    {
        $regex = '/^'.preg_replace('/\//', '\/', $this->base_path()).'/';
        $wp = preg_replace($regex, '', $this->wwwpath);
        foreach ($this->routes() as $route) {
            if($route->matches_request($wp)) {
                # error_log("Router::route() match request '$wp' with route $route");
                return $route;
            }
        }
        return false;
    }
    private function yaml_routes()
    {
        if(!isset($this->yaml_routes)) $this->yaml_routes = spyc_load_file($this->libdir().'/config/routes.yml');
        return $this->yaml_routes;
    }
    private function routes()
    {
        if(!isset($this->routes)) {
            $this->routes = [];
            $tmproutes = $this->yaml_routes()['routes'];
            foreach ($tmproutes as $key => $value) {
                # error_log("creating route for $key");
                $this->routes[$key] = new Route($key, $value);
            }
            # error_log("yaml routes:".print_r($this->routes, true));
        }
        return $this->routes;
    }
}
?>