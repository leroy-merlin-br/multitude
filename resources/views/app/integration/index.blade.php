@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Integration</h1>
            <hr>
            <p style="font-size: 1.2rem;">
                Multitude supports scheduled integrations and data dumps for external systems.
                Follow the instructions bellow to get an integration up and running.
            </p>
        </div>
        <div class="col-md-4 col-xs-12">
            <img src="http://image.flaticon.com/icons/svg/33/33616.svg" width="50%" align="center">
            <p>
                Setup and schedule the export of a CSV file containing the latest interactions to S3 or a FTP server.
                <a class="button button-primary" style="width:100%; text-align:center;">
                    Setup CSV dump
                </a>
            </p>
        </div>
        <div class="col-md-4 col-xs-12">
            <img src="http://image.flaticon.com/icons/svg/4/4426.svg" width="50%" align="center">
            <p>
                Setup a connection with a SQL database in order to insert the latest interactions in there.
                <a class="button button-primary" style="width:100%; text-align:center;">
                    Setup SQL insertion
                </a>
            </p>
        </div>
        <div class="col-md-4 col-xs-12">
            <img src="http://image.flaticon.com/icons/svg/234/234964.svg" width="50%" align="center">
            <p>
                Setup and schedule a
                <a href="https://docs.mongodb.com/v3.2/reference/program/mongodump/" target="_blank">mongodump</a>
                containing the interactions and customers to S3 or a FTP server.
                <a class="button button-primary" style="width:100%; text-align:center;">
                    Setup Mongo dump
                </a>
            </p>
        </div>
    </div>
@stop
