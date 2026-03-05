@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">ラッキング完了 登録</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 border">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rack_sheets.store') }}">
        @csrf

        <div class="space-y-3 mb-6">
            <div>
                <label class="block text-sm">作業日</label>
                <input type="date" name="work_date" value="{{ old('work_date', now()->toDateString()) }}" class="border p-2 w-full">
            </div>

            <div>
                <label class="block text-sm">生産番号（daily_slot_no）</label>
                <input type="number" name="daily_slot_no" value="{{ old('daily_slot_no') }}" class="border p-2 w-full">
            </div>

            <div>
                <label class="block text-sm">枠No（rack_no）</label>
                <input type="text" name="rack_no" value="{{ old('rack_no') }}" class="border p-2 w-full">
            </div>

            <div>
                <label class="block text-sm">仕様（spec_code）</label>
                <input type="text" name="spec_code" value="{{ old('spec_code') }}" class="border p-2 w-full">
                {{-- 固定10種にするなら後で<select>に置き換え --}}
            </div>

            <div>
                <label class="block text-sm">ラッキング完了時刻（racking_completed_at）</label>
                <input type="time" name="racking_completed_at" value="{{ old('racking_completed_at') }}" class="border p-2 w-full">
            </div>

            <div>
                <label class="block text-sm">備考</label>
                <textarea name="note" class="border p-2 w-full" rows="2">{{ old('note') }}</textarea>
            </div>
        </div>

        <h2 class="text-lg font-semibold mb-2">明細</h2>

        <div id="lines" class="space-y-4">
            {{-- oldがある場合は復元、なければ1行 --}}
            @php
                $oldLines = old('lines', [
                    ['control_code' => '', 'customer_name' => '', 'job_code' => '', 'planned_qty' => '', 'racked_qty' => '', 'note' => '']
                ]);
            @endphp

            @foreach ($oldLines as $i => $line)
                <div class="border p-3 line-item">
                    <div class="flex justify-between items-center mb-2">
                        <div class="font-semibold">行 {{ $i + 1 }}</div>
                        <button type="button" class="remove-line border px-2 py-1 text-sm">削除</button>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-sm">管理No（control_code）</label>
                            <input type="text" name="lines[{{ $i }}][control_code]" value="{{ $line['control_code'] }}" class="border p-2 w-full">
                        </div>

                        <div>
                            <label class="block text-sm">顧客名（customer_name）</label>
                            <input type="text" name="lines[{{ $i }}][customer_name]" value="{{ $line['customer_name'] }}" class="border p-2 w-full">
                        </div>

                        <div>
                            <label class="block text-sm">物件名（job_code）</label>
                            <input type="text" name="lines[{{ $i }}][job_code]" value="{{ $line['job_code'] }}" class="border p-2 w-full">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm">分母（planned_qty）</label>
                                <input type="number" name="lines[{{ $i }}][planned_qty]" value="{{ $line['planned_qty'] }}" class="border p-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm">分子（racked_qty）</label>
                                <input type="number" name="lines[{{ $i }}][racked_qty]" value="{{ $line['racked_qty'] }}" class="border p-2 w-full">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm">備考</label>
                            <input type="text" name="lines[{{ $i }}][note]" value="{{ $line['note'] }}" class="border p-2 w-full">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="my-4">
            <button type="button" id="add-line" class="border px-3 py-2">＋ 行を追加</button>
        </div>

        <div class="mt-6">
            <button class="border px-4 py-2 font-semibold">保存（指示書を作成）</button>
        </div>
    </form>
</div>

<script>
(function () {
    const linesEl = document.getElementById('lines');
    const addBtn = document.getElementById('add-line');

    function renumber() {
        const items = linesEl.querySelectorAll('.line-item');
        items.forEach((item, idx) => {
            item.querySelector('.font-semibold').textContent = `行 ${idx + 1}`;

            // name属性の index を付け替える
            item.querySelectorAll('input, textarea').forEach(el => {
                if (!el.name) return;
                el.name = el.name.replace(/lines\[\d+\]/, `lines[${idx}]`);
            });
        });
    }

    function bindRemoveButtons() {
        linesEl.querySelectorAll('.remove-line').forEach(btn => {
            btn.onclick = () => {
                const items = linesEl.querySelectorAll('.line-item');
                if (items.length <= 1) return; // 最低1行は残す
                btn.closest('.line-item').remove();
                renumber();
            };
        });
    }

    addBtn.addEventListener('click', () => {
        const idx = linesEl.querySelectorAll('.line-item').length;

        const div = document.createElement('div');
        div.className = 'border p-3 line-item';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <div class="font-semibold">行 ${idx + 1}</div>
                <button type="button" class="remove-line border px-2 py-1 text-sm">削除</button>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm">管理No（control_code）</label>
                    <input type="text" name="lines[${idx}][control_code]" class="border p-2 w-full">
                </div>

                <div>
                    <label class="block text-sm">顧客名（customer_name）</label>
                    <input type="text" name="lines[${idx}][customer_name]" class="border p-2 w-full">
                </div>

                <div>
                    <label class="block text-sm">物件名（job_code）</label>
                    <input type="text" name="lines[${idx}][job_code]" class="border p-2 w-full">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm">分母（planned_qty）</label>
                        <input type="number" name="lines[${idx}][planned_qty]" class="border p-2 w-full">
                    </div>

                    <div>
                        <label class="block text-sm">分子（racked_qty）</label>
                        <input type="number" name="lines[${idx}][racked_qty]" class="border p-2 w-full">
                    </div>
                </div>

                <div>
                    <label class="block text-sm">備考</label>
                    <input type="text" name="lines[${idx}][note]" class="border p-2 w-full">
                </div>
            </div>
        `;
        linesEl.appendChild(div);
        bindRemoveButtons();
        renumber();
    });

    bindRemoveButtons();
})();
</script>
@endsection