<?php

namespace HistoricalRecords\Detectors;

use BadMethodCallException;
use Illuminate\Container\Container;

class Detector
{
    protected array $detectors = [
        'browser' => BrowserDetector::class,
        'device' => DeviceDetector::class,
    ];

    public function __construct(
        protected $request = null,
    ) {
    }

    public function getDetector($name)
    {
        if (isset($this->detectors[$name])) {
            return Container::getInstance()->make($this->detectors[$name]);
        }

        return null;
    }

    public function __call($method, $parameters)
    {
        foreach ($this->detectors as $detector) {
            if (method_exists($detector, $method)) {
                $instance = call_user_func_array($detector.'::getInstance', [$this->request]);
                return call_user_func_array([$instance, $method], $parameters);
            }
        }

        throw new BadMethodCallException(
            sprintf("Can't call %s method.", $method),
        );
    }
}
