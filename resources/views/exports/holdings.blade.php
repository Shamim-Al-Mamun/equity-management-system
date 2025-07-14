<table>
    <thead>
        <tr>
            <th>Client</th>
            <th>Stock</th>
            <th>Sector</th>
            <th>Quantity</th>
            <th>Purchase Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($holdings as $h)
            <tr>
                <td>{{ $h->client->name }}</td>
                <td>{{ $h->stock_symbol }}</td>
                <td>{{ $h->sector }}</td>
                <td>{{ $h->quantity }}</td>
                <td>{{ $h->purchase_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
