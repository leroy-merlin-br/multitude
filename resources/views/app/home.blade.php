@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Dashboard</h1>
            <hr>
        </div>
        <div class="col-xs-12">
            <legend class="legend">Interactions pulse</legend>
            <div class="interacton-canvas"
                data-module="InteractionCanvas"
                data-endpoint="{{ route("interaction.pulse") }}"
                data-interaction-counter="#interactionCount"
                data-customer-counter="#customerCount">
            </div>
            <hr>
        </div>
        <div class="col-xs-12">
            <legend class="legend">Core metrics</legend>
            <div class="field">
                <span class="label" style="font-size:1.2em">
                    <span id="interactionCount" data-count={{ $interactionCount }}>{{ $interactionCount }}</span> <strong>interactions</strong>
                </span>
                <span class="helper">
                    Total number of interactions that have been processed by multitude.
                </span>
            </div>
            <div class="field">
                <span class="label" style="font-size:1.2em">
                    <span id="customerCount" data-count={{ $customerCount }}>{{ $customerCount }}</span> <strong>Customers</strong>
                </span>
                <span class="helper">
                    Total number of Customers that are ready for segmentation.
                </span>
            </div>
        </div>
    </div>
@stop
