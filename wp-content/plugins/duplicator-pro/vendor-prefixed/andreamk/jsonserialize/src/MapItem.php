<?php

/**
 * Map item class
 *
 * @package VendorDuplicator\Amk\JsonSerialize
 */
namespace VendorDuplicator\Amk\JsonSerialize;

/**
 * Map item element
 */
class MapItem
{
    /** @var ?string */
    public $type = null;
    /** @var self[] */
    public $childs = [];
}
