<div class="modal fade" id="confirm">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure want to delete this data ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="delete-btn" type="button" class="btn btn-primary">Confirm</button>
{{--                {!! Form::submit('delete', ['id' => 'delete-btn', 'class' => 'btn btn-primary', 'name' => 'delete_modal']) !!}--}}
            </div>
        </div>
    </div>
</div>
