@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <a href="#" class="btn btn-primary btn-add-subscriber">Add Subscriber</a>

        <div class="col-md-12">
            <table class="table table-striped table-subscribers">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal modal-update-subscriber" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Field</h5>
                <button type="button" class="close" data-dismiss="modal" 
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" class="update-subscriber-form" 
                accept-charset="utf-8">

                <div class="modal-body">
                    
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
<script src="{{ mix('js/subscribers.js') }}" defer></script>
@endsection