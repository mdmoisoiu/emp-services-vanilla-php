<?php

class PositionManager {

	public static $REGISTRY_KEY = "PositionManager";
	
	/**
	 * Constructor.
	 */
	function PositionManager() {
	}		
	
	/**
	 * sortPositionsAsOrgChart
	 * @param array $positions
	 * @return array of Position
	 */
	public function sortPositionsAsOrgChart($positions){
		/* @var $position Position */

		$positionChildrenIdById = array();
		$positionsIdMap = array();
		$rootsIds = array();
		foreach ($positions as $position){
			if(isset($positionChildrenIdById[$position->lineManagerId])==false){
				$positionChildrenIdById[$position->lineManagerId] = array();
			}
			array_push($positionChildrenIdById[$position->lineManagerId], $position->id);
			
			if($position->lineManagerId==0){
				$rootsIds []= $position->id; 
			}
			
			$positionsIdMap[$position->id] = $position;
		}
		
		$resultPositions = array();		
		foreach($rootsIds as $positionId){
			$this->createPositionTree($positionId, $positionsIdMap, $positionChildrenIdById, 0, $resultPositions);
		}
		return $resultPositions;
	}
	
	/**
	 *
	 * @param Position $position
	 */
	function createPositionTree($positionId, &$positionsIdMap, &$positionChildrenIdById, $level, &$positions) {
		if(isset($positionsIdMap[$positionId])){
			$position = $positionsIdMap[$positionId];
			
			if(in_array($position, $positions)==false){
				$position->orgChartLevel = $level;
				$positions []= $position;
				
				if(isset($positionChildrenIdById[$position->id])){
					$childrensIds = $positionChildrenIdById[$position->id];
					foreach($childrensIds as $childId){
						$this->createPositionTree($childId, $positionsIdMap, $positionChildrenIdById, ($level+1), $positions);
					}
				}
			}
		}
	}
}

?>