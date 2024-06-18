<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4 shadow">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold">Order Management</h1>
        </div>
    </header>

    <div class="container mx-auto mt-5 p-5 bg-white rounded shadow">

        <div class="flex justify-between mb-5">
            <button id="add-order" class="bg-green-500 text-white px-4 py-2 rounded">Add Order</button>
            <input type="text" id="search" placeholder="Search..." class="px-3 py-2 border rounded">
        </div>

        <!-- Modal untuk form order -->
        <div id="order-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-5 rounded shadow-lg w-1/2">
                <h2 class="text-xl font-bold mb-4" id="modal-title">Add Order</h2>
                <input type="hidden" id="order-id">
                <div class="mb-3">
                    <label for="code" class="block text-gray-700">Code:</label>
                    <input type="text" id="code" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-3">
                    <label for="order_date" class="block text-gray-700">Order Date:</label>
                    <input type="date" id="order_date" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="block text-gray-700">Customer Name:</label>
                    <input type="text" id="customer_name" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-3">
                    <label for="product_name" class="block text-gray-700">Product Name:</label>
                    <input type="text" id="product_name" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-3">
                    <label for="quantity" class="block text-gray-700">Quantity:</label>
                    <input type="number" id="quantity" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-3">
                    <label for="price" class="block text-gray-700">Price:</label>
                    <input type="number" id="price" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="flex justify-end">
                    <button id="cancel-order" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button id="save-order" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </div>
        </div>

        <!-- Daftar pesanan -->
        <div id="order-list">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-2">ID</th>
                        <th class="py-2">Code</th>
                        <th class="py-2">Order Date</th>
                        <th class="py-2">Customer Name</th>
                        <th class="py-2">Product Name</th>
                        <th class="py-2">Quantity</th>
                        <th class="py-2">Price</th>
                        <th class="py-2">Total</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div id="pagination" class="mt-4 flex justify-center items-center"></div>
    </div>

    <script>
        $(document).ready(function() {
            let currentPage = 1;

            function fetchOrders(page = 1, search = '') {
                $.ajax({
                    url: `/orders/fetch?page=${page}&search=${search}`,
                    method: 'GET',
                    success: function(response) {
                        var ordersTbody = $('#orders-tbody');
                        ordersTbody.empty();
                        response.data.forEach(function(order) {
                            ordersTbody.append(`
                                <tr>
                                    <td class="border px-4 py-2">${order.id}</td>
                                    <td class="border px-4 py-2">${order.code}</td>
                                    <td class="border px-4 py-2">${order.order_date}</td>
                                    <td class="border px-4 py-2">${order.customer_name}</td>
                                    <td class="border px-4 py-2">${order.product_name}</td>
                                    <td class="border px-4 py-2">${order.quantity}</td>
                                    <td class="border px-4 py-2">${order.price}</td>
                                    <td class="border px-4 py-2">${order.total}</td>
                                    <td class="border px-4 py-2">
                                        <button class="edit-order bg-yellow-500 text-white px-2 py-1 rounded" data-id="${order.id}">Edit</button>
                                        <button class="delete-order bg-red-500 text-white px-2 py-1 rounded" data-id="${order.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });

                        $('#pagination').html(response.links); 
                        $('#pagination a').on('click', function(e) {
                            e.preventDefault();
                            var page = $(this).attr('href').split('page=')[1];
                            currentPage = page;
                            fetchOrders(page, $('#search').val());
                        });
                    }
                });
            }

            fetchOrders();
            
            $('#add-order').on('click', function() {
                $('#modal-title').text('Add Order');
                $('#order-id').val('');
                $('#code').val('');
                $('#order_date').val('');
                $('#customer_name').val('');
                $('#product_name').val('');
                $('#quantity').val('');
                $('#price').val('');
                $('#order-modal').removeClass('hidden');
                $('#pagination').addClass('hidden'); 
            });

            $('#cancel-order').on('click', function() {
                $('#order-modal').addClass('hidden');
                $('#pagination').removeClass('hidden');
            });

            $('#save-order').on('click', function() {
                var orderData = {
                    code: $('#code').val(),
                    order_date: $('#order_date').val(),
                    customer_name: $('#customer_name').val(),
                    product_name: $('#product_name').val(),
                    quantity: $('#quantity').val(),
                    price: $('#price').val(),
                    total: $('#quantity').val() * $('#price').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                var orderId = $('#order-id').val();
                var method = orderId ? 'PUT' : 'POST';
                var url = orderId ? `/orders/${orderId}` : '/orders/store';

                $.ajax({
                    url: url,
                    method: method,
                    data: orderData,
                    success: function(response) {
                        fetchOrders(currentPage);
                        $('#order-modal').addClass('hidden');
                    }
                });
            });

            $(document).on('click', '.edit-order', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: `/orders/${id}`,
                    method: 'GET',
                    success: function(response) {
                        $('#modal-title').text('Edit Order');
                        $('#order-id').val(response.id);
                        $('#code').val(response.code);
                        $('#order_date').val(response.order_date);
                        $('#customer_name').val(response.customer_name);
                        $('#product_name').val(response.product_name);
                        $('#quantity').val(response.quantity);
                        $('#price').val(response.price);
                        $('#order-modal').removeClass('hidden');
                        $('#pagination').addClass('hidden');
                    }
                });
            });

            $(document).on('click', '.delete-order', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: `/orders/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        fetchOrders(currentPage);
                    }
                });
            });

            $('#search').on('keyup', function() {
                fetchOrders(1, $(this).val());
            });
        });
    </script>
</body>
</html>
