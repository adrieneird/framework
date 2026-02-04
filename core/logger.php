<?php
class Logger
{
    private const LOG_DIR = BASE_PATH . '/log/';
    
        public static function error(string $message, array $context = []): void
    {
        self::log($message, 'ERROR', $context);
    }
    
    public static function info(string $message, array $context = []): void
    {
        self::log($message, 'INFO', $context);
    }
    
    public static function warning(string $message, array $context = []): void
    {
        self::log($message, 'WARNING', $context);
    }
    
    private static function log(string $message, string $severity, array $context): void
    {
        $dir = self::LOG_DIR;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[$timestamp] $severity: $message$contextStr" . PHP_EOL;
        
        $file = $dir . date('Y-m-d') . '.log';
        file_put_contents($file, $logMessage, FILE_APPEND);
    }
}