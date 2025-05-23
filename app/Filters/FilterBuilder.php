<?php namespace App\Filters;

class FilterBuilder {
    protected $query;
    protected $filters;
    protected $namespace;

    public function __construct($query, $filters, $namespace)
    {
        $this->query = $query;
        $this->filters = $filters;
        $this->namespace = $namespace;
    }

    public function apply()
    {
        foreach ($this->filters as $name => $value) {
            $normailizedName = ucfirst($name);
            $class = $this->namespace . "\\{$normailizedName}";

            if (! class_exists($class)) {
                continue;
            }

            if( (is_array($value) && sizeof($value)) || (!is_array($value) && strlen($value)) ) {
                (new $class($this->query))->handle($value, $this->filters);
            }
        }

        return $this->query;
    }
}

