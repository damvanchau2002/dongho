<?php

class Controller
{
    protected function render($view, $data = [])
    {
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        $layoutFile = APP_PATH . '/views/layouts/main.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo 'View not found: ' . htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($data);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if (file_exists($layoutFile)) {
            include $layoutFile;
            return;
        }

        echo $content;
    }

    protected function renderLegacy($legacyView, $data = [])
    {
        $viewFile = BASE_PATH . '/views/' . $legacyView . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo 'View not found: ' . htmlspecialchars($legacyView, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($data);
        include $viewFile;
    }
}
