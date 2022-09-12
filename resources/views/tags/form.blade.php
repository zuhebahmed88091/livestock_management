<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">{{__('commons.title')}}</label>
            <input class="form-control" name="title" type="text" id="title"
                value="{{ old('title', optional($tag)->title) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

