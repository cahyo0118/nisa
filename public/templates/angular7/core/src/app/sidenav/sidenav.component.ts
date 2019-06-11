import {Component, OnInit} from '@angular/core';

@Component({
    selector: 'app-sidenav',
    templateUrl: './sidenav.component.html',
    styleUrls: ['./sidenav.component.css']
})
export class SidenavComponent implements OnInit {

    constructor() {
    }

    ngOnInit() {
    }

    isAllowed(permissionName): boolean {
        return JSON.parse(localStorage.getItem('userinfo')).permissions.find(obj => obj === permissionName);
    }

}
