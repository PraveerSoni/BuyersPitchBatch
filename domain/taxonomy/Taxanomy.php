<?php
class Taxanomy {
	private $brandTag;
	private $typeTag;
	private $keywords;
	private $genderTag;
	private $ageGroupTag;
	private $serviceTag;
	private $serviceNameTag;
	private $serviceTypeTag;
	private $synonym;

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}
	public function getKeywords() {
		return $this->keywords;
	}

	public function setBrandTag($btag) {
		$this->brandTag = $btag;
	}

	public function getBrandTag() {
		return $this->brandTag;
	}

	public function setTypeTag($tTag) {
		$this->typeTag = $tTag;
	}

	public function getTypeTag() {
		return $this->typeTag;
	}

	public function setGenderTag($gTag) {
		$this->genderTag = $gTag;
	}

	public function getGenderTag() {
		return $this->genderTag;
	}

	public function setAgeGroupTag($ageTag) {
		$this->ageGroupTag = $ageTag;
	}

	public function getAgetGroupTag() {
		return $this->ageGroupTag;
	}

	public function setServiceTag($sTag) {
		$this->serviceTag = $sTag;
	}

	public function getServiceTag() {
		return $this->serviceTag;
	}

	public function setServiceNameTag($sNameTag) {
		$this->serviceNameTag = $sNameTag;
	}

	public function getServiceNameTag() {
		return $this->serviceNameTag;
	}

	public function setServiceTypeTag($sTypeTag) {
		return $this->serviceTypeTag = $sTypeTag;
	}

	public function getServiceTypeTag() {
		return $this->serviceTypeTag;
	}

	public function setSynonym($synonym) {
		$this->synonym = $synonym;
	}

	public function getSynonym() {
		return $this->synonym;
	}
	public function __toString() {
		$stringValue = "[Taxanomy { Entire =".$this->getTaxonomyString();
		return $stringValue;
	}

	public function getTaxonomyString() {
		$stringValue = $this->keywords;
		if(null != $this->synonym) {
			$stringValue = $stringValue.",".$this->synonym;
		}if (null != $this->ageGroupTag) {
			$stringValue = $stringValue.",".$this->ageGroupTag;
		} if(null != $this->brandTag) {
			$stringValue = $stringValue.",".$this->brandTag;
		} if(null != $this->genderTag){
			$stringValue = $stringValue.",".$this->genderTag;
		} if(null != $this->serviceNameTag) {
			$stringValue = $stringValue.",".$this->serviceNameTag;
		} if(null != $this->serviceTag) {
			$stringValue = $stringValue.",".$this->serviceTag;
		} if(null != $this->serviceTypeTag) {
			$stringValue = $stringValue.",".$this->serviceTypeTag;
		} if(null != $this->typeTag) {
			$stringValue = $stringValue.",".$this->typeTag;
		}
		return $stringValue;
	}
}