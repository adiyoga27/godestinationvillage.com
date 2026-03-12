<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Days;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\Period;

class AnalyticController extends Controller
{
    public $analytic;
    public function __construct(Analytics $analytic) {
        $this->analytic = $analytic;
    }
    public function index()
    {
        return json_encode($this->analytic->fetchVisitorsAndPageViews(Period::days(7)));
    }
}
