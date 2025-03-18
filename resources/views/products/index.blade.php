<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h2 class="text-center">Product Catalog</h2>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <input type="text" id="search" class="form-control" placeholder="Search products...">
        </div>
    </div>

    <!-- Product List -->
    <div class="row" id="product-list">
        @foreach ($products as $product)
            <div class="col-md-3 product-item">
                <div class="card p-3 mb-3">
                    <h5>{{ $product['name'] }}</h5>
                    <p>Price: ${{ $product['price'] }}</p>
                    <button class="btn btn-primary add-to-cart" data-id="{{ $product['id'] }}"
                        data-name="{{ $product['name'] }}" data-price="{{ $product['price'] }}">
                        Add to Cart
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Shopping Cart -->
    <h3 class="mt-5 mb-3">Your cart items</h3>
    <div class="row">
        <div class="col-md-9">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-body"></tbody>
            </table>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Total
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="card-text">Sub Total ($): </h6>
                        <h6 id="sub-total">0.00</h6>
                    </div>
                    <p class="fs-12px">If a user adds 3 or more products to the cart, they get a 10% discount.
                    </p>
                    <div class="d-flex justify-content-between">
                        <h6 class="card-text">Discount ($): </h6>
                        <h6 id="discount">0.00</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="card-text">Total Amount ($): </h6>
                        <h6 id="cart-total">0.00</h6>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-primary w-100">Continue to payment</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateCart() {
            $.get("{{ route('cart.show') }}", function(response) {
                let cartHTML = "";
                $.each(response.cart, function(id, item) {
                    cartHTML += `
                        <tr>
                            <td>${item.name}</td>
                            <td>$${item.price}</td>
                            <td>${item.quantity}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-from-cart" data-id="${id}">Remove</button>
                            </td>
                        </tr>`;
                });
                $("#cart-body").html(cartHTML);
                $("#sub-total").text(response.subTotal.toFixed(2));
                $("#discount").text(response.discount.toFixed(2));
                $("#cart-total").text(response.total.toFixed(2));
            });
        }

        $(document).ready(function() {
            updateCart();

            $(".add-to-cart").click(function() {
                let productId = $(this).data("id");
                let productName = $(this).data("name");
                let productPrice = $(this).data("price");

                $.post("{{ route('cart.add') }}", {
                    product_id: productId,
                    product_name: productName,
                    product_price: productPrice,
                    _token: "{{ csrf_token() }}"
                }, function() {
                    updateCart();
                });
            });

            $(document).on("click", ".remove-from-cart", function() {
                let productId = $(this).data("id");

                $.post("{{ route('cart.remove') }}", {
                    product_id: productId,
                    _token: "{{ csrf_token() }}"
                }, function() {
                    updateCart();
                });
            });

            // Product Search Functionality
            $("#search").on("keyup", function() {
                let searchText = $(this).val().toLowerCase();
                $(".product-item").each(function() {
                    let productName = $(this).find("h5").text().toLowerCase();
                    $(this).toggle(productName.includes(searchText));
                });
            });
        });
    </script>

</body>

</html>
