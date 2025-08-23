<?php

namespace App\Http\Controllers;

use App\Models\division;
use App\Models\districs;
use App\Models\thana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class addresController extends Controller
{

    // Division fetch
    public function ShowDivision(){
    $divisions = DB::table('division')->select('ID',  'Division')->get();
        return response()->json($divisions);
    }

    // District fetch by division ID
    public function ShowDistrict($divisionId){
        $districts = DB::table('district')
            ->where('DID', $divisionId)
            ->select('DID', 'District', 'DesID')
            ->get();
        return response()->json($districts);
    }

    // Thana fetch by district DesID
    public function ShowThana($desId){
        $thanas = DB::table('thana')
            ->where('Des_ID', $desId)
            ->select('Thana_ID', 'Thana')
            ->get();
        return response()->json($thanas);
    }








}
