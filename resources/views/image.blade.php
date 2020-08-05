@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">        
        <div class="col-md-10">
            @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
            @endif
            <div id="wrapper">                     
                <form enctype="multipart/form-data" action="{{ Route('store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="image" class="col-md-4 col-form-label text-md-left">Загрузите картинку</label>  
                        <div class="col-md-12">
                          <input id="image" type="file" class="@error('image') is-invalid @enderror" name="image" value="{{ old('image') }}" accept="image/*" autofocus>
                        </div>
                        @error('image')
                          <span style="display: block;" class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                    </div>
                    <input type="hidden" name="positions" id="positions">
                    <div class="form-group text-right">  
                        <div class="col-md-12">
                            <input type="submit" name="submit" id="submit" class="btn btn-success" disabled value="Сохранить">
                        </div>
                    </div>
                </form>
                <div class="preview">
                    <h3>Preview</h3>
                    <span class="alert-info">Разделите картинку на 4 части</span>
                    <div class="image-decorator">
                        <img alt="Image principale" class="imagePreview" id="example" src="/images/example.jpg"/>
                    </div>
                    <h3>Example</h3>
                    <div class="image-decorator">
                        <img width="300" src="/images/example.jpg" alt="Example">
                    </div>
                </div>                
            </div>

            <script type="text/javascript">
                $(document).ready(function () {  
                    var _URL = window.URL || window.webkitURL;
                    $('#image').change(function() {                        
                        if (this.files) $.each(this.files, readAndPreview);                        
                        function readAndPreview(i, file) {
                            if (!/\.(jpe?g|png|gif|svg)$/i.test(file.name)) {
                                return alert(file.name + " Это не картинка!");
                            }

                            var reader = new Image();                            
                            $('img#example').attr('src', _URL.createObjectURL(file))
                            reader.onload = function () {                                
                                _URL.revokeObjectURL(objectUrl);
                            };                                                        
                                                    
                            $('.preview').css('display', 'block');
                            $('img#example').selectAreas('destroy');                                               
                            $('img#example').selectAreas({
                                minSize: [10, 10],
                                onChanged: debugQtyAreas,
                                maxAreas: 4                                
                            });                            
                        }                                                                    
                    })                                        
                });
                
                // Log the quantity of selections
                function debugQtyAreas (event, id, areas) {
                    console.log(areas.length + " areas", arguments); 
                    if (areas.length == 4) {
                        $('#submit').prop('disabled', false)
                    }                    
                };    

                $('#submit').click(function(e) {                    
                    var positions = JSON.stringify($('img#example').selectAreas('areas'));
                    console.log(positions)
                    $('#positions').val(positions)
                })                
            </script>
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Path</th>
                        <th scope="col">First Cut</th>
                        <th scope="col">Second Cut</th>
                        <th scope="col">Third Cut</th>
                        <th scope="col">Fourth Cut</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($crop_images as $item)
                        <tr>
                            <th scope="row">{{ $count }}</th>
                            <td>{{ $item->name_img }}</td>                            
                            <td> <a target="_blank" href="{{ asset($item->full_img) }}">Open</a></td>
                            @php
                                $croped_img = json_decode($item->croped_img);
                            @endphp
                            @foreach ($croped_img as $crop)
                                <td> <a target="_blank" href="{{ asset($crop) }}">Open</a></td>
                            @endforeach
                        </tr>
                        @php
                            $count++;
                        @endphp
                    @endforeach
                </tbody>
            </table>                            
        </div>
    </div>
</div>
@endsection