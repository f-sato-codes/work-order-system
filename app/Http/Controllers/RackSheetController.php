<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRackSheetRequest;
use App\Models\RackSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RackSheetController extends Controller
{
    public function store(StoreRackSheetRequest $request)
    {
        $validated = $request->validated();

        $rackSheet = DB::transaction(function () use ($validated) {

            $rackSheet = RackSheet::create([
                'work_date' => $validated['work_date'],
                'daily_slot_no' => $validated['daily_slot_no'],
                'rack_no' => $validated['rack_no'],
                'spec_code' => $validated['spec_code'],
                'created_by_user_id' => Auth::id(),
                'racking_completed_at' => $validated['racking_completed_at'],
                'note' => $validated['note'] ?? null,
            ]);

            // lines.* を line_no 付きで保存（1,2,3…）
            foreach ($validated['lines'] as $index => $line) {
                $rackSheet->lines()->create([
                    'line_no' => $index + 1,
                    'control_code' => $line['control_code'],
                    'customer_name' => $line['customer_name'],
                    'job_code' => $line['job_code'] ?? null,
                    'planned_qty' => $line['planned_qty'],
                    'racked_qty' => $line['racked_qty'],
                    'note' => $line['note'] ?? null,
                ]);
            }

            return $rackSheet;
        });

        return redirect()->route('rack_sheets.show', $rackSheet);
    }
}