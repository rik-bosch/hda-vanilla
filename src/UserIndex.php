<?php

class UserIndex {
    private array $data;
    private int $offset = 0;
    private int $limit = 20;
    private ?string $sortBy = null;

    function __construct(array $data, array $params) {
        if (array_key_exists('offset', $params)) {
            $this->offset = intval($params['offset']);
        }
        if (array_key_exists('limit', $params)) {
            $this->limit = intval($params['limit']);
        }
        if (array_key_exists('sortBy', $params)) {
            $key = $params['sortBy'];
            if ($key !== 'id' && array_key_exists($key, $data[0])) {
                $this->sortBy = $key;
                usort($data, function($a,$b) use ($key) { return strcmp($a[$key], $b[$key]); });
            }
        }
        $this->data = $data;
    }

    public function getData(): array {
        return array_slice($this->data, $this->offset, $this->limit);
    }

    public function getOffset(): int {
        return $this->offset;
    }

    public function getLimit(): int {
        return $this->limit;
    }

    private function queryString(int $o): string {
        $params = [];
        if ($this->sortBy !== null) {
            $params[] = "sortBy={$this->sortBy}";
        }
        if ($o > 0) {
            $params[] = "offset={$o}";
        }
        if ($this->limit !== 20) {
            $params[] = "limit={$this->limit}";
        }
        return (count($params) > 0)
            ? '?' . implode('&', $params)
            : '';
    }
    
    private function getLastOffset(): int {
        $dataCount = count($this->data);
        $restValue = $dataCount % $this->limit;
        return ($restValue > 0)
            ? $dataCount - $restValue
            : $dataCount - $this->limit;
    }

    public function getUrl() {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        return "http://{$_SERVER['HTTP_HOST']}{$path}";
    }

    public function getLinks(): array {
        $url = $this->getUrl();
        $next = $this->offset + $this->limit;
        $last = $this->getLastOffset();
        $links = [];

        if ($last > 0) {
            $links['first'] = [
                'href' => $url . $this->queryString(0),
            ];
        }
        if ($this->offset > 0) {
            $links['prev'] = [
                'href' => $url . $this->queryString($this->offset - $this->limit, $this->limit),
            ];
        }
        $links['self'] = [
            'href' => $url . $this->queryString($this->offset, $this->limit),
        ];
        if ($next <= $last) {
            $links['next'] = [
                'href' => $url . $this->queryString($next),
            ];
        }

        if ($last > 0) {
            $links['last'] = [
                'href' => $url . $this->queryString($last),
            ];
        }
        return $links;
    }
}
