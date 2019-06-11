import {Component, Input, OnInit} from '@angular/core';

@Component({
    selector: 'app-form-error-message',
    templateUrl: './form-error-message.component.html',
    styleUrls: ['./form-error-message.component.css']
})
export class FormErrorMessageComponent implements OnInit {

    @Input() apiValidationErrors;
    @Input() errors;
    @Input() label;
    @Input() name;

    constructor() {
    }

    ngOnInit() {
        console.log(this.name);
        console.log(this.label);
        console.log(this.errors);
        console.log(this.apiValidationErrors);
    }

}
