@extends('layouts.dashboard')
{{-- @section('page_heading','Add Role') --}}
@section('section')
<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>
<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12">
            @if(count($errors) > 0)
            <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $error)
                <li>
                    <span>
                        {{ $error }}
                    </span>
                </li>
                @endforeach
            </div>
            @endif

            @if(Session::has('product_purchase'))
                @include('widgets.alert', array('class'=>'success', 'message'=> Session::get('product_purchase') ))
            @endif
            <form action="{{ Route('product_purchase_add') }}" method="post" role="form">
                <select class="form-control input_required" name="client_id" required="" style="max-width: 200px;">
                    <option value="">
                        Select Client
                    </option>
                    @foreach($clients as $client)
                    <option class="" value="{{ $client->user_id }}">
                        {{ $client->first_name }}
                    </option>
                    @endforeach
                </select>
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <table class="table table-bordered purchase_table" id="tblSearch">
                        <thead>
                            <tr>
                                <tr>
                                    <th class="">
                                        Product Name
                                    </th>
                                    <th class="">
                                        Quantity
                                    </th>
                                    @foreach($vattaxes as $vattax)
                                    <th class="">
                                        {{ $vattax->name }}
                                    </th>
                                    @endforeach
                                    <th class="">
                                        Purchase Price
                                    </th>
                                    <th>
                                        Remove
                                    </th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="purchase_table_row">
                                <td>
                                    <select class="form-control" name="product_id[]">
                                        <?php $i = 0;?>
                                        @foreach($products as $product)
                                        <option class="" value="{{ $product->id }}">
                                            {{ $product->pro_name }} {{ $product->pac_name }}({{ $product->quantity }}) {{ $product->unit_name }}({{ $product->unit_quantity }})
                                        </option>
                                        <?php $i++;?>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="quantity[]" placeholder="Quantity" type="text">
                                    </input>
                                </td>
                                <?php $i = 1;?>
                                @foreach($vattaxes as $vattax)
                                <td>
                                    <input name="vattax_percentage[]" placeholder="{{ $vattax->name }}" type="text">
                                        <input name="vattax_id[{{ $i-1 }}]" type="hidden" value="{{ $vattax->id }}">
                                            <input name="vattax_column_num" type="hidden" value="{{ $i }}">
                                            </input>
                                        </input>
                                    </input>
                                </td>
                                <?php $i++;?>
                                @endforeach
                                <td>
                                    <input name="product_purchase_price[]" placeholder="Purchase Price" type="text">
                                    </input>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-outline" id="new_purchase_row_delete">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group pull-right">
                        <a class="btn btn-primary btn-outline" id="new_purchase_table_row">
                            New Row
                        </a>
                    </div>

                    <input name="company_id" type="hidden" value="{{ $request->session()->get('company_id') }}">
                    <input name="com_group_id" type="hidden" value="{{ $request->session()->get('group_id') }}">
                            <div class="form-group col-sm-2 pull-right" style="padding-right: 0px;">
                                <input class="form-control btn btn-primary btn-outline" style="float: right;" type="submit" value="SAVE">
                                </input>
                            </div>
                        </input>
                    </input>
                </input>
            </form>
        </div>
    </div>
</div>
@stop
