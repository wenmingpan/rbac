<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function p($arr)
{
    echo "<pre>";
    print_r($arr);
}

function node_merge($node, $pid=0)
{
  
    $arr = array();
    foreach($node as $v) {
        if ($pid == $v['pid']) {
            $v['child'] = node_merge($node, $v['id']);
            $arr[] = $v;
        }
    }
    
    return $arr;
}

