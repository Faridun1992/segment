<?php

namespace App\Http\Controllers;

use App\Services\SegmentService;
use Illuminate\Http\Request;

class SegmentController extends Controller
{
    public function __construct(
       public SegmentService $service
    )
    {
    }

    public function getSegments()
    {
        $segmentRFM = $this->service->getSegmentRFM();

        return view('welcome', compact('segmentRFM'));
    }
}
