@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Customers</h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <h2>Find customer</h2>
            <form method="GET" data-module="GardenForm">
                <div class="field addon-right">
                  <input type="text" class="input" name="search" id="search" value="{{ $search ?? '' }}"/>
                  <label for="search" class="label">Id, email or document number</label>
                  <div class="addon right"><span class="glyph glyph-magnifier"></span></div>
                </div>
                <button type="submit" class="button button-full button-primary">Find</button>
            </form>
        </div>

        <div class="col-xs-12">
            <h3 class="link">zizaco@gmail.com</h3>
            <img data-module="Gravatar" data-email="zizaco@gmail.com" class="circular">

            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>When</th>
                        <th></th>
                        <th>Interaction</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 10; $i++)
                    <tr>
                        <td>2 hours ago</td>
                        <th><div class="glyph glyph-magnifier"></div></th>
                        <td>Visited category</td>
                        <td>Banheiros</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@stop
