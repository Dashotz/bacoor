<?php

namespace App\Services;

class AssetService
{
    /**
     * Get external scripts for a page
     */
    public static function getExternalScripts(string $page): array
    {
        $assets = config('assets.pages.' . $page . '.external_scripts', []);
        $scripts = [];
        
        foreach ($assets as $script) {
            $scripts[] = config('assets.external.' . $script, []);
        }
        
        return $scripts;
    }
    
    /**
     * Get page-specific styles
     */
    public static function getPageStyles(string $page): array
    {
        return config('assets.pages.' . $page . '.styles', []);
    }
    
    /**
     * Get page-specific scripts
     */
    public static function getPageScripts(string $page): array
    {
        return config('assets.pages.' . $page . '.scripts', []);
    }
    
    /**
     * Generate external script tags
     */
    public static function generateExternalScriptTags(string $page): string
    {
        $scripts = self::getExternalScripts($page);
        $tags = '';
        
        foreach ($scripts as $script) {
            if (isset($script['script_url'])) {
                $async = isset($script['async']) && $script['async'] ? ' async' : '';
                $defer = isset($script['defer']) && $script['defer'] ? ' defer' : '';
                $tags .= '<script src="' . $script['script_url'] . '"' . $async . $defer . '></script>' . "\n    ";
            }
        }
        
        return $tags;
    }
}
