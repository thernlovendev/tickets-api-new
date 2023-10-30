<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for HeartbeatResult StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class HeartbeatResult extends AbstractStructBase
{
    /**
     * The schema
     * Meta information extracted from the WSDL
     * - ref: xs:schema
     * @var \DOMDocument|string|null
     */
    protected $schema = null;
    /**
     * The any
     * @var \DOMDocument|string|null
     */
    protected $any = null;
    /**
     * Constructor method for HeartbeatResult
     * @uses HeartbeatResult::setSchema()
     * @uses HeartbeatResult::setAny()
     * @param \DOMDocument|string|null $schema
     * @param \DOMDocument|string|null $any
     */
    public function __construct($schema = null, $any = null)
    {
        $this
            ->setSchema($schema)
            ->setAny($any);
    }
    /**
     * Get schema value
     * @uses \DOMDocument::loadXML()
     * @param bool $asDomDocument true: returns \DOMDocument, false: returns XML string
     * @return \DOMDocument|string|null
     */
    public function getSchema(bool $asDomDocument = false)
    {
        $domDocument = null;
        if (!empty($this->schema) && $asDomDocument) {
            $domDocument = new \DOMDocument('1.0', 'UTF-8');
            $domDocument->loadXML($this->schema);
        }
        return $asDomDocument ? $domDocument : $this->schema;
    }
    /**
     * Set schema value
     * @uses \DOMDocument::hasChildNodes()
     * @uses \DOMDocument::saveXML()
     * @uses \DOMNode::item()
     * @param \DOMDocument|string|null $schema
     * @return \StructType\HeartbeatResult
     */
    public function setSchema($schema = null): self
    {
        // validation for constraint: xml
        if (!is_null($schema) && !$schema instanceof \DOMDocument && (!is_string($schema) || (is_string($schema) && (empty($schema) || (($schemaDoc = new \DOMDocument()) && false === $schemaDoc->loadXML($schema)))))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a valid XML string', var_export($schema, true)), __LINE__);
        }
        $this->schema = ($schema instanceof \DOMDocument) ? $schema->saveXML($schema->hasChildNodes() ? $schema->childNodes->item(0) : null) : $schema;
        
        return $this;
    }
    /**
     * Get any value
     * @uses \DOMDocument::loadXML()
     * @param bool $asDomDocument true: returns \DOMDocument, false: returns XML string
     * @return \DOMDocument|string|null
     */
    public function getAny(bool $asDomDocument = false)
    {
        $domDocument = null;
        if (!empty($this->any) && $asDomDocument) {
            $domDocument = new \DOMDocument('1.0', 'UTF-8');
            $domDocument->loadXML($this->any);
        }
        return $asDomDocument ? $domDocument : $this->any;
    }
    /**
     * Set any value
     * @uses \DOMDocument::hasChildNodes()
     * @uses \DOMDocument::saveXML()
     * @uses \DOMNode::item()
     * @param \DOMDocument|string|null $any
     * @return \StructType\HeartbeatResult
     */
    public function setAny($any = null): self
    {
        // validation for constraint: xml
        if (!is_null($any) && !$any instanceof \DOMDocument && (!is_string($any) || (is_string($any) && (empty($any) || (($anyDoc = new \DOMDocument()) && false === $anyDoc->loadXML($any)))))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a valid XML string', var_export($any, true)), __LINE__);
        }
        $this->any = ($any instanceof \DOMDocument) ? $any->saveXML($any->hasChildNodes() ? $any->childNodes->item(0) : null) : $any;
        
        return $this;
    }
}
