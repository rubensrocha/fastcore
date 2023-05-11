<?php if (!defined('FastCore')) {
    echo('Выявлена попытка взлома!');
    exit();
}

class router
{
    public string $title = '';
    public mixed $params = null;
    public string $classname = '';
    public mixed $segment = '';
    public mixed $request_uri = '';
    public array $url_info = array();
    public bool $found = false;

    public function __construct()
    {
        $this->Routed();
    }

    public function Routed()
    {

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
            $i = preg_match('@^' . $term . '$@Uu', $uri, $match);
            if ($i > 0) {
                # Страницы с параметрами и заголовки
                $m = explode(',', $dd);
                $data = array(
                    'classname' => isset($m[0]) ? strtolower(trim($m[0])) : '',
                    'title' => isset($m[1]) ? trim($m[1]) : '',
                    'params' => $match,
                    'segment' => $segment);
                break;
            }
        }

        if ($data === false) {
            # 404
            if (isset($map['_404'])) {
                # Страница 404
                $dd = $map['_404'];
                $m = explode(',', $dd);
                $this->classname = strtolower(trim($m[0]));
                $this->title = isset($m[1]) ? trim($m[1]) : '404';
                $this->params = array();
            }
            $this->found = false;
        } else {
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