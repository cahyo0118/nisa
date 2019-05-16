import {DatepickerOptions} from 'ng2-datepicker';

export class Environment {
    static ITEM_PER_PAGE = {{ $project->item_per_page }};

    static DEFAULT_DATE_PICKER_OPTIONS: DatepickerOptions = {
        displayFormat: 'DD/MM/YYYY'
    };

    static locale = 'id';

    static CLIENT_ID = {{ QueryHelpers::getVariable($template_name, 'CLIENT_ID', $project->id) }};
    static CLIENT_SECRET = '{{ QueryHelpers::getVariable($template_name, 'CLIENT_SECRET', $project->id) }}';

    static SERVER_URL = '{{ QueryHelpers::getVariable($template_name, 'BASE_URL', $project->id) }}';
    SERVER_URL = '{{ QueryHelpers::getVariable($template_name, 'BASE_URL', $project->id) }}';
}
