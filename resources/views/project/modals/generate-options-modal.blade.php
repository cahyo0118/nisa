<div class="modal fade" id="generateOptionsModal{!! $item->id !!}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Generate Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                    <tr>
                        <td class="float-left">
                            <span class="fas fa-project-diagram"></span>
                            Laravel 5
                        </td>
                        <td class="w-100 justify-content-end">
                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                    class="btn btn-icon btn-dark btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                <span class="btn-inner--text">Generate</span>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="float-left">
                            <span class="fas fa-project-diagram"></span>
                            Android Native ( JAVA )
                            <span class="badge badge-primary">PRO</span>
                        </td>
                        <td class="w-100 justify-content-end">
                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                    class="btn btn-icon btn-dark btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                <span class="btn-inner--text">Generate</span>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="float-left">
                            <span class="fas fa-project-diagram"></span>
                            Ionic 4
                        </td>
                        <td class="w-100 justify-content-end">
                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                    class="btn btn-icon btn-dark btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                <span class="btn-inner--text">Generate</span>
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="float-left">
                            <span class="fas fa-project-diagram"></span>
                            Angular 7
                        </td>
                        <td class="w-100 justify-content-end">
                            <button onclick="onGenerateLaravel5({{ $item->id }})" type="button"
                                    class="btn btn-icon btn-dark btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-cog"></i></span>
                                <span class="btn-inner--text">Generate</span>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
