<?php

class JsonApiRequestData
{
    private $type;
    private $attributes;

    public function __construct($type, array $attributes, $id = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->attributes = $attributes;
    }

    public function type()
    {
        return $this->type;
    }

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function attributes()
    {
        return $this->attributes;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        $data = [
            'data' => [
                'type' => $this->type,
                'attributes' => $this->attributes,
            ],
        ];

        if ($this->id) {
            $data['data']['id'] = $this->id;
        }

        return $data;
    }
}
