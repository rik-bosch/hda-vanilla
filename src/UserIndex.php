<?php

class UserIndex {
    private array $data;
    private int $offset = 0;
    private int $limit = 20;

    function __construct(array $data, array $params) {
        $this->data = $data;
        if (array_key_exists('offset', $params)) {
            $this->offset = intval($params['offset']);
        }
        if (array_key_exists('limit', $params)) {
            $this->limit = intval($params['limit']);
        }
    }

    public function getData(): array {
        return array_slice($this->data, $this->offset, $this->limit);
    }

    private function queryString(int $o): string {
        $params = [];
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

    public function getLinks(): array {
        $uri = '/api/users';
        $next = $this->offset + $this->limit;
        $last = $this->getLastOffset();
        $links = [];

        if ($last > 0) {
            $links['first'] = [
                'href' => $uri . $this->queryString(0),
            ];
        }
        if ($this->offset > 0) {
            $links['prev'] = [
                'href' => $uri . $this->queryString($this->offset - $this->limit, $this->limit),
            ];
        }
        $links['self'] = [
            'href' => $uri . $this->queryString($this->offset, $this->limit),
        ];
        if ($next <= $last) {
            $links['next'] = [
                'href' => $uri . $this->queryString($next),
            ];
        }

        if ($last > 0) {
            $links['last'] = [
                'href' => $uri . $this->queryString($last),
            ];
        }
        return $links;
    }
}
