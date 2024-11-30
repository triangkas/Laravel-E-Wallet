<x-app-layout>
    <style>
        .topup-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .topup-button:hover {
            background-color: #45a049;
        }

        .topup-button:active {
            background-color: #388e3c;
            transform: scale(0.98);
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Simulasi Update Wallet') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" style="text-align: center">
                    Click for Top Up Deposit
                    <br /><label id="infoTopup"></label>
                    <br /><br />
                    <button class="topup-button" onclick="topUpWallet(25000)">25.000</button>
                    <button class="topup-button" onclick="topUpWallet(50000)">50.000</button>
                    <button class="topup-button" onclick="topUpWallet(100000)">100.000</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
        function topUpWallet(nominal){
            $('#infoTopup').html('Please wait...');
            $('.topup-button').attr('disabled','disabled');
            $.ajax({
                url : "{{ route('update_wallet.queue') }}",
                data : {"_token": "{{ csrf_token() }}", "customerId": "{{ Auth::user()->id}}", "amount": nominal},
                type: "POST",
                success: function(response){
                    $('#infoTopup').html(response['message']);
                    $('.topup-button').removeAttr('disabled');
                },
                error: function (xhr) {
                    console.log(xhr)
                    $('.topup-button').removeAttr('disabled');
                }
            });
        }
    </script>
</x-app-layout>