<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(private string $from, private string $to) {}

    public function collection()
    {
        $rows = Order::whereBetween('entry_date', [$this->from, $this->to])->selectRaw('DATE(entry_date) as tanggal, COUNT(*) as jumlah_order, SUM(total_price) as pendapatan')
            ->groupBy(DB::raw('DATE(entry_date)'))->orderBy('tanggal')->get();
        $data = collect([['Periode', $this->from.' s/d '.$this->to, '', ''], ['Tanggal', 'Jumlah Order', 'Pendapatan', 'Detail kategori']]);
        foreach ($rows as $row) {
            $categories = DB::table('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->leftJoin('items', 'items.id', '=', 'order_items.item_id')->leftJoin('categories', 'categories.id', '=', 'items.category_id')
                ->whereDate('orders.entry_date', $row->tanggal)->select('categories.name', DB::raw('SUM(order_items.subtotal) as amount'))->groupBy('categories.name')->get()
                ->map(fn ($category) => $category->name.': Rp '.number_format($category->amount, 0, ',', '.'))->implode(' | ');
            $data->push([$row->tanggal, (int) $row->jumlah_order, (float) $row->pendapatan, $categories ?: '-']);
        }
        $data->push(['TOTAL', $rows->sum('jumlah_order'), (float) $rows->sum('pendapatan'), '']);
        return $data;
    }

    public function headings(): array { return []; }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $last = $event->sheet->getHighestRow();
            $event->sheet->getStyle('A1:D2')->getFont()->setBold(true);
            $event->sheet->getStyle('A'.$last.':D'.$last)->getFont()->setBold(true);
            $event->sheet->getStyle('C3:C'.$last)->getNumberFormat()->setFormatCode('"Rp" #,##0');
        }];
    }
}
