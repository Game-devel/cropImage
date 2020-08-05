@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
           
            <div id="wrapper">
                <h1>jQuery Select Areas Plugin Demos</h1>
    
                <div class="image-decorator">
                    <img alt="Image principale" id="example" src="/images/example.jpg"/>
                </div>
                <table>
                    <tr>
                        <td class="actions">
                            <input type="button" id="btnView" value="Display areas" class="actionOn" />
                            <input type="button" id="btnViewRel" value="Display relative" class="actionOn" />
                            <input type="button" id="btnNew" value="Add New" class="actionOn" />
                            <input type="button" id="btnNews" value="Add 2 New" class="actionOn" />
                            <input type="button" id="btnReset" value="Reset" class="actionOn" />
                            <input type="button" id="btnDestroy" value="Destroy" class="actionOn" />
                            <input type="button" id="btnCreate" value="Create" class="actionOff" />
                        </td>
                        <td>
                            <div id="output" class='output'> </div>
                        </td>
                    </tr>
                </table>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {                    
                    $('img#example').selectAreas({
                        minSize: [10, 10],
                        onChanged: debugQtyAreas,
                        maxAreas: 4,
                        width: 500,
                        areas: [
                            {
                                x: 10,
                                y: 20,
                                width: 60,
                                height: 100,
                            }                            
                        ]
                    });                    
                    $('#btnView').click(function () {
                        var areas = $('img#example').selectAreas('areas');
                        displayAreas(areas);
                    });
                    $('#btnViewRel').click(function () {
                        var areas = $('img#example').selectAreas('relativeAreas');
                        displayAreas(areas);
                    });
                    $('#btnReset').click(function () {
                        output("reset")
                        $('img#example').selectAreas('reset');
                    });
                    $('#btnDestroy').click(function () {
                        $('img#example').selectAreas('destroy');
    
                        output("destroyed")
                        $('.actionOn').attr("disabled", "disabled");
                        $('.actionOff').removeAttr("disabled")
                    });
                    $('#btnCreate').attr("disabled", "disabled").click(function () {
                        $('img#example').selectAreas({
                            minSize: [10, 10],
                            onChanged : debugQtyAreas,
                            maxAreas: 4,
                            width: 500,
                        });
    
                        output("created")
                        $('.actionOff').attr("disabled", "disabled");
                        $('.actionOn').removeAttr("disabled")
                    });
                    $('#btnNew').click(function () {
                        var areaOptions = {
                            x: Math.floor((Math.random() * 200)),
                            y: Math.floor((Math.random() * 200)),
                            width: Math.floor((Math.random() * 100)) + 50,
                            height: Math.floor((Math.random() * 100)) + 20,
                        };
                        output("Add a new area: " + areaToString(areaOptions))
                        $('img#example').selectAreas('add', areaOptions);
                    });
                    $('#btnNews').click(function () {
                        var areaOption1 = {
                            x: Math.floor((Math.random() * 200)),
                            y: Math.floor((Math.random() * 200)),
                            width: Math.floor((Math.random() * 100)) + 50,
                            height: Math.floor((Math.random() * 100)) + 20,
                        }, areaOption2 = {
                            x: areaOption1.x + areaOption1.width + 10,
                            y: areaOption1.y + areaOption1.height - 20,
                            width: 50,
                            height: 20,
                        };
                        output("Add a new area: " + areaToString(areaOption1) + " and " + areaToString(areaOption2))
                        $('img#example').selectAreas('add', [areaOption1, areaOption2]);
                    });
                });
    
                var selectionExists;
    
                function areaToString (area) {
                    return (typeof area.id === "undefined" ? "" : (area.id + ": ")) + area.x + ':' + area.y  + ' ' + area.width + 'x' + area.height + '<br />'
                }
    
                function output (text) {
                    $('#output').html(text);
                }
    
                // Log the quantity of selections
                function debugQtyAreas (event, id, areas) {
                    console.log(areas.length + " areas", arguments);
                };
    
                // Display areas coordinates in a div
                function displayAreas (areas) {
                    var text = "";
                    $.each(areas, function (id, area) {
                        text += areaToString(area);
                    });
                    output(text);
                };
    
            </script>
            
            {{-- <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">No</th>
                        <th scope="col">Invoice data</th>
                        <th scope="col">Supply</th>
                        <th scope="col">Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($invoices as $item)
                        <tr>
                            <th scope="row">{{ $count }}</th>
                            <td>{{ $item->number }}</td>
                            <td>{{ $item->invoice_date }}</td>
                            <td>{{ $item->supply_date }}</td>
                            <td>{{ $item->comment }}</td>
                        </tr>
                        @php
                            $count++;
                        @endphp
                    @endforeach                    
                </tbody>
            </table> --}}                            
        </div>
    </div>
</div>
@endsection