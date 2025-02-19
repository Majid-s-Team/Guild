<ul>
    @forelse ($products as $product)
        <li>{{ $product->name }}</li>
    @empty
        <li>no products found</li>
    @endforelse
</ul>
