<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">{{__('commons.name')}}</label>
            <input class="form-control" name="name" type="text" id="name"
                value="{{ old('name', optional($inventory)->name) }}" minlength="1" maxlength="255" required>
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('inventory_image') ? 'has-error' : '' }}">
            <label for="inventory_image">{{__('inventory.inventory_image')}}</label>
            
            <div class="input-group uploaded-file-group">
                @if(!empty($inventory->inventory_image))
                    <label class="input-group-btn" for="inventory_image_path">
                        <img src="{{ asset('storage/' . $inventory->inventory_image) }}"
                             alt="Inventory Image"
                             class="form-image">
                    </label>
                @endif

                <input type="text" class="form-control uploaded-file-name"
                    value="{{ optional($inventory)->inventory_image }}"
                    id="inventory_image_path"
                    readonly>
                <label class="input-group-btn">
                    <span class="btn btn-warning">
                        Browse <input type="file" name="inventory_image" id="inventory_image" class="hidden">
                    </span>
                </label>
            </div>

            {!! $errors->first('inventory_image', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('inventory_type_id') ? 'has-error' : '' }}">
            <label for="inventory_type_id">{{__('inventory.inventory_type')}}</label>
            <select class="form-control  select-admin-lte" id="inventory_type_id" name="inventory_type_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($inventoryTypes as $key => $inventoryType)
                <option value="{{ $key }}" {{ old('inventory_type_id', optional($inventory)->inventory_type_id) == $key ? 'selected' : '' }}>
                    {{ $inventoryType }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('inventory_type_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('inventory_unit_id') ? 'has-error' : '' }}">
            <label for="inventory_unit_id">{{__('inventory.inventory_unit')}}</label>
            <select class="form-control  select-admin-lte" id="inventory_unit_id" name="inventory_unit_id" required>
                <option value="">-----{{__('commons.select')}}-----</option>
                @foreach ($inventoryUnits as $key => $inventoryUnit)
                <option value="{{ $key }}" {{ old('inventory_unit_id', optional($inventory)->inventory_unit_id) == $key ? 'selected' : '' }}>
                    {{ $inventoryUnit }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('inventory_unit_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('source') ? 'has-error' : '' }}">
            <label for="source">{{__('inventory.source')}}</label>
            <input class="form-control" name="source" type="text" id="source"
                value="{{ old('source', optional($inventory)->source) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('source', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('warranty') ? 'has-error' : '' }}">
            <label for="warranty">{{__('inventory.warranty')}}</label>
            <input class="form-control" name="warranty" type="text" id="warranty"
                value="{{ old('warranty', optional($inventory)->warranty) }}" minlength="1" maxlength="100" required>
            {!! $errors->first('warranty', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
            <label for="description">{{__('commons.description')}}</label>
                    <textarea class="form-control" name="description" cols="50" rows="3" id="description" required>{{ old('description', optional($inventory)->description) }}</textarea>

            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('instruction') ? 'has-error' : '' }}">
            <label for="instruction">{{__('commons.instruction')}}</label>
                    <textarea class="form-control" name="instruction" cols="50" rows="3" id="instruction" required>{{ old('instruction', optional($inventory)->instruction) }}</textarea>

            {!! $errors->first('instruction', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

