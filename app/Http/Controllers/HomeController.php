<?php

namespace App\Http\Controllers;

use App\Dijkstra;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
//        array(
//            'A' => array('B' => 3, 'D' => 3, 'F' => 6),
//            'B' => array('A' => 3, 'D' => 1, 'E' => 3),
//            'C' => array('E' => 2, 'F' => 3),
//            'D' => array('A' => 3, 'B' => 1, 'E' => 1, 'F' => 2),
//            'E' => array('B' => 3, 'C' => 2, 'D' => 1, 'F' => 5),
//            'F' => array('A' => 6, 'C' => 3, 'D' => 2, 'E' => 5),
//        );

        $graph = [];

        for ($i = 0; $i < count($request->points); $i++) {
            $point = $request->points[$i];
            if (is_null($point)) continue;
            $graph[$point] = [];
            for ($j = 0; $j < count($request->targets[$i]["tar"]); $j++) {
                if (is_null($request->targets[$i]["tar"][$j])) continue;
                $graph[$point][$request->targets[$i]["tar"][$j]] = $request->targets[$i]["val"][$j];
            }
        }

        $dijkstra = new Dijkstra($graph);
        $dijkstra->shortestPath($request->from, $request->to);

        return view('home', [
            'dijkstra'=>$dijkstra,
            'graph'=>$graph
        ]);
    }
}
