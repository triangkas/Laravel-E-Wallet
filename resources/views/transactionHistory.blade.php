<x-app-layout>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 13px auto;
        }

        th {
            background-color: #f3f3f3;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 style="font-size: 24px">Total Saldo: Rp. {{ $deposit }}</h1>
                    <br />
                    <table width="100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$list_data->isEmpty())
                                @foreach($list_data as $rows)
                                    <tr>
                                        <td align="center">{{ date('Y-m-d H:i:s', strtotime($rows->timestamp)) }}</td>
                                        <td align="center">{{ $rows->order_id }}</td>
                                        <td align="right">
                                            @if($rows->type == 'C') + 
                                            @elseif($rows->type == 'D') -
                                            @endif
                                            Rp. {{ number_format($rows->amount, 2, ',','.') }}
                                        </td>
                                        <td align="center">
                                            @if($rows->status == '1')Success
                                            @elseif($rows->status == '2')Failed
                                            @endif
                                        </td>
                                        <td align="left">{{ $rows->description }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="100%" align="center">Data not found</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $list_data->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>