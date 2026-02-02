<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Rent::with('tenant.room.house')->latest();

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

          $rents = $query->paginate(20);
        $totalCollected = $query->clone()->where('status', 'paid')->sum('total_amount');
        $totalPending = $query->clone()->where('status', 'pending')->sum('total_amount');

        return view('admin.reports.index', compact('rents', 'totalCollected', 'totalPending'));
    }

    public function export(Request $request)
    {
        $rents = Rent::with('tenant.room.house')
                     ->when($request->month, function($q) use ($request) {
                         $q->where('month', $request->month);
                     })
                     ->get();

        $filename = "rent_report_" . date('Y-m-d') . ".csv";
        
        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['Date', 'Tenant', 'Property', 'Room', 'Month', 'Rent', 'Maintenance', 'EB Cost', 'Total', 'Status']);

        foreach ($rents as $rent) {
            fputcsv($handle, [
                $rent->created_at->format('Y-m-d'),
                $rent->tenant->name,
                $rent->room->house->name,
                $rent->room->room_no,
                $rent->month,
                $rent->rent_amount,
                $rent->maintenance_amount,
                $rent->eb_amount,
                $rent->total_amount,
                $rent->status
            ]);
        }

        fseek($handle, 0);
        
        return response()->stream(
            function () use ($handle) {
                fpassthru($handle);
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    public function invoice(Rent $rent)
    {
         
    //    return  $rent;
         
         $tenants=Tenant::where('id',$rent->tenant_id)->first();
          $type=$tenants->type;
        return view('admin.reports.invoice', compact('rent','type'));
    }
    public function updateStatus(Request $request, $id)
{
     
      $rent = Rent::find($id);
    $rent->status = $request->status;
    $rent->save();

    return response()->json(['success' => true]);
}

}
