<?php if(!defined('FastCore')){echo ('Выявлена попытка взлома!');exit();}

class Router {
	public $title = '';
	public $params = null;
	public $classname = '';
	public $data = null;
	public $segment = '';
	public $request_uri = '';
	public $url_info = array();
	public $found = false;
 
	function __construct() {
		$this->Routed();
	}
 
	function Routed() {

	$this->classname = '';
	$this->title = '';
	$this->params = null;
	$this->segment = '';

	$map = &$GLOBALS['routes'];
	$this->request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$this->url_info = parse_url($this->request_uri);
	$uri = urldecode($this->url_info['path']);
	$segment = explode('/', trim($uri, '/'));

	$data = false;

foreach ($map as $term => $dd) {
	$match = array();
	$i = preg_match('@^'.$term.'$@Uu', $uri, $match);
	if ($i > 0) {
		# Страницы с параметрами и заголовки
		$m = explode(',', $dd);
		$data = array(
			'classname' => isset($m[0])?strtolower(trim($m[0])):'',
			'title' => isset($m[1])?trim($m[1]):'',
			'params' => $match,
			'segment' => $segment); break;
		}
	}

	if ($data === false) {
	# 404
            if (isset($map['_404'])) {
			# Страница 404
			$dd = $map['_404'];
			$m = explode(',', $dd);
			$this->classname = strtolower(trim($m[0]));
			$this->title = trim($m[1]);
			$this->params = array();
			}
		$this->found = false;
	} 
	else {
		# Найдено!
		$this->classname = $data['classname'];
		$this->title = $data['title'];
		$this->params = $data['params'];
		$this->segment = $data['segment'];
		$this->found = true;
		}
		return $this->classname;
	}
}
?>