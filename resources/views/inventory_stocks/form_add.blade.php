<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('inventory_id') ? 'has-error' : '' }}">
            <label for="inventory_id">{{__('inventory.inventory')}}</label>
            <select class="form-control  select-admin-lte" id="inventory_id" name="inventory_id" required>
                @foreach ($inventories as $key => $inventory)
                <option value="{{ $key }}"
                    {{ old('inventory_id', optional($inventoryStock)->inventory_id) == $key
                     || $selectedInventory->id == $key ? 'selected' : '' }}>
                    {{ $inventory }}
                </option>
                @endforeach
            </select>
            {!! $errors->first('inventory_id', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
            <label for="quantity">{{__('inventory.quantity')}} ({{ $selectedInventory->unit }})</label>
            <input class="form-control" name="quantity" type="number" id="quantity"
                value="{{ old('quantity', optional($inventoryStock)->quantity) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('quantity', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cost') ? 'has-error' : '' }}">
            <label for="cost">{{__('commons.cost')}}</label>
            <input class="form-control" name="cost" type="number" id="cost"
                   value="{{ old('cost', optional($inventoryStock)->cost) }}" min="-2147483648" max="2147483647" required>
            {!! $errors->first('cost', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

