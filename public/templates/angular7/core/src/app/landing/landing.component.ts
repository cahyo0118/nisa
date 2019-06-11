import {Component, OnInit} from '@angular/core';
import {NgxSpinnerService} from 'ngx-spinner';

@Component({
    selector: 'app-landing',
    templateUrl: './landing.component.html',
    styleUrls: ['./landing.component.css']
})
export class LandingComponent implements OnInit {

    constructor(private spinner: NgxSpinnerService,) {
        this.spinner.show()
    }

    ngOnInit() {
        this.spinner.hide()
    }

}
