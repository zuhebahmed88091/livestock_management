<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">{{__('commons.name')}}</label>
            <input class="form-control" name="name" type="text" id="name"
                   value="{{ old('name', optional($user)->name) }}" minlength="1" maxlength="191" required>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">{{__('adminPrivilege.email')}}</label>
            <input class="form-control" name="email" type="text" id="email"
                   value="{{ old('email', optional($user)->email) }}" minlength="1" maxlength="191" required>
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">{{__('adminPrivilege.password')}}</label>
            <input class="form-control" name="password" type="password" id="password"
                   minlength="1" maxlength="191" {{ empty($user) ? 'required' : '' }}>
            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <label for="password-confirm">{{__('adminPrivilege.confirm_password')}}</label>
            <input class="form-control" name="password_confirmation" type="password" id="password-confirm"
                   minlength="1" maxlength="191" {{ empty($user) ? 'required' : '' }}>
            {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
            <label for="country_id">{{__('adminPrivilege.country')}}</label>
            <select class="form-control select-admin-lte"
                    id="country_id"
                    name="country_id"
                    data-placeholder="----------Select----------"
                    required>
                <option value="" class="d-none" selected></option>
                @foreach ($countries as $key => $text)
                    <option value="{{ $key }}" {{ old('country_id', optional($user)->country_id) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('country_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="phone">{{__('adminPrivilege.phone')}}</label>
            <input class="form-control" name="phone" type="text" id="phone"
                   value="{{ old('phone', optional($user)->phone) }}"
                   minlength="1" maxlength="20" required>
            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('co') ? 'has-error' : '' }}">
            <label for="status">{{__('commons.status')}}</label>
            <select class="form-control select-admin-lte"
                    id="status"
                    name="status"
                    data-placeholder="----------Select----------"
                    required>
                <option value="" class="d-none" selected></option>
                @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                    <option value="{{ $key }}" {{ old('status', optional($user)->status) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
            <label for="gender">{{__('commons.gender')}}</label>
            <select class="form-control select-admin-lte"
                    id="gender"
                    name="gender"
                    data-placeholder="----------Select----------"
                    required>
                <option value="" class="d-none" selected></option>
                @foreach (['Male' => 'Male', 'Female' => 'Female'] as $key => $text)
                    <option value="{{ $key }}" {{ old('gender', optional($user)->gender) == $key ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
            <label for="photo">{{__('commons.photo')}}</label>
            <div class="input-group">
                @if(!empty($user))
                    <label class="input-group-btn">
                        <img src="{{ asset('storage/profiles/' . optional($user->uploadedFile)->filename) }}"
                             alt="Profile Image"
                             class="form-image">
                    </label>
                @endif
                <input value="{{ !empty($user) ? optional($user->uploadedFile)->original_filename : '' }}"
                       type="text"
                       id="photo"
                       class="form-control" readonly {{ empty($user) ? 'required' : '' }}>
                <label class="input-group-btn">
                    <span class="btn btn-warning">
                        Browse&hellip; <input type="file" name="photo" class="d-none-imp">
                    </span>
                </label>
            </div>
            {!! $errors->first('photo', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('assign_roles') ? 'has-error' : '' }}">
            <label for="assign_roles">{{__('adminPrivilege.assign_roles')}}</label>
            <select class="form-control select-admin-lte"
                    id="assign_roles"
                    name="assign_roles[]"
                    multiple="multiple"
                    data-placeholder="----------Select Multiple----------"
                    required>
                @foreach ($roles as $key => $text)
                    @php ($assign_roles = old('assign_roles', !empty($user) ? $user->roles->pluck('id')->toArray() : ''))
                    <option value="{{ $key }}" {{ !empty($assign_roles) && in_array($key, $assign_roles) ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('assign_roles', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</div>
