<?php

namespace App;

class Dijkstra
{
    protected $graph;
 
    public function __construct($graph) {
        $this->graph = $graph;
    }
 
    public function shortestPath($source, $target) {
        $d = array();
        $pi = array();
        $Q = new \SplPriorityQueue();
 
        foreach ($this->graph as $v => $adj) {
            $d[$v] = INF;
            $pi[$v] = null;
            foreach ($adj as $w => $cost) {
                $Q->insert($w, $cost);
            }
        }
 
        $d[$source] = 0;
 
        while (!$Q->isEmpty()) {
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                foreach ($this->graph[$u] as $v => $cost) {
                    $alt = $d[$u] + $cost;
                    if ($alt < $d[$v]) {
                        $d[$v] = $alt;
                        $pi[$v] = $u;
                    }
                }
            }
        }
 
        $S = new \SplStack();
        $u = $target;
        $dist = 0;
        while (isset($pi[$u]) && $pi[$u]) {
            $S->push($u);
            $dist += $this->graph[$u][$pi[$u]];
            $u = $pi[$u];
        }
 
        if ($S->isEmpty()) {
            return "Нет пути из $source в $target";
        }
        else {
            $S->push($source);
            $this->dist = $dist;
            $this->points = [];

            foreach ($S as $v) {
                array_push($this->points, $v);
            }
        }
    }
}