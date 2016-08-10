@extends('layouts.app')

@section('content')
<section class="container">
    <div class="card card-padded">

        {{-- success message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- subscription info --}}
        <div class="section-header">
            <h2>Your Subscription</h2>
        </div>

        @if ( ! $user->subscribed('main'))
            <div class="jumbotron text-center">
                <p>You don't have a subscription.</p>
                <a href="/subscribe" class="btn btn-success btn-lg"></a>
            </div>
        @else
            
            <div class="row">
                <div class="col-sm-6">

                    {{-- current plan --}}
                    <div class="well text-center">
                        <strong>Current Plan:</strong> {{ ucfirst($user->subscription('main')->stripe_plan) }}
                    </div>

                </div>
                <div class="col-sm-6">
                    
                    {{-- update subscription form --}}
                    <h4>Update Subscription</h4>

                    <form action="/account/subscription" method="POST">
                        {!! csrf_field() !!} 

                        <div class="form-group">
                            <select name="plan" class="form-control">
                                <option value="bronze" 
                                    {{ ($user->subscription('main')->stripe_plan) == 'bronze' ? 'selected' : '' }}>
                                    Bronze ($5/mo)
                                </option>
                                <option value="silver" 
                                    {{ ($user->subscription('main')->stripe_plan) == 'silver' ? 'selected' : '' }}>
                                    Silver ($10/mo)
                                </option>
                                <option value="gold" 
                                    {{ ($user->subscription('main')->stripe_plan) == 'gold' ? 'selected' : '' }}>
                                    Gold ($15/mo)
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Plan
                        </button>
                    </form>

                </div>
            </div>
        @endif

        {{-- credit card section --}}
        <div class="section-header">
            <h2>Update Card</h2>
        </div>

        <form action="/account/card" method="POST" id="subscribe-form">
            {!! csrf_field() !!}

            <div class="form-group row">
                {{-- credit card number --}}
                <div class="col-xs-8">
                    <label>Credit Card Number</label>
                    <input type="text" class="form-control" placeholder="**** **** **** {{ $user->card_last_four }}" data-stripe="number">
                </div>

                {{-- cvc --}}
                <div class="col-xs-4">
                    <label>CVC</label>
                    <input type="text" class="form-control" placeholder="123" data-stripe="cvc">
                </div>
            </div>

            <div class="form-group row">

                {{-- exp month --}}
                <div class="col-xs-3">
                    <label>Expiration Month</label>
                    <input type="text" class="form-control" placeholder="08" data-stripe="exp-month">
                </div>

                {{-- exp year --}}
                <div class="col-xs-3">
                    <label>Expiration Year</label>
                    <input type="text" class="form-control" placeholder="2020" data-stripe="exp-year">
                </div>
            </div>        

            <div class="stripe-errors"></div>

            <button type="submit" class="btn btn-primary">Update Card</button>

        </form>

        {{-- billing history --}}
        <div class="section-header">
            <h2>Billing History</h2>
        </div>

        @if (count($invoices) > 0)
            <table class="table table-bordered table-striped table-hover">
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                        <td>{{ $invoice->total() }}</td>
                        <td class="col-xs-2">
                            <a href="/account/invoices/{{ $invoice->id }}" class="btn btn-primary btn-sm">Download</a>
                        </td>
                    </tr>
                @endforeach 
            </table>
        @else
            <div class="jumbotron text-center">
                <p>No billing history</p>
            </div>
        @endif

        {{-- delete subscription --}}
        <div class="section-header">
            <h2>Delete Subscription</h2>
        </div>

    </div>
</section>
@endsection