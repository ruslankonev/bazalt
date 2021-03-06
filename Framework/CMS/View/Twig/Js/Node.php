<?php

class CMS_View_Twig_Js_Node extends Twig_Node
{
    public function __construct($nodes = array(), $filename, $lineno, $tag = 'metadata')
    {
        parent::__construct($nodes, array('filename' => $filename), $lineno, $tag);
    }

    /**
     * Compiles the tag
     *
     * @param object $compiler
     * @return void
     */
    public function compile(Twig_Compiler $compiler)
    {
        /*if ($this->hasNode('params')) {
            $compiler
                ->write('$route_params = ')
                ->subcompile($this->getNode('params'))
                ->raw(";\n");
        } else {
            $compiler
                ->write('$route_params = array()')
                ->raw(";\n");
        }*/

        $compiler
            ->raw('echo Assets_JS::add("' . addslashes($this->getAttribute('filename')) . '/" .')
            ->subcompile($this->getNode('name'))
            ->raw(");\n");
    }
}