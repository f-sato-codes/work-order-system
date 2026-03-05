<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRackSheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // rack_sheets
            'work_date' => ['required', 'date'],
            'daily_slot_no' => ['required', 'integer', 'min:1'],
            'rack_no' => ['required', 'string', 'max:255'],
            'spec_code' => ['required', 'string', 'max:255'],
            'racking_completed_at' => ['required', 'date_format:H:i'],
            'note' => ['nullable', 'string'],

            // lines（最低1行）
            'lines' => ['required', 'array', 'min:1'],

            'lines.*.control_code' => ['required', 'string', 'max:255'],
            'lines.*.customer_name' => ['required', 'string', 'max:255'],
            'lines.*.job_code' => ['nullable', 'string', 'max:255'],
            'lines.*.planned_qty' => ['required', 'integer', 'min:0'],
            'lines.*.racked_qty' => ['required', 'integer', 'min:0'],
            'lines.*.note' => ['nullable', 'string'],

            // racked_qty <= planned_qty は後で「after」でチェック
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);
            foreach ($lines as $i => $line) {
                $planned = $line['planned_qty'] ?? null;
                $racked  = $line['racked_qty'] ?? null;

                if (is_numeric($planned) && is_numeric($racked) && (int)$racked > (int)$planned) {
                    $validator->errors()->add("lines.$i.racked_qty", '分子は分母以下にしてください。');
                }
            }
        });
    }
}