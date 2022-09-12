<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('filename') ? 'has-error' : '' }}">
            <label for="filename">Filename</label>
            <input class="form-control" name="filename" type="text" id="filename"
                value="{{ old('filename', optional($uploadedFile)->filename) }}" minlength="1" maxlength="50" required>
            {!! $errors->first('filename', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('original_filename') ? 'has-error' : '' }}">
            <label for="original_filename">Original Filename</label>
            <input class="form-control" name="original_filename" type="text" id="original_filename"
                value="{{ old('original_filename', optional($uploadedFile)->original_filename) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('original_filename', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('file_type_id') ? 'has-error' : '' }}">
            <label for="file_type_id">File Type</label>
            <select class="form-control  select-admin-lte" id="file_type_id" name="file_type_id" required>
                <option value="" class="d-none" {{ old('file_type_id', optional($uploadedFile)->file_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select file type</option>
                @foreach ($fileTypes as $key => $fileType)
                <option value="{{ $key }}" {{ old('file_type_id', optional($uploadedFile)->file_type_id) == $key ? 'selected' : '' }}>
                    {{ $fileType }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('file_type_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            <label for="user_id">User</label>
            <select class="form-control  select-admin-lte" id="user_id" name="user_id" required>
                <option value="" class="d-none" {{ old('user_id', optional($uploadedFile)->user_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select user</option>
                @foreach ($users as $key => $user)
                <option value="{{ $key }}" {{ old('user_id', optional($uploadedFile)->user_id) == $key ? 'selected' : '' }}>
                    {{ $user }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

