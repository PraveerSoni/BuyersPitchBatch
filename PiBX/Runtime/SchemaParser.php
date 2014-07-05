<?php
/**
 * Copyright (c) 2010, Christoph Gockel.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * * Neither the name of PiBX nor the names of its contributors may be used
 *   to endorse or promote products derived from this software without specific
 *   prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
require_once ROOT_PATH.'PiBX/CodeGen/TypeUsage.php';
require_once ROOT_PATH.'PiBX/ParseTree/Tree.php';
require_once ROOT_PATH.'PiBX/ParseTree/ChoiceNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/ComplexTypeNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/ElementNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/EnumerationNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/RestrictionNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/RestrictionNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/RootNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/SequenceNode.php';
require_once ROOT_PATH.'PiBX/ParseTree/SimpleTypeNode.php';
/**
 * The SchemaParser parses a given XML-Schema file.
 * While parsing, it creates the parse-tree.
 * 
 * @author Christoph Gockel
 */
class PiBX_CodeGen_SchemaParser {
    private static $XSD_NS = "http://www.w3.org/2001/XMLSchema";
    private $schemaNamespace;
    /**
     * @var PiBX_ParseTree_Tree Internal ParseTree that is being built.
     */
    private $parseTree;

    private $typeUsage;

    /**
     * Creates a new SchemaParser.
     *
     * @param string $schemaFile
     */
    public function __construct($schemaFile = '', PiBX_CodeGen_TypeUsage $typeUsage = null) {
        if ($schemaFile !== '') {
            $this->setSchemaFile($schemaFile);
        }

        if ($typeUsage === null) {
            $typeUsage = new PiBX_CodeGen_TypeUsage();
        }
        $this->typeUsage = $typeUsage;
    }

    /**
     *
     * @param string $schemaFile
     */
    private function init($schemaFile) {
        $this->xml = simplexml_load_file($schemaFile);

        if (!is_object($this->xml)) {
            // @TODO: add simple-xml-error message
            throw new Exception("Invalid XSD");
        }

        $this->initializeXml();
    }

    private function initializeXml() {
        $nsPrefix = array_search(self::$XSD_NS, $this->xml->getDocNamespaces());
        $this->xml->registerXPathNamespace($nsPrefix, self::$XSD_NS);
        $this->schemaNamespace = $nsPrefix;
    }

    /**
     * Starts the parsing process.
     * @return PiBX_ParseTree_ParseTree
     */
    public function parse() {
        $this->parseTree = new PiBX_ParseTree_RootNode();

        // starts a straight-forward schema parsing
        $this->parseSchemaNodes($this->xml, $this->parseTree);

        return $this->parseTree;
    }

    /**
     * Recursive top-down parsing (depth-first) of an XSD-Schema.
     * 
     * @param SimpleXMLElement $xml The current xml-node to parse
     * @param PiBX_ParseTree_ParseTree $part Current ParseTree-node
     * @param int $level Parse-tree level (current depth)
     */
    private function parseSchemaNodes(SimpleXMLElement $xml, PiBX_ParseTree_Tree $part, $level = 0) {
        $ns = $this->schemaNamespace;

        foreach ($xml->children($ns, true) as $child) {
            $name = (string)$child->getName();

            if ($name == 'element') {
                $newPart = new PiBX_ParseTree_ElementNode($child, $level);
                $attributes = $child->attributes();
                
                $type = (string)$attributes['type'];
                $type = $this->getStringWithoutNamespace($type);
                
                $this->typeUsage->addType($type);
            } elseif ($name == 'simpleType') {
                $newPart = new PiBX_ParseTree_SimpleTypeNode($child, $level);
            } elseif ($name == 'complexType') {
                $newPart = new PiBX_ParseTree_ComplexTypeNode($child, $level);
            } elseif ($name == 'sequence') {
                $newPart = new PiBX_ParseTree_SequenceNode($child, $level);
            } elseif ($name == 'choice') {
                $newPart = new PiBX_ParseTree_ChoiceNode($child, $level);
            } elseif ($name == 'restriction') {
                $newPart = new PiBX_ParseTree_RestrictionNode($child, $level);
            } elseif ($name == 'enumeration') {
                $newPart = new PiBX_ParseTree_EnumerationNode($child, $level);
            }

            $this->parseSchemaNodes($child, $newPart, $level+1);
            $part->add($newPart);
        }
    }

    /**
     *
     * @param SimpleXMLElement $xml The Schema-XML (or a fragment of a schema)
     */
    public function setSchema(SimpleXMLElement $xml) {
        $this->xml = $xml;
        $this->initializeXml();
    }

    public function setSchemaFile($schemaFile) {
        if (!file_exists($schemaFile) || !is_readable($schemaFile)) {
            throw new InvalidArgumentException("Unreadable XSD-Schema file: $schemaFile");
        }

        $this->init($schemaFile);
    }

    /**
     * Helper method to strip off namespaces from a string.
     * 
     * @param string $string
     * @return string The namespace-free string
     */
    private function getStringWithoutNamespace($string) {
       if (strpos($string, ':')) {
           $parts = explode(':', $string);
           return $parts[1];
       }

       return $string;
    }
}
