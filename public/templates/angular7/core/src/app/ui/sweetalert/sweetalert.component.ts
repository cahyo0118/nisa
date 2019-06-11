import {Component, OnInit} from '@angular/core';
import {NgxSpinnerService} from 'ngx-spinner';

@Component({
    selector: 'app-sweetalert',
    templateUrl: './sweetalert.component.html',
    styleUrls: ['./sweetalert.component.css']
})
export class SweetalertComponent implements OnInit {

    constructor(
        private spinner: NgxSpinnerService,
    ) {
    }

    ngOnInit() {
    }


}
