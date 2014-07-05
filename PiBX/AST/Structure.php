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
require_once ROOT_PATH.'PiBX/AST/Tree.php';
require_once ROOT_PATH.'PiBX/AST/StructureType.php';
/**
 * A Structure represents an (un)ordered set of types/elements.
 * Think of an abstraction of ordered and choice complex-types in a schema.
 *
 * @author Christoph Gockel
 */
class PiBX_AST_Structure extends PiBX_AST_Tree {
    /**
     * @var PiBX_AST_StructureType An Enum, what type this structure is
     */
    private $structureType;
    private $getMethod;
    private $setMethod;

    public function  __construct($name = '', $type = '') {
        parent::__construct($name, $type);
    }

    public function setStructureType(PiBX_AST_StructureType $type) {
        $this->structureType = $type;
    }

    public function getStructureType() {
        return $this->structureType;
    }

    public function setGetMethod($methodName) {
        $this->getMethod = $methodName;
    }
    public function getGetMethod() {
        return $this->getMethod;
    }

    public function setSetMethod($methodName) {
        $this->setMethod = $methodName;
    }
    public function getSetMethod() {
        return $this->setMethod;
    }

    public function getName() {
        // Normally a Structure itself does not have a name.
        // But it can have a name when the current AST-composite
        // is built off the binding.xml
        if ($this->name !== '') {
            // Binding -> AST
            return $this->name;
        } else {
            // XSD -> AST
            return $this->getParent()->getName();
        }
    }

    public function accept(PiBX_AST_Visitor_VisitorAbstract $v) {
        if ($v->visitStructureEnter($this)) {
            foreach ($this->children as $child) {
                if ($child->accept($v) === false) {
                    break;
                }
            }
        }

        return $v->visitStructureLeave($this);
        //poopoo
    }
}
