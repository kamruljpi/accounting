@extends('layouts.dashboard')
@section('page_heading','Trial Balance List')
@section('section')

<style type="text/css">
    .table{
            margin-bottom: 5px;
    }
</style>

<div class="col-sm-12" id="demo">
    <div class="row">
        <div class="col-sm-12 input_table_specific">
            @if(count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                          <li><span>{{ $error }}</span></li>
                        @endforeach
                    </div>
            @endif

            <form class="form-horizontal" method="post" id="TrailbalanceForm">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="col-md-10 row">
                    <div class="col-md-6 row">
                        <div class="col-md-3">
                            Form:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" placeholder="Search date...." name="TrailbalanceDateSearchFldFrom" id="TrailbalanceDateSearchFldFrom" value="{{ Session::get('Trailbalance_fromDate')}}"  >
                            <p id="search"></p>
                        </div>
                    </div>

                    <div class="col-md-6 row">
                        <div class="col-md-3">
                            To:
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" placeholder="Search date...." name="TrailbalanceDateSearchFldTo" id="TrailbalanceDateSearchFldTo" value="{{ Session::get('Trailbalance_toDate') }}" >
                            <p id="search"></p>
                        </div>
                    </div>
                </div>

            
                <div class="col-md-2">
                    <input class="form-control btn btn-primary btn-outline" type="submit" value="search" id="generalTrailbalanceSearchBtn">
                </div>
            </form>
        </div>

        <div class="preLoadImageView" style="display: none;">
            <center>
                <img src="{{ asset("assets/images/preLoader_2.gif") }}">
            </center>
        </div>
        <div class="TrailbalanceDetailsView"></div>
        <div class="dataNotFoundMessage" style="margin-top: 10%; margin-left: 9%;"></div>
        
        <div class="TrailbalanceDataView" style="display: none;">
            <p class="dateFrom"></p> 
            <p class="dateTo"></p>
            <table class="table table-bordered table-striped">
                <thead>
                    {{-- <tr>
                        <th colspan="5"><center>Account Name</center></th>
                        <th colspan="2"><center>Balance</center></th>
                    </tr>
                    <tr>
                        <th>Chart of Account Name</th>
                        <th>Head Sub-Class Name</th>
                        <th>Head Class Name</th>
                        <th>Sub-Head Name</th>
                        <th>Head Name</th>
                        <th>Debit/Deposit</th>
                        <th>Credit/Withdraw</th>
                    </tr> --}}
                    <tr>
                        <th>Account Name</th>
                        <th>Debit of Closing Balance</th>
                        <th>Credit of Closing Balance</th>
                    </tr>
                </thead>
                <tbody id="TrailbalanceTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset("js/trialBalance.js") }}"></script>
@stop
