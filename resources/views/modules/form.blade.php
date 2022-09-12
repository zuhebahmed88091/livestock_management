<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">{{__('commons.name')}}</label>
            <input class="form-control" name="name" type="text" id="name"
                   value="{{ old('name', optional($module)->name) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
            <label for="slug">{{__('adminPrivilege.slug')}}</label>
            <input class="form-control" name="slug" type="text" id="slug"
                   value="{{ old('slug', optional($module)->slug) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('fa_icon') ? 'has-error' : '' }}">
            <label for="fa_icon">{{__('adminPrivilege.fa_icon')}}</label>
            <input class="form-control" name="fa_icon" type="text" id="fa_icon"
                   value="{{ old('fa_icon', optional($module)->fa_icon) }}" minlength="1" maxlength="20" required>
            {!! $errors->first('fa_icon', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control" id="status" name="status">
                @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                    <option value="{{ $key }}" {{ old('status', optional($module)->status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('sorting') ? 'has-error' : '' }}">
            <label for="sorting">{{__('commons.sorting')}}</label>
            <input class="form-control" name="sorting" type="text" id="sorting"
                   value="{{ old('sorting', optional($module)->sorting) }}" min="-32768" max="32767" required>
            {!! $errors->first('sorting', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

@section('javascript')
    <script>
        $(document).ready(function () {
            "use strict";
            $('#name').change(function () {
                let name = $(this).val();
                name = name.replace(/\s+/g, "-");
                $('#slug').val(name.toLocaleLowerCase())
            });
        });
    </script>
@endsection
