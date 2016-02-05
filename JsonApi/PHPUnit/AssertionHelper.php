<?php

use PHPUnit_Framework_TestCase;

class JsonApiAssertionHelper extends PHPUnit_Framework_TestCase
{
    private $json;
    private $primaryData;

    public function __construct($json)
    {
        $this->json = $json;
        $this->primaryData();
    }

    public function primaryData()
    {
        $this->assertTrue(isset($this->json->data), 'Invalid Response - response is empty');

        $primaryData = $this->json->data;

        $this->assertObjectHasAttribute('type', $primaryData, "Response does not contain 'type'");
        $this->assertObjectHasAttribute('attributes', $primaryData, "Response does not have 'attributes'");

        $this->primaryData = $primaryData;
    }

    public function matchFile($jsonFile, array $replacements = [])
    {
        $jsonFromFile = json_decode(file_get_contents($jsonFile));

        foreach ($replacements as $key => $replacement) {
            if (isset($jsonFromFile->data->$key)) {
                $jsonFromFile->data->$key = $replacement;
            }
        }

        $this->assertEquals($jsonFromFile, $this->json);
    }

    public function id()
    {
        return $this->json->data->id;
    }

    public function json()
    {
        return $this->json;
    }

    public function hasId()
    {
        $this->assertObjectHasAttribute('id', $this->primaryData);

        return $this;
    }

    public function idIs($value)
    {
        $this->assertEquals($value, $this->primaryData->id);

        return $this;
    }

    public function typeIs($type)
    {
        $this->assertEquals(
            $type,
            $this->primaryData->type,
            "Type must be {$type}"
        );

        return $this;
    }

    public function attributesValues(array $keyValuePairs)
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->assertEquals(
                $value,
                $this->primaryData->attributes->$key
            );
        }
    }

    public function hasRelationships()
    {
        $this->assertObjectHasAttribute('relationships', $this->json->data, 'Missing relationships key');

        $args = func_get_args();
        $relationships = $this->json->data->relationships;

        foreach ($args as $value) {
            $this->assertObjectHasAttribute($value, $relationships, "Missing include {$value}");
        }

        return $this;
    }

    public function includes()
    {
        $args = func_get_args();

        $this->hasInclude();
        $included = $this->json->relationships;

        foreach ($args as $value) {
            $this->assertObjectHasAttribute($value, $included, "Missing include {$value}");
        }
    }

    public function attributesContain()
    {
        $args = func_get_args();
        foreach ($args as $attributeKey) {
            $this->assertObjectHasAttribute(
                $attributeKey,
                $this->primaryData->attributes,
                "Missng {$attributeKey} in the attributes object."
            );
        }

        return $this;
    }
}