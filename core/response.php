<?php

class Response
{
    public static function format(mixed $data): string
    {
        if ($data) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return '';
    }

	 public static function render(string $view, array $data = []): void
	{
		extract($data);

        ob_start();
        require BASE_PATH . '/public/view/' . $view . '.php';
        $content = ob_get_clean();

        require BASE_PATH . '/public/layout.php';
	}

	public static function redirect(string $controller, string $action, array $params = []): void
    {
        $query = array_merge([
            'page' => $controller,
            'action' => $action,
        ], $params);
        self::redirectUrl('/index.php?' . http_build_query($query));
    }

    public static function redirectUrl(string $url, int $statusCode = 302): void
    {
		http_response_code($statusCode);
        header('Location: ' . $url);
        exit;
    }

    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
