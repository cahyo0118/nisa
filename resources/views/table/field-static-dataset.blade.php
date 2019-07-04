<div class="col-lg-12">
    <div id="relation{{ $random }}" class="card" style="margin-bottom: 30px">

        <div class="card-body">

            <h3>
                # Static Dataset
                <button class="btn btn-primary btn-sm float-right"
                        type="button"
                        onclick="createRelation('{{ $random }}', '{{ $project_id }}', {{ !empty($item) ? $item->id : 0 }})">
                    Use dynamic dataset
                </button>

                {!! Form::hidden("dataset_type[$random]", "static") !!}
            </h3>

            <div class="row">
                <div class="col-12">
                    <button class="btn btn-icon btn-3 btn-primary btn-sm" type="button"
                            data-toggle="modal"
                            data-target="#addStaticDatasetModal{{ !empty($item) ? $item->id : 0 }}">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>

                        <span class="btn-inner--text">Add new data</span>

                    </button>
                </div>

                <div class="col-12">
                    <br>
                    <div class="table-responsive">
                        <table class="table align-items-center" data-toggle="dataTable"
                               data-form="deleteForm">
                            <thead>
                            <tr>
                                <th scope="col">Value</th>
                                <th scope="col">Label</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="static_datasets{{ !empty($item) ? $item->id : 0 }}">

                            @foreach($static_datasets as $static_dataset)
                                @include('table.partials.static-dataset-item', ['static_dataset' => $static_dataset])
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                    <br>
                </div>
            </div>

            <button class="btn btn-icon btn-3 btn-danger btn-sm" type="button"
                    onclick="deleteRelation('{{ $random }}', {{ !empty($item) ? $item->id : 0 }})">
                <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>

                <span class="btn-inner--text">Delete Relation</span>

            </button>

        </div>

    </div>

    @include('table.modals.add-static-dataset-modal')

</div>
