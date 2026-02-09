<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="bg-light">
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>User</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockIns as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="fw-bold">{{ $log->product->name ?? 'Deleted Product' }}</div>
                    <small class="text-muted">{{ $log->product->code ?? '-' }}</small>
                </td>
                <td><span class="badge bg-success">+{{ $log->quantity }}</span></td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>{{ $log->notes }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">No stock in records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $stockIns->appends(['returned_page' => $returnedStocks->currentPage(), 'out_page' => $stockOuts->currentPage(), 'damaged_page' => $damagedStocks->currentPage(), 'period' => $period])->links() }}
