@extends('admin.main')

@section('head')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
@endsection

@section('content')
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Tên Sản Phẩm</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"  placeholder="Nhập tên sản phẩm">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Danh Mục</label>
                        <select class="form-control" name="menu_id">
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Sizes</label>
                <div class="size-container">
                    @foreach($sizes as $size)
                    <div class="size-item mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" 
                                   name="sizes[{{ $size->id }}][active]" 
                                   class="custom-control-input" 
                                   id="size{{ $size->id }}">
                            <label class="custom-control-label" for="size{{ $size->id }}">{{ $size->name }}</label>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <input type="number" 
                                       name="sizes[{{ $size->id }}][quantity]" 
                                       class="form-control" 
                                       placeholder="Số lượng">
                            </div>
                            <div class="col-md-6">
                                <input type="number" 
                                       name="sizes[{{ $size->id }}][price]" 
                                       class="form-control" 
                                       placeholder="Giá (nếu khác)">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Giá Gốc</label>
                        <input type="number" name="price" onkeyup="formatNumber(this)"  class="form-control" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="menu">Giá Giảm</label>
                        <input type="number" name="price_sale" onkeyup="formatNumber(this)" value="{{ old('price_sale') }}"  class="form-control" >
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Mô Tả </label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Mô Tả Chi Tiết</label>
                <textarea name="content" id="content" class="form-control">{{ old('content') }}</textarea>
            </div>

            <div class="form-group">
                <label for="thumb">Chọn hình ảnh</label>
                <input type="file" id="imageInput" name="thumb" accept="image/*" onchange="previewImage(this);">
                <img id="preview" src="#" alt="Preview" style="max-width: 100px; display: none;">
                @error('thumb')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            
            <script>
            function previewImage(input) {
                var preview = document.getElementById('preview');
                
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '#';
                    preview.style.display = 'none';
                }
            }
            </script>

            <div class="form-group">
                <label>Kích Hoạt</label>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" value="1" type="radio" id="active" name="active" checked="">
                    <label for="active" class="custom-control-label">Có</label>
                </div>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" value="0" type="radio" id="no_active" name="active" >
                    <label for="no_active" class="custom-control-label">Không</label>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
        </div>
        @csrf
    </form>
    
@endsection

@section('footer')
    <script>
        CKEDITOR.replace('content');
        function formatNumber(input) {
    var value = input.value.replace(/\D/g, "");
    var formatted = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    input.value = formatted;
}  
    </script>
@endsection