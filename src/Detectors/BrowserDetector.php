<?php

namespace HistoricalRecords\Detectors;

class BrowserDetector
{
    protected static $instance;

    protected string $agent;

    protected string $browser;

    protected string $platform;

    protected string $version;

    public function __construct(protected $request)
    {
        $this->agent = $request->server('HTTP_USER_AGENT');
        $this->detect();
    }

    public function detect()
    {
        $browser = get_browser($this->agent, true);

        $this->browser = $browser['browser'];
        $this->platform = $browser['platform'];
        $this->version = $browser['version'];
    }

    public static function getInstance($request)
    {
        if (! isset(static::$instance)) {
            static::$instance = new static($request);
        }
        
        return static::$instance;
    }
 
    public function isMobile(): bool
    {
        return is_numeric(strpos(strtolower($this->agent), 'mobile'));
    }
    
    public function isPhone(): bool
    {
        return is_numeric(strpos(strtolower($this->agent), 'android'))
                || is_numeric(strpos(strtolower($this->agent), 'iphone'));
    }
    
    public function isTablet(): bool
    {
        return true;
    }
    
    public function isDesktop(): bool
    {
        return true;
    }
}
