<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $dateFrom;
    protected $dateTo;
    protected $paymentMethod;
    protected $status;

    public function __construct($dateFrom = null, $dateTo = null, $paymentMethod = null, $status = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
    }

    public function collection(): Collection
    {
        $query = Order::with(['items', 'payment', 'user']);

        if ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo . ' 23:59:59');
        }
        if ($this->paymentMethod) {
            $query->where('payment_method', $this->paymentMethod);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderByDesc('created_at')->get()->map(function ($order) {
            return [
                $order->order_number,
                $order->user?->name ?? '-',
                $order->recipient_name,
                $order->recipient_phone,
                $order->shipping_address . ', ' . $order->shipping_city . ', ' . $order->shipping_province . ' ' . $order->shipping_postal_code,
                $order->created_at->format('d/m/Y H:i'),
                $order->status,
                $order->payment_method ?? '-',
                number_format($order->subtotal, 0, ',', '.'),
                number_format($order->discount_amount, 0, ',', '.'),
                number_format($order->shipping_cost, 0, ',', '.'),
                number_format($order->total_amount, 0, ',', '.'),
                $order->items->sum('quantity'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Account',
            'Recipient Name',
            'Recipient Phone',
            'Shipping Address',
            'Date',
            'Status',
            'Payment Method',
            'Subtotal',
            'Discount',
            'Shipping Cost',
            'Total Amount',
            'Total Items',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
