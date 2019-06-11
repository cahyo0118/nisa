import {DatepickerOptions} from 'ng2-datepicker';

export class Environment {
    // Put your config variable here
    // Prod
    // static CLIENT_ID = 2;
    // static SERVER_URL = 'https://api.votever.id/';
    // static CLIENT_SECRET = 'yBndpApMUL1rrD8SepdLMSMAgo0XIajYkAoEU9w2';

    // Dev

    static ITEM_PER_PAGE = 15;

    static DEFAULT_DATE_PICKER_OPTIONS: DatepickerOptions = {
        displayFormat: 'DD/MM/YYYY'
    };

    static locale = 'id';

    static CLIENT_ID = 2;
    static CLIENT_SECRET = 'zWDBuCJLr0AXv8QxCuqu63cHxmpBMqbSKut7QdYR';

    static SERVER_URL = 'http://localhost:8000/';
    SERVER_URL = 'http://localhost:8000/';
}
