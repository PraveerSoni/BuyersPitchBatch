<?php
interface TaxonomyMatcherService {

	function mapTaxonomyForInput(PostDetailsDTO $postDetailsDTO);

	function mapStringMatch(PostDetailsDTO $postDeatilsDTO);

	function mapWithTaxonomyTable($string,$taxonomyCategoryObjArray=null);
	
	function getCategoryHireracy($childCategoryId);
}
?>