<?php
interface TaxonomyDao {

	function findProductMap($string,$categoryIdArray=null,$productCategoryMapIdAddary = null);

	function findProductSynonnymMap($string, $categoryIdArray=null, $productCategoryMapIdAddary = null);

	function findCategory($string,$categoryIdArray=null);
	
	function findCategorySynonym($string,$categoryIdArray=null);
	
	function findAllNotificationWithCategory();
	
	function findParentCategory($childCategoryId);
	
	function findProductMapForCode($string,$categoryIdArray=null,$productCategoryMapIdAddary = null);
	
	function findProductMapForBrand($string,$categoryIdArray=null,$productCategoryMapIdAddary = null);
	
	function findProductMapForSubBrand($string,$categoryIdArray=null,$productCategoryMapIdAddary = null);
	
	function findProductSynonnymMapForBrand($string, $categoryIdArray=null, $productCategoryMapIdAddary = null);
	
	function findProductSynonnymMapForSubBrand($string, $categoryIdArray=null, $productCategoryMapIdAddary = null);
	
	function findProductSynonnymMapForCode($string, $categoryIdArray=null, $productCategoryMapIdAddary = null);
}
?>