<?php

class TestRunner
{
    public function findTests()
    {
        $tests = array();
        // have to change this for move to parent
        // $className = get_class($this);
        $className = get_called_class();
        $metaClass = new ReflectionClass($className);

        foreach ($metaClass->getMethods() as $metaMethod) {
            $comment = $metaMethod->getDocComment();
            if ($this->commentContainsTestFlag($comment)) {
                $tests[] = $metaMethod->getName();
            }
        }
    
        return $tests;
    }

    private function commentContainsTestFlag($comment)
    {
        return (($comment != false) && 
                (strpos($comment, '@Test') !== false));
    }


    
}
