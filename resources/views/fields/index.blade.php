@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <a href="#" class="btn btn-primary btn-add-field">Add Field</a>

        <div class="col-md-12">
            <table class="table table-striped table-fields">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Placeholder</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal modal-update-field" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Field</h5>
                <button type="button" class="close" data-dismiss="modal" 
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" class="update-field-form" 
                accept-charset="utf-8">
                <input type="hidden" name="field_id" id="field-id">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="field-title" class="form-label">Title</label>
                        <input type="text" name="field_title" id="field-title" 
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="field-type" class="form-label">Type</label>
                        <select name="field_type" class="form-control" id="field-type">
                            @foreach($allowedTypes as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" 
                        data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ mix('js/fields.js') }}" defer></script>
@endsection