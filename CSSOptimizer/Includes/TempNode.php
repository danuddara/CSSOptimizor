<?php 

class TempNode extends SplPriorityQueue // available in php 5, 5.3 > higher, create a queue base on the priority.
{
	
	 public function compare($a, $b) //overides the compare in priorityqueue and push the elements. the priotiy of the elements have been compared
    {
        if ($a == $b)
        {
            return 0;
        }
        return ($a > $b) ? -1 : 1; // positive if its a grater than b postive else negative interger.  it will make the shifting up and down. 
    }
	
}
?>